<?php

namespace App\Services\Agreement;

use Carbon\Carbon;
use App\Models\Reports\Report;
use App\Models\Payments\Payment;
use App\Models\Customers\Customer;
use Illuminate\Support\Facades\DB;
use App\Models\Catalogs\ServiceType;
use App\Helpers\GenerateTrackingFolio;
use App\Models\Catalogs\ServiceStatus;
use App\Services\Payment\PaymentService;
use App\Models\Agreements\PaymentAgreement;
use App\Models\Payments\MonthlyServiceCharge;
use App\Validators\Agreement\AgreementValidator;
use App\Http\Resources\Agreement\AgreementResource;
use App\Models\Agreements\BreakdownAgreementPayment;
use App\Http\Resources\Agreement\BreakdownAgreementPaymentCollection;
use App\Models\Catalogs\ProcessStatus;
use App\Services\MonthlyAmountsMutation\OperationChangeAmountService;

class PaymentAgreementService
{
    public function __construct(
        private OperationChangeAmountService $operation_change_amount_service,
        private PaymentService $payment_service
    ) {}

    public function generateAgreement(array $data): array
    {
        $customer = Customer::findOrFail($data['customer_id']);
        $service_status = ServiceStatus::findOrFail($customer['service_status_id'])->name;
        $service_type = ServiceType::findOrFail($customer['service_type_id'])->name;
        AgreementValidator::validateCustomerNotAccountCanceled($customer, $service_status);
        AgreementValidator::validateCustomerNotAccountSuspended($customer, $service_status);
        AgreementValidator::validateCustomerNotInAgreement($customer);
        AgreementValidator::validateCustomerNotInNotice($customer);
        AgreementValidator::validateMonthsToPayAreNotPayment($data['id_months_to_pay'] ?? []);

        return DB::transaction(function () use ($data, $customer, $service_type) {
            $data['tracking_folio'] = GenerateTrackingFolio::generateTrackingFolioAgreement();
            $data['process_status_id'] = 2;

            $amount_to_pay = !empty($data['id_months_to_pay'])
                ? array_map(fn($id) => MonthlyServiceCharge::findOrFail($id)->total_amount, $data['id_months_to_pay'])
                : [];

            $data['initial_payment_amount'] = array_sum($amount_to_pay);

            $blocks = $this->createBlock($data['payment_breakdown']);
            $array_ids = array_merge(...array_map(fn($block) => $block->id, $blocks));

            $data['total_debt'] = $this->getTotalDebt($array_ids, $service_type);

            $agreement = PaymentAgreement::create($data);

            if (!empty($data["id_months_to_pay"])) {
                $payment = $this->savePayment($data, $customer->id);
            };

            $this->saveBreakdownAgreementPayment($blocks, $agreement->id);

            $customer->in_agreement = true;
            $customer->save();

            return [
                'agreement' => $agreement,
                'payment' => $payment ?? null
            ];
        });
    }

    public function listPaymentDates(int $customer_id): array
    {
        $payment_agreement = PaymentAgreement::where('customer_id', $customer_id)
            ->where('process_status_id', 2)
            ->first();

        if (empty($payment_agreement))
            throw new \Exception("No se encontró el convenio de pago.");

        $breakdown_agreement_payments = BreakdownAgreementPayment::where('agreement_id', $payment_agreement->id)
            ->whereNull('payment_id')
            ->get();

        return [
            'payment_agreement' => new AgreementResource($payment_agreement),
            'breakdown_agreement_payments' => new BreakdownAgreementPaymentCollection($breakdown_agreement_payments)
        ];
    }

    public function payAgreement(int $breakdown_agreement_payment_id): void
    {
        $breakdown_agreement = BreakdownAgreementPayment::findOrFail($breakdown_agreement_payment_id);
        $agreement = PaymentAgreement::findOrFail($breakdown_agreement->agreement_id)->first();
        AgreementValidator::isDelayed($breakdown_agreement->payment_date, $breakdown_agreement->payment_id);
        $id_months_to_pay = json_decode($breakdown_agreement->monthly_service_charge_ids);
        AgreementValidator::isPaid($id_months_to_pay);

        $data = [
            'id_months_to_pay' => $id_months_to_pay,
            'customer_id' => $agreement->customer_id,
            'payment_type_id' => 1,
            'notes' => "Pago por convenio con el folio {$agreement->tracking_folio}, generado el " . Carbon::now()->format('d/m/Y')
        ];

        $payment = $this->payment_service->createPayment($data);

        DB::transaction(function () use ($breakdown_agreement, $payment, $agreement) {
            $breakdown_agreement->payment_id = $payment->id;
            $breakdown_agreement->save();

            $breakdown_agreements_pendient = BreakdownAgreementPayment::where('agreement_id', $breakdown_agreement->agreement_id)
                ->whereNull('payment_id')
                ->count();

            if ($breakdown_agreements_pendient === 0) {
                $agreement->process_status_id = 5; // Convenio liquidado
                $agreement->save();

                $customer = Customer::findOrFail($agreement->customer_id);
                $customer->in_agreement = false;
                $customer->save();
            }
        });
    }

    public function createReportForNonCompliantAgreements(PaymentAgreement $payment_agreement): Report
    {
        $process_status = ProcessStatus::findOrFail($payment_agreement->process_status_id);
        AgreementValidator::validateStatus($process_status->name);

        $data = [
            'customer_id' => $payment_agreement->customer_id,
            'report_category_id' => 1,
            'report_subcategory_id' => 6,
            'report_child_subcategory_id' => 19,
            'report_priority_id' => 1,
            'description' => "Reporte de incumplimiento de convenio {$payment_agreement->tracking_folio}"
        ];

        return Report::create($data);
    }

    public function cancelAgreement(PaymentAgreement $payment_agreement): void
    {
        DB::transaction(function () use ($payment_agreement) {
            $payment_agreement->process_status_id = 4;
            $payment_agreement->save();

            $customer = Customer::findOrFail($payment_agreement->customer_id);
            $customer->in_agreement = false;
            $customer->save();

            $breakdown_agreements_pendient = BreakdownAgreementPayment::where('agreement_id', $payment_agreement->id)
                ->whereNull('payment_id')
                ->get();

            foreach ($breakdown_agreements_pendient as $breakdown_agreement) {
                $breakdown_agreement->non_compliance = true;
                $breakdown_agreement->save();
            }
        });
    }

    private function createBlock(string $payment_breakdown): array
    {
        $payment_breakdown_object = json_decode($payment_breakdown);
        $payment_breakdown_array = (array) $payment_breakdown_object;
        AgreementValidator::validatePaymentBreakdown($payment_breakdown_array);
        return $payment_breakdown_array;
    }

    private function savePayment(array $data, int $customer_id): Payment
    {
        $payment = $this->payment_service->createPayment([
            'customer_id' => $customer_id,
            'id_months_to_pay' => $data['id_months_to_pay'],
            'payment_type_id' => $data['payment_type_id'],
            'notes' => "Primer pago por convenio con el folio {$data['tracking_folio']}, generado el " . Carbon::now()->format('d/m/Y')
        ]);
        if (!$payment) throw new \Exception("Errores al crear el pago. Revisa los datos del pago.");
        return $payment;
    }

    private function saveBreakdownAgreementPayment(array $blocks, int $agreement_id): void
    {
        foreach ($blocks as $block) {
            $amounts = array_map(fn($id) => MonthlyServiceCharge::findOrFail($id)->total_amount, $block->id);
            $amount = array_sum($amounts);
            $breakdown_agreement = [
                'agreement_id' => $agreement_id,
                'amount' => $amount,
                'monthly_service_charge_ids' => json_encode($block->id),
                'payment_date' => Carbon::parse($block->payment_day)->format('Y-m-d H:i:s'),
            ];
            BreakdownAgreementPayment::create($breakdown_agreement);
        }
    }

    private function getTotalDebt(array $array_ids, string $service_type): float
    {
        return array_sum(array_map(function ($id) use ($service_type) {
            $monthly = MonthlyServiceCharge::findOrFail($id);
            AgreementValidator::serviceTypeAndReadingValidation($service_type, $monthly->new_reading);
            return $monthly->total_amount;
        }, $array_ids));
    }
}
