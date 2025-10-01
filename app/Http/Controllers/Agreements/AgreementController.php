<?php

namespace App\Http\Controllers\Agreements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Agreements\PaymentAgreement;
use App\Http\Resources\Agreement\AgreementResource;
use App\Services\Agreement\PaymentAgreementService;
use App\Http\Resources\Agreement\AgreementCollection;
use App\Http\Requests\Agreement\StoreAgreementRequest;
use App\Http\Resources\Agreement\BreakdownAgreementPaymentCollection;
use App\Models\Agreements\BreakdownAgreementPayment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AgreementController extends Controller
{

    use AuthorizesRequests;

    public function __construct(protected PaymentAgreementService $service) {}

    public function index()
    {
        $this->authorize('viewAny', PaymentAgreement::class);
        $agreements = PaymentAgreement::orderBy('created_at', 'desc')->paginate(15);
        return new AgreementCollection($agreements);
    }

    public function store(StoreAgreementRequest $request)
    {
        try {
            $this->authorize('create', PaymentAgreement::class);
            $agreement = $this->service->generateAgreement($request->validated());
            return response([
                'message' => 'Convenio creado exitosamente.',
                'data' => $agreement
            ], 201);
        } catch (\Throwable $th) {
            return response([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function show(PaymentAgreement $payment_agreement)
    {
        $this->authorize('view', $payment_agreement);
        return response([
            'data' => new AgreementResource($payment_agreement)
        ]);
    }

    public function destroy(PaymentAgreement $payment_agreement)
    {
        try {
            $this->authorize('delete', $payment_agreement);
            $this->service->cancelAgreement($payment_agreement);
            return response([
                'message' => 'Convenio eliminado exitosamente.'
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al eliminar el convenio.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function listPaymentDates(int $customer_id)
    {
        try {
            $this->authorize('viewAny', PaymentAgreement::class);
            $data = $this->service->listPaymentDates($customer_id);
            return response([
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al listar las fechas de pago.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function payAgreementPartial(int $breakdown_agreement_payment_id)
    {
        try {
            $this->authorize('payAgreement', PaymentAgreement::class);
            $this->service->payAgreement($breakdown_agreement_payment_id);
            return response([
                'message' => 'Pago realizado exitosamente.',
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al realizar el pago del convenio.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function createReportForNonCompliantAgreements(PaymentAgreement $payment_agreement)
    {
        try {
            $this->authorize('generateReport', PaymentAgreement::class);
            $report = $this->service->createReportForNonCompliantAgreements($payment_agreement);
            return response([
                'data' => $report
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al generar el reporte.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function filterByDate(Request $request)
    {
        $month = $request->month ?? now()->format('m');
        $year = $request->year ?? now()->format('Y');

        $this->authorize('viewAny', PaymentAgreement::class);
        $breakdown_agreements = BreakdownAgreementPayment::whereMonth('payment_date', $month)
            ->whereYear('payment_date', $year)
            ->get();
        return new BreakdownAgreementPaymentCollection($breakdown_agreements);
    }
}
