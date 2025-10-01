<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Payments\AdditionalPayment;
use App\Services\Payment\AdditionalPaymentService;
use App\Http\Resources\Payment\AdditionalPaymentResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Resources\Payment\AdditionalPaymentCollection;
use App\Http\Requests\Payment\StoreAdditionalPaymentRequest;
use App\Http\Requests\Payment\UpdateAdditionalPaymentRequest;

class AdditionalPaymentsController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected AdditionalPaymentService $additional_payment_service) {}

    public function index()
    {
        $this->authorize('viewAny', AdditionalPayment::class);
        $additional_payments = AdditionalPayment::paginate(15);
        return new AdditionalPaymentCollection($additional_payments);
    }

    public function store(StoreAdditionalPaymentRequest $request)
    {
        try {
            $this->authorize('create', AdditionalPayment::class);
            $additionalPayment = $this->additional_payment_service->create($request->validated());
            return response([
                'message' => 'El pago adicional ha sido creado correctamente.',
                'data' => $additionalPayment,
            ], 201);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al crear el pago adicional.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function show(AdditionalPayment $additional_payment)
    {
        $this->authorize('view', $additional_payment);
        return response([
            'data' => new AdditionalPaymentResource($additional_payment)
        ]);
    }

    public function update(UpdateAdditionalPaymentRequest $request, AdditionalPayment $additional_payment)
    {
        try {
            $additional_payment = $this->additional_payment_service->update($additional_payment, $request->validated());
            return response([
                'message' => 'El pago adicional ha sido actualizado correctamente.',
                'data' => new AdditionalPaymentResource($additional_payment->fresh()),
            ], 200);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al actualizar el pago adicional.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy(AdditionalPayment $additional_payment)
    {
        try {
            $this->authorize('delete', $additional_payment);
            $this->additional_payment_service->cancel($additional_payment);
            return response(['message' => 'El pago adicional ha sido cancelado correctamente.']);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al cancelar el pago adicional.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
