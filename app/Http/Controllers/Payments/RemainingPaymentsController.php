<?php

namespace App\Http\Controllers\Payments;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Payments\RemainingPayment;
use App\Services\Payment\RemainingPaymentService;
use App\Services\Payment\PayRemainingPaymentService;
use App\Http\Resources\Payment\RemainingPaymentResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Resources\Payment\RemainingPaymentCollection;
use App\Http\Requests\Payment\StoreRemainingPaymentRequest;
use App\Http\Requests\Payment\PayRemainingPaymentRequest;
use App\Http\Requests\Payment\UpdateRemainingPaymentRequest;

class RemainingPaymentsController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected RemainingPaymentService $remaining_payment_service,
        protected PayRemainingPaymentService $pay_remaining_payment_service,
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', RemainingPayment::class);

        $remainingPayments = RemainingPayment::where('customer_id', $request->customer_id)
            ->orderBy('id', 'desc')
            ->paginate(15);

        return new RemainingPaymentCollection($remainingPayments);
    }

    public function store(StoreRemainingPaymentRequest $request)
    {
        try {
            $this->authorize('create', RemainingPayment::class);
            $data = $request->validated();
            $remaining_payment = $this->remaining_payment_service->createRemainingPaymentForAdditionals($data);
            return response([
                'message' => 'Pago restante creado exitosamente.',
                'data' => $remaining_payment
            ], 201);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al crear el pago restante.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function show(RemainingPayment $remaining_payment)
    {

        $this->authorize('view', $remaining_payment);
        return new RemainingPaymentResource($remaining_payment);
    }

    public function update(UpdateRemainingPaymentRequest $request, RemainingPayment $remaining_payment)
    {
        try {
            $data = $request->validated();
            $this->authorize('update', $remaining_payment);
            $remaining_payment = $this->remaining_payment_service->updateRemainingPayment($remaining_payment, $data);

            return response([
                'message' => 'Adeudo actualizado exitosamente.',
                'data' => new RemainingPaymentResource($remaining_payment->fresh())
            ], 200);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al actualizar el adeudo.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(RemainingPayment $remaining_payment)
    {

        try {
            $this->authorize('delete', $remaining_payment);
            $this->remaining_payment_service->cancelRemainingPayment($remaining_payment);
            return response(['message' => 'Adeudo cancelado exitosamente.']);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al cancelar el adeudo.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function payRemainingPayment(PayRemainingPaymentRequest $request)
    {
        try {
            $data = $request->validated();
            $remaining_payment = RemainingPayment::findOrFail($data['id']);
            $this->authorize('pay', $remaining_payment);
            $message = $this->pay_remaining_payment_service->payRemainingPayment($remaining_payment, $data);
            return response([
                'message' => $message,
                'data' => new RemainingPaymentResource($remaining_payment->fresh())
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al procesar el pago.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
