<?php

namespace App\Services\Customer;

use Carbon\Carbon;
use App\Models\Customers\Customer;
use Illuminate\Support\Facades\DB;
use App\Helpers\GenerateTrackingFolio;
use App\Helpers\UploadDataToCloudinary;
use App\Models\Readings\WaterMeterFolios;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\PasswordConfirmationHelper;
use App\Models\Payments\MonthlyServiceCharge;
use App\Validators\Customer\CustomerValidator;
use App\Services\Customer\CustomerChargeUpdatesService;
use App\Services\MonthlyAmountsMutation\AddMonthlyAmountService;
use App\Services\MonthlyAmountsMutation\UpdateMonthlyAmountService;
use App\Services\MonthlyAmountsMutation\OperationChangeAmountService;

class CustomerService
{
    public function __construct(
        protected AddMonthlyAmountService $add_monthly_amount_service,
        protected OperationChangeAmountService $operation_change_amount_service,
        protected UpdateMonthlyAmountService $update_monthly_amount_service,
        protected CustomerChargeUpdatesService $charge_service_updates
    ) {}

    public function getFilteredCustomers($search): Builder
    {
        return Customer::whereRaw('CONCAT(first_name, " ", last_name) LIKE?', ["%$search%"])
            ->orWhere('id', 'LIKE', "%$search%")
            ->orWhere('address', 'LIKE', "%$search%");
    }

    public function createCustomer(array $data): Customer
    {
        return DB::transaction(function () use ($data) {
            $customer = $this->createNewCustomer($data);
            CustomerValidator::validateClassificationAndUsage($data, $customer);
            $this->add_monthly_amount_service->createChargesForRemainingMonths($customer);
            if (!empty($data['meter'])) $this->createMeterInstallationDate(
                $customer,
                Carbon::parse($data['installation_date'])->format('Y-m-d H:i:s')
            );
            return $customer;
        });
    }

    public function updateCustomer(Customer $customer, array $data): Customer
    {
        CustomerValidator::ensureNotCancelled($customer);
        return DB::transaction(function () use ($customer, $data) {
            if (!empty($data['image']))
                $data['url_image'] = UploadDataToCloudinary::uploadImage($customer->id, $data['image'], 'customers');
            if (!empty($data['meter'])) {
                $customer->meter = $data['meter'];
                $this->createMeterInstallationDate(
                    $customer,
                    Carbon::parse($data['installation_date'])->format('Y-m-d H:i:s')
                );
                $this->updateReadingForNewMeter($customer);
            }
            $customer->update($data);
            CustomerValidator::validateClassificationAndUsage($data, $customer);
            $this->update_monthly_amount_service->updateCharges($customer);
            return $customer;
        });
    }

    public function cancelService(Customer $customer, array $data): void
    {
        PasswordConfirmationHelper::check($data['password_confirmation']);
        DB::transaction(function () use ($customer) {
            CustomerValidator::ensureNotAlreadyCancelled($customer);
            $customer->service_status_id = 3;
            $customer->save();
            $this->charge_service_updates->cancelCharges($customer);
        });
    }

    private function createNewCustomer(array $data): Customer
    {
        $customer = Customer::create($data);
        $customer->folio = GenerateTrackingFolio::generateContractFolio($customer->id);
        $customer->save();
        return $customer;
    }

    private function createMeterInstallationDate(Customer $customer, string $installation_date): void
    {
        WaterMeterFolios::create([
            'customer_id' => $customer->id,
            'folio_number' => $customer->meter,
            'installation_date' => $installation_date ?? now(),
        ]);
    }

    private function updateReadingForNewMeter(Customer $customer): void
    {
        $monthly_service_charge = MonthlyServiceCharge::where('customer_id', $customer->id)
            ->where('month', now()->month)
            ->where('year', now()->year)
            ->first();

        if (!$monthly_service_charge) throw new \Exception("No se encontró el cargo mensual para el usuario.");

        $data['new_reading'] = 0;
        $data['year'] = now()->year;
        $data['month'] = now()->month;

        $this->operation_change_amount_service->updateReadings($data, $monthly_service_charge, $customer->meter);
    }
}
