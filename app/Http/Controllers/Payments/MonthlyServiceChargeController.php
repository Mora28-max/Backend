<?php

namespace App\Http\Controllers\Payments;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Customers\Customer;
use App\Http\Controllers\Controller;
use App\Models\Payments\MonthlyServiceCharge;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\Payment\StoreMonthlyServChargeRequest;
use App\Http\Requests\Payment\UpdateMonthlyServChargeRequest;
use App\Http\Resources\Payment\MonthlyServiceChargeCollection;
use App\Services\MonthlyServiceCharge\MonthlyServChargeService;
use App\Services\MonthlyAmountsMutation\AddMonthlyAmountService;
use App\Http\Resources\Payment\MonthlyServiceChargeByUserCollection;
use App\Http\Resources\Payment\MonthlyServiceChargeForgivenessCollection;

class MonthlyServiceChargeController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected AddMonthlyAmountService $add_monthly_amount_service,
        protected MonthlyServChargeService $monthly_serv_charge_service
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', MonthlyServiceCharge::class);
        $month = (int) date('m');
        $year = (int) date('Y');
        $data = MonthlyServiceCharge::summaryGroupByClient()
            ->where('monthly_service_charges.monthly_payment_statement_id', 2)
            ->where(function ($query) use ($month, $year) {
                $query->where('monthly_service_charges.year', '<', $year)
                    ->orWhere(function ($q) use ($year, $month) {
                        $q->where('monthly_service_charges.year', '=', $year)
                            ->where('monthly_service_charges.month', '<=', $month);
                    });
            })
            ->orderBy('total_general', 'desc')
            ->paginate(15);
        return new MonthlyServiceChargeCollection($data);
    }

    public function store(StoreMonthlyServChargeRequest $request)
    {
        try {
            $this->authorize('create', MonthlyServiceCharge::class);
            $this->monthly_serv_charge_service->storeMonthlyServCharge($request->validated());
            return response([
                'message' => 'El cargo se ha registrado correctamente.'
            ], 201);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al registrar el cobro.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function show(int $customer_id)
    {
        $customer = Customer::findOrFail($customer_id);
        if ($customer->in_agreement) {
            return response([
                'message' => 'El cliente tiene un convenio activo, no se pueden mostrar los cargos mensuales de servicio.'
            ], 403);
        }

        $monthly_payments = MonthlyServiceCharge::where('customer_id', $customer_id)
            ->where('monthly_payment_statement_id', 2)
            ->get();
        $model = $monthly_payments->first();
        $model ? $this->authorize('view', $model) : $this->authorize('viewAny', MonthlyServiceCharge::class);
        return new MonthlyServiceChargeByUserCollection($monthly_payments);
    }

    public function update(UpdateMonthlyServChargeRequest $request, int $customer_id)
    {
        try {
            $exists = Customer::where('id', $customer_id)->exists();
            if (!$exists) throw new \Exception('El usuario no existe.');

            $monthly_payments = MonthlyServiceCharge::where('customer_id', $customer_id)
                ->where('monthly_payment_statement_id', 2)
                ->get();

            $model = $monthly_payments->first();
            $model ? $this->authorize('view', $model) : $this->authorize('viewAny', MonthlyServiceCharge::class);
            $this->monthly_serv_charge_service->updateMonthlyServCharge(
                $request->validated(),
                $customer_id,
                $monthly_payments
            );
            return response([
                'message' => 'Los montos se han actualizado correctamente.'
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al actualizar los montos.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function showForgiven(int|string $id)
    {
        try {
            $this->authorize('viewAny', MonthlyServiceCharge::class);
            $monthly_payments = MonthlyServiceCharge::where('customer_id', $id)
                ->where('monthly_payment_statement_id', "4")
                ->paginate(15);

            if ($monthly_payments->isEmpty())
                throw new \Exception('No hay cobros condonados para este usuario.');

            return new MonthlyServiceChargeForgivenessCollection($monthly_payments);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al obtener los cobros condonados.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function filterMonthlyServiceCharges(Request $request)
    {
        $this->authorize('viewAny', User::class);
        $filters = $request->all() ?? [];
        $orderNum = $request->get('ordernum') ?? 'DESC';
        $orderBy = $request->get('orderby') ?? 'total_general';
        $actual_month = (int) date('m');
        $actual_year = (int) date('Y');

        $data = MonthlyServiceCharge::summaryGroupByClient($filters)
            ->where('monthly_service_charges.monthly_payment_statement_id', 2)
            ->where(function ($query) use ($actual_month, $actual_year) {
                $query->where('monthly_service_charges.year', '<', $actual_year)
                    ->orWhere(function ($q) use ($actual_year, $actual_month) {
                        $q->where('monthly_service_charges.year', '=', $actual_year)
                            ->where('monthly_service_charges.month', '<=', $actual_month);
                    });
            })
            ->orderBy($orderBy, $orderNum)
            ->paginate(15)
            ->appends($request->all());

        return new MonthlyServiceChargeCollection($data);
    }
}
