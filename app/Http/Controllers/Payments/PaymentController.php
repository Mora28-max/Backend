<?php

namespace App\Http\Controllers\Payments;

use Illuminate\Http\Request;
use App\Models\Payments\Payment;
use App\Models\Customers\Customer;
use App\Http\Controllers\Controller;
use App\Services\Payment\PaymentService;
use App\Http\Resources\Payment\PaymentResource;
use App\Http\Resources\Payment\PaymentCollection;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Requests\Payment\UpdatePaymentRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PaymentController extends Controller
{

    use AuthorizesRequests;

    public function __construct(protected PaymentService $payment_service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Payment::class);

        if (empty($request->customer_id))
            return response(['message' => 'No se especificó el usuario'], 400);

        $customer = Customer::findOrFail($request->customer_id);
        $payments = Payment::where('customer_id', $customer->id)
            ->orderBy('payment_date', 'desc')
            ->paginate(15)
            ->appends($request->all());

        return new PaymentCollection($payments);
    }

    public function store(StorePaymentRequest $request)
    {
        try {
            $this->authorize('create', Payment::class);
            $payment = $this->payment_service->createPayment($request->validated());
            return response([
                'message' => 'El cargo se ha registrado correctamente.',
                'payment' => new PaymentResource($payment)
            ], 201);
        } catch (\Throwable $th) {
            return response([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function show(Payment $payment)
    {
        $data = $this->payment_service->getPayment($payment);
        return new PaymentResource($data);
    }

    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        try {
            $this->authorize('update', $payment);
            $data = $request->validated();
            $payment = $this->payment_service->updatePayment($payment, $data);

            return response([
                'message' => 'El cargo se ha actualizado correctamente.',
                'payment' => new PaymentResource($payment->fresh())
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al actualizar el cargo.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Payment $payment)
    {
        try {
            $this->authorize('delete', $payment);
            $this->payment_service->cancelPayment($payment);
            return response(['message' => 'El cobro ha sido cancelado correctamente.']);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al cancelar el cobro.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
