<?php

namespace App\Models\Payments;

use App\Models\Customers\Customer;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\Payments\MonthlyPaymentStatement;

class MonthlyServiceCharge extends Model
{
    //
    use LogsActivity;

    protected $table = 'monthly_service_charges';
    protected $fillable = [
        'customer_id',
        'has_drainage',
        'year',
        'month',
        'water_amount',
        'drainage_amount',
        'overdue_months',
        'water_surcharge',
        'drainage_surcharge',
        'water_discount',
        'drainage_discount',
        'water_amount_subtotal',
        'drainage_amount_subtotal',
        'vat',
        'total_amount',
        'folio',
        'monthly_payment_statement_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('MonthlyServiceCharge')
            ->logFillable($this->fillable)
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function monthlyPaymentStatement()
    {
        return $this->belongsTo(MonthlyPaymentStatement::class);
    }

    public function scopeSummaryGroupByClient(Builder $query, array $filters = []): Builder
    {
        $query
            ->join('customers', 'customers.id', '=', 'monthly_service_charges.customer_id')
            ->join('zones', 'zones.id', '=', 'customers.zone_id')
            ->join('colonies', 'colonies.id', '=', 'zones.colony_id')
            ->join('use_of_types', 'use_of_types.id', '=', 'customers.use_of_type_id')
            ->join('classification_types', 'classification_types.id', '=', 'customers.classification_type_id')
            ->join('service_status', 'service_status.id', '=', 'customers.service_status_id')
            ->join('additional_observations', 'additional_observations.id', '=', 'customers.additional_observation_id');

        $search = $filters['search'] ?? null;
        $zones = $filters['zones'] ?? null;
        $use_of_types = $filters['use'] ?? null;
        $classification_types = $filters['clasifs'] ?? null;
        $colonies = $filters['colonies'] ?? null;

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('customers.first_name', 'like', "%$search%")
                    ->orWhere('customers.last_name', 'like', "%$search%")
                    ->orWhere('customers.address', 'like', "%$search%");
            });
        }

        $query->where('customers.service_status_id', 1)
            ->where('customers.is_notified', false)
            ->where('customers.in_agreement', false);

        if (!empty($zones)) {
            $query->whereIn('customers.zone_id', $zones);
        }
        if (!empty($use_of_types)) {
            $query->whereIn('use_of_types.id', $use_of_types);
        }
        if (!empty($classification_types)) {
            $query->whereIn('classification_types.id', $classification_types);
        }
        if (!empty($colonies)) {
            $query->whereIn('zones.colony_id', $colonies);
        }

        $query->where('service_status.id', 1);

        $max_overdue_month = DB::table('monthly_service_charges')
            ->where('total_amount', '>', 0)
            ->max('overdue_months') ?? 1;

        $month_between_1 = $filters['month_between_1'] ?? 1;
        $month_between_2 = $filters['month_between_2'] ?? $max_overdue_month;


        $query->where(function ($q) {
            $currentMonth = (int) now()->format('m');
            $currentYear = (int) now()->format('Y');
            $q->where('monthly_service_charges.year', '<', $currentYear)
                ->orWhere(function ($q2) use ($currentMonth, $currentYear) {
                    $q2->where('monthly_service_charges.year', $currentYear)
                        ->where('monthly_service_charges.month', '<', $currentMonth);
                });
        });

        return $query
            ->selectRaw('
                monthly_service_charges.customer_id,
                customers.first_name,
                customers.last_name,
                customers.address,
                customers.int_num,
                customers.ext_num,
                zones.name AS zona,
                colonies.name AS colonia,
                use_of_types.name AS uso,
                classification_types.name AS clasificacion,
                additional_observations.code AS observacion,
                SUM(water_amount_subtotal) AS subtotal_water,
                SUM(drainage_amount_subtotal) AS subtotal_drainage,
                SUM(vat) AS subtotal_vat,
                SUM(total_amount) AS total_general,
                MAX(overdue_months) AS max_overdue_months
            ')
            ->groupBy([
                'monthly_service_charges.customer_id',
                'customers.first_name',
                'customers.last_name',
                'customers.address',
                'customers.int_num',
                'customers.ext_num',
                'zones.name',
                'colonies.name',
                'use_of_types.name',
                'classification_types.name',
                'additional_observations.code'
            ])
            ->havingRaw('SUM(total_amount) > ? AND MAX(overdue_months) BETWEEN ? AND ?', [0, $month_between_1, $month_between_2]);
    }

    public function scopePendingReadingsByClient(Builder $query, ?array $filters = []): Builder
    {
        $query
            ->join('customers as c', 'monthly_service_charges.customer_id', '=', 'c.id')
            ->join('zones as z', 'c.zone_id', '=', 'z.id')
            ->join('colonies as cl', 'z.colony_id', '=', 'cl.id');

        $customer_id = $filters['customer_id'] ?? null;
        $search = $filters['search'] ?? null;
        $zones = $filters['zones'] ?? null;
        $useOfTypes = $filters['use'] ?? null;
        $classificationTypes = $filters['clasifs'] ?? null;
        $colonies = $filters['colonies'] ?? null;

        $actualMonth = (int) now()->format('m');
        $month = $actualMonth - 1 === 0 ? 12 : $actualMonth - 1;

        $actualYear = (int) now()->format('Y');
        $year = $actualMonth - 1 === 0 ? $actualYear - 1 : $actualYear;

        if (!$customer_id) {
            $query->where('c.service_status_id', 1)
                ->where('c.service_type_id', 2)
                ->whereNull('monthly_service_charges.new_reading');
        }

        $query->whereNotExists(function ($sub) {
            $sub->select(DB::raw(1))
                ->from('meter_reading_schedule as mrs')
                ->whereColumn('mrs.montly_service_charge_id', 'monthly_service_charges.id');
        });

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('c.first_name', 'like', "%$search%")
                    ->orWhere('c.last_name', 'like', "%$search%")
                    ->orWhere('c.address', 'like', "%$search%");
            });
        }
        if ($customer_id) {
            $query->where(function ($query) use ($customer_id) {
                $query->where('c.id', $customer_id);
            });
        }

        if ($month !== null && !$customer_id) {
            $query->where('monthly_service_charges.month', $month);
        }

        if ($year !== null && !$customer_id) {
            $query->where('monthly_service_charges.year', $year);
        }

        if (!empty($zones)) {
            $query->whereIn('c.zone_id', $zones);
        }

        if (!empty($useOfTypes)) {
            $query->whereIn('c.use_of_type_id', $useOfTypes);
        }

        if (!empty($classificationTypes)) {
            $query->whereIn('c.classification_type_id', $classificationTypes);
        }

        if (!empty($colonies)) {
            $query->whereIn('z.colony_id', $colonies);
        }


        return $query
            ->with(['customer.zone.colony'])
            ->select(
                'monthly_service_charges.id',
                'monthly_service_charges.customer_id',
                'monthly_service_charges.old_reading',
                'monthly_service_charges.new_reading',
                'monthly_service_charges.month',
                'monthly_service_charges.year'
            );
    }
}
