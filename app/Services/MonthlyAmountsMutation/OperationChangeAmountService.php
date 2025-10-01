<?php


namespace App\Services\MonthlyAmountsMutation;

use Carbon\Carbon;
use App\Helpers\SumNaturalAmounts;
use App\Models\Customers\Customer;
use Illuminate\Support\Facades\DB;
use App\Models\Catalogs\CustomerType;
use App\Models\Catalogs\DrainagePercentage;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Catalogs\MeasuredServiceRates;
use App\Models\Payments\MonthlyServiceCharge;

class OperationChangeAmountService
{

    private $vat_value = 0.16;
    private $surcharge_value = 0.0113;

    public function updateAmount(string $id, array $data, Collection $months_to_be_updated): void
    {
        switch ($data['operation_name']) {
            case 'SURCHARGE':
                $this->updateAmountWithSurcharge($months_to_be_updated);
                break;
            case 'DISCOUNT_ALL':
                $this->updateAmountWithDiscountAll($id, $data);
                break;
            case 'DISCOUNT_SURCHARGE':
                $this->updateAmountWithDiscountSurcharge($id, $data);
                break;
            case 'DISCOUNT_WATER':
                $this->updateAmountWithDiscountWater($id, $data);
                break;
            case 'DISCOUNT_DRAINAGE':
                $this->updateAmountWithDiscountDrainage($id, $data);
                break;
            case 'DISCOUNT_WATER_AND_DRAINAGE':
                $this->updateAmountWithDiscountWaterAndDrainage($id, $data);
                break;
            case 'FORGIVE_AMOUNTS':
                $this->forgiveAmounts($id, $data);
                break;
            case 'REVERSE_FORGIVENESS':
                $this->reverseForgiveness($id, $data);
                break;
            case 'RESET':
                $this->updateAmountWithNoOperation($data);
                break;
            default:
                $this->resetDiscounts($data['months_to_be_updated']);
                break;
        }
    }
    public function updateAmountWithSurcharge(Collection $months_to_be_updated): void
    {
        $months_before_today = $months_to_be_updated->filter(function ($item) {
            return ($item->year < date('Y')) ||
                ($item->year == date('Y') && $item->month < date('m'));
        });

        $total_months = count($months_before_today);

        $max = $total_months;
        foreach ($months_before_today as $month) {

            $new_water_surcharge = $month->water_amount * $this->surcharge_value * $max;
            $new_drainage_surcharge = $month->drainage_amount * $this->surcharge_value * $max;
            $new_excess_water_surcharge = $month->excess_water_amount * $this->surcharge_value * $max;
            $new_excess_drainage_surcharge = $month->excess_drainage_amount * $this->surcharge_value * $max;

            $month->overdue_months = $max;
            $month->water_surcharge = $new_water_surcharge;
            $month->drainage_surcharge = $new_drainage_surcharge;
            $month->excess_water_surcharge = $new_excess_water_surcharge;
            $month->excess_drainage_surcharge = $new_excess_drainage_surcharge;

            SumNaturalAmounts::SumValues($month, $this->vat_value);
            $max -= 1;
        }
    }
    public function updateAmountWithDiscountAll(string $id, array $data): void
    {
        $months_to_be_updated = collect($data['months_to_be_updated']);
        $percentage_discount = round((float)$data['percentage_discount'] / 100, 4);
        $this->resetDiscounts($data['months_to_be_updated']);
        foreach ($months_to_be_updated as $month) {
            $montly_to_be_updated = MonthlyServiceCharge::find($month);
            if ((string)$montly_to_be_updated->customer_id !== $id)
                throw new \Exception("Los meses seleccionados no pertenecen a la cuenta.");

            $customer = Customer::find($id);

            if (!$customer)
                throw new \Exception("La cuenta no existe.");

            $water_discount = (float)$montly_to_be_updated->water_amount * $percentage_discount;
            $drainage_discount = (float)$montly_to_be_updated->drainage_amount * $percentage_discount;
            $water_surcharge_discount = (float)$montly_to_be_updated->water_surcharge * $percentage_discount;
            $drainage_surcharge_discount = (float)$montly_to_be_updated->drainage_surcharge * $percentage_discount;
            $excess_water_discount = (float)$montly_to_be_updated->excess_water_amount * $percentage_discount;
            $excess_drainage_discount = (float)$montly_to_be_updated->excess_drainage_amount * $percentage_discount;
            $excess_water_surcharge_discount = (float)$montly_to_be_updated->excess_water_surcharge * $percentage_discount;
            $excess_drainage_surcharge_discount = (float)$montly_to_be_updated->excess_drainage_surcharge * $percentage_discount;

            $montly_to_be_updated->water_discount = $water_discount;
            $montly_to_be_updated->drainage_discount = $drainage_discount;
            $montly_to_be_updated->water_surcharge_discount = $water_surcharge_discount;
            $montly_to_be_updated->drainage_surcharge_discount = $drainage_surcharge_discount;
            $montly_to_be_updated->excess_water_discount = $excess_water_discount;
            $montly_to_be_updated->excess_drainage_discount = $excess_drainage_discount;
            $montly_to_be_updated->excess_water_surcharge_discount = $excess_water_surcharge_discount;
            $montly_to_be_updated->excess_drainage_surcharge_discount = $excess_drainage_surcharge_discount;

            SumNaturalAmounts::SumValues($montly_to_be_updated, $this->vat_value);
        }
    }
    public function updateAmountWithDiscountSurcharge(string $id, array $data): void
    {
        $months_to_be_updated = collect($data['months_to_be_updated']);
        $percentage_discount = round((float)$data['percentage_discount'] / 100, 4);
        $this->resetDiscounts($data['months_to_be_updated']);

        foreach ($months_to_be_updated as $month) {

            $montly_to_be_updated = MonthlyServiceCharge::find($month);

            if ((string)$montly_to_be_updated->customer_id !== $id)
                throw new \Exception("Los meses seleccionados no pertenecen a la cuenta.");

            $water_surcharge_discount = (float)$montly_to_be_updated->water_surcharge * $percentage_discount;
            $drainage_surcharge_discount = (float)$montly_to_be_updated->drainage_surcharge * $percentage_discount;
            $excess_water_surcharge_discount = (float)$montly_to_be_updated->excess_water_surcharge * $percentage_discount;
            $excess_drainage_surcharge_discount = (float)$montly_to_be_updated->excess_drainage_surcharge * $percentage_discount;

            $montly_to_be_updated->water_surcharge_discount = $water_surcharge_discount;
            $montly_to_be_updated->drainage_surcharge_discount = $drainage_surcharge_discount;
            $montly_to_be_updated->excess_water_surcharge_discount = $excess_water_surcharge_discount;
            $montly_to_be_updated->excess_drainage_surcharge_discount = $excess_drainage_surcharge_discount;

            SumNaturalAmounts::SumValues($montly_to_be_updated, $this->vat_value);
        }
    }
    public function updateAmountWithDiscountWater(string $id, array $data): void
    {
        $months_to_be_updated = collect($data['months_to_be_updated']);
        $percentage_discount = round((float)$data['percentage_discount'] / 100, 4);
        $this->resetDiscounts($data['months_to_be_updated']);

        foreach ($months_to_be_updated as $month) {

            $montly_to_be_updated = MonthlyServiceCharge::find($month);

            if ((string)$montly_to_be_updated->customer_id !== $id)
                throw new \Exception("Los meses seleccionados no pertenecen a la cuenta.");

            $customer = Customer::find($id);

            if (!$customer)
                throw new \Exception("La cuenta no existe.");

            $water_discount = (float)$montly_to_be_updated->water_amount * $percentage_discount;
            $water_excess_discount = (float)$montly_to_be_updated->excess_water_amount * $percentage_discount;

            $montly_to_be_updated->water_discount = $water_discount;
            $montly_to_be_updated->excess_water_discount = $water_excess_discount;

            SumNaturalAmounts::SumValues($montly_to_be_updated, $this->vat_value);
        }
    }
    public function updateAmountWithDiscountDrainage(string $id, array $data): void
    {
        $months_to_be_updated = collect($data['months_to_be_updated']);
        $percentage_discount = round((float)$data['percentage_discount'] / 100, 4);
        $this->resetDiscounts($data['months_to_be_updated']);

        foreach ($months_to_be_updated as $month) {

            $montly_to_be_updated = MonthlyServiceCharge::find($month);

            if ((string)$montly_to_be_updated->customer_id !== $id)
                throw new \Exception("Los meses seleccionados no pertenecen a la cuenta.");

            $customer = Customer::find($id);

            if (!$customer)
                throw new \Exception("La cuenta no existe.");

            $drainage_discount = (float)$montly_to_be_updated->drainage_amount * $percentage_discount;
            $drainage_excess_discount = (float)$montly_to_be_updated->excess_drainage_amount * $percentage_discount;
            $montly_to_be_updated->drainage_discount = $drainage_discount;
            $montly_to_be_updated->excess_drainage_discount = $drainage_excess_discount;

            SumNaturalAmounts::SumValues($montly_to_be_updated, $this->vat_value);
        }
    }
    public function updateAmountWithDiscountWaterAndDrainage(string $id, array $data): void
    {
        $months_to_be_updated = collect($data['months_to_be_updated']);
        $percentage_discount = round((float)$data['percentage_discount'] / 100, 4);
        $this->resetDiscounts($data['months_to_be_updated']);
        foreach ($months_to_be_updated as $month) {

            $montly_to_be_updated = MonthlyServiceCharge::find($month);

            if ((string)$montly_to_be_updated->customer_id !== $id)
                throw new \Exception("Los meses seleccionados no pertenecen a la cuenta.");

            $customer = Customer::find($id);

            if (!$customer)
                throw new \Exception("La cuenta no existe.");

            $water_discount = (float)$montly_to_be_updated->water_amount * $percentage_discount;
            $drainage_discount = (float)$montly_to_be_updated->drainage_amount * $percentage_discount;
            $excess_water_discount = (float)$montly_to_be_updated->excess_water_amount * $percentage_discount;
            $excess_drainage_discount = (float)$montly_to_be_updated->excess_drainage_amount * $percentage_discount;

            $montly_to_be_updated->water_discount = $water_discount;
            $montly_to_be_updated->drainage_discount = $drainage_discount;
            $montly_to_be_updated->excess_water_discount = $excess_water_discount;
            $montly_to_be_updated->excess_drainage_discount = $excess_drainage_discount;

            SumNaturalAmounts::SumValues($montly_to_be_updated, $this->vat_value);
        }
    }
    public function forgiveAmounts(string $id, array $data): void
    {

        $months_to_be_updated = collect($data['months_to_be_updated']);
        $this->resetDiscounts($data['months_to_be_updated']);

        foreach ($months_to_be_updated as $month) {
            $montly_to_be_updated = MonthlyServiceCharge::find($month);

            if ((string)$montly_to_be_updated->customer_id !== $id)
                throw new \Exception("Los meses seleccionados no pertenecen a la cuenta.");

            $montly_to_be_updated->monthly_payment_statement_id = '4';
            $montly_to_be_updated->save();
        }
    }
    public function reverseForgiveness(string $id, array $data): void
    {
        $months_to_be_updated = collect($data['months_to_be_updated']);
        $this->resetDiscounts($data['months_to_be_updated']);

        foreach ($months_to_be_updated as $month) {
            $montly_to_be_updated = MonthlyServiceCharge::find($month);

            if ((string)$montly_to_be_updated->customer_id !== $id)
                throw new \Exception("Los meses seleccionados no pertenecen a la cuenta.");

            $montly_to_be_updated->monthly_payment_statement_id = '2';
            $montly_to_be_updated->save();
        }
    }
    public function updateAmountWithNoOperation(array $data): void
    {
        $this->resetDiscounts($data['months_to_be_updated']);
    }
    private function resetDiscounts(array $months_to_be_updated): void
    {
        $months_to_reset = collect($months_to_be_updated);
        $months_to_reset->each(function ($month) {
            $month = MonthlyServiceCharge::find($month);
            if ($month->monthly_payment_statement_id !== '2' && $month->monthly_payment_statement_id !== '4') {
                $month_paid = Carbon::createFromDate(null, $month->month)->monthName;
                throw new \Exception("El mes $month_paid $month->year ya ha sido pagado, condonado, o la cuenta está cancelada.");
            }
        });
        foreach ($months_to_reset as $month) {
            $montly_to_be_updated = MonthlyServiceCharge::find($month);

            $montly_to_be_updated->water_discount = 0;
            $montly_to_be_updated->drainage_discount = 0;
            $montly_to_be_updated->water_surcharge_discount = 0;
            $montly_to_be_updated->drainage_surcharge_discount = 0;
            $montly_to_be_updated->excess_water_discount = 0;
            $montly_to_be_updated->excess_drainage_discount = 0;
            $montly_to_be_updated->excess_water_surcharge_discount = 0;
            $montly_to_be_updated->excess_drainage_surcharge_discount = 0;

            SumNaturalAmounts::SumValues($montly_to_be_updated, $this->vat_value);
        }
    }
    public function updateReadings(array $data, MonthlyServiceCharge $monthly_service_charge, ?string $meter = null): void
    {
        DB::transaction(function () use ($data, $monthly_service_charge, $meter) {
            $isPaid = !empty($monthly_service_charge->folio);

            if ($isPaid)
                throw new \Exception("No se puede editar la lectura de un mes pagado.");

            $customer = Customer::findOrFail($monthly_service_charge->customer_id);

            $use_of_type = $customer->use_of_type_id;
            $classification_type = $customer->classification_type_id;
            $drainage = (bool) $customer->drainage_use;
            $drainage_percentage = DrainagePercentage::where('year', (int) $data['year'])->first()->percentage;

            $service_rate = MeasuredServiceRates::where('use_of_type_id', $use_of_type)
                ->where('classification_type_id', $classification_type)
                ->where('year', $data['year'])
                ->first();

            $discount_percentage = CustomerType::where('id', $customer->customer_type_id)->first()->discount_percentage;

            if (!$service_rate)
                throw new \Exception("No se encontró el costo del servicio para el año $data[year].");

            $amount = $service_rate->cost_to_exceed;
            $upper_limit = $service_rate->upper_limit;

            $difference = !is_null($meter) ? 0 : $data['new_reading'] - $monthly_service_charge->old_reading; //--- requerido
            if ($difference < 0)
                throw new \Exception("La lectura no puede ser menor a la anterior.");

            $difference_with_limit = $difference - $upper_limit > 0 ? $difference - $upper_limit : 0;
            $excessive = $difference_with_limit > 0 ? $difference_with_limit : 0; // --- requerido
            $excess_water_amount = $excessive * $amount;
            $excess_drainage_amount = 0.00;
            if ($drainage) {
                $excess_drainage_amount = $excess_water_amount * $drainage_percentage;
            }

            $discount_water = $excess_water_amount * $discount_percentage;
            $discount_drainage = $excess_drainage_amount * $discount_percentage;

            $excess_water_surcharge = $excess_water_amount * $monthly_service_charge->overdue_months * $this->surcharge_value;
            $excess_drainage_surcharge = $excess_drainage_amount * $monthly_service_charge->overdue_months * $this->surcharge_value;

            $excess_water_surcharge_discount = $excess_water_surcharge * $discount_percentage;
            $excess_drainage_surcharge_discount = $excess_drainage_surcharge * $discount_percentage;

            $monthly_service_charge->new_reading = $data['new_reading'];
            $monthly_service_charge->difference = $difference;
            $monthly_service_charge->excessive = $excessive;
            $monthly_service_charge->excess_water_amount = $excess_water_amount;
            $monthly_service_charge->excess_drainage_amount = $excess_drainage_amount;
            $monthly_service_charge->excess_water_discount = $discount_water;
            $monthly_service_charge->excess_drainage_discount = $discount_drainage;
            $monthly_service_charge->excess_water_surcharge = $excess_water_surcharge;
            $monthly_service_charge->excess_drainage_surcharge = $excess_drainage_surcharge;
            $monthly_service_charge->excess_water_surcharge_discount = $excess_water_surcharge_discount;
            $monthly_service_charge->excess_drainage_surcharge_discount = $excess_drainage_surcharge_discount;

            SumNaturalAmounts::SumValues($monthly_service_charge, $this->vat_value);

            $next_month = (int) $data['month'] + 1 === 13 ? 1 : (int) $data['month'] + 1;
            $next_year = $next_month === 1 ? (int) $data['year'] + 1 : (int) $data['year'];

            $next_monthly_service_charge = MonthlyServiceCharge::where('customer_id', $customer->id)
                ->where('year', $next_year)
                ->where('month', $next_month)
                ->first();

            if ($next_monthly_service_charge) {
                $next_monthly_service_charge->old_reading = $data['new_reading'];
                $next_monthly_service_charge->save();
            };
        });
    }
}
