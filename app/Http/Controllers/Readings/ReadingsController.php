<?php

namespace App\Http\Controllers\Readings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Payments\MonthlyServiceCharge;
use App\Http\Requests\Reading\UpdateReadingRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Resources\Customer\MeasuredServCustomerResource;
use App\Http\Resources\Customer\MeasuredServCustomerCollection;
use App\Services\MonthlyAmountsMutation\OperationChangeAmountService;

class ReadingsController extends Controller
{

    use AuthorizesRequests;

    public function __construct(protected OperationChangeAmountService $operation_change_amount_service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', MonthlyServiceCharge::class);
        $pendingReadings = MonthlyServiceCharge::pendingReadingsByClient($request->all())
            ->paginate(15)
            ->appends($request->all());
        return new MeasuredServCustomerCollection($pendingReadings);
    }

    public function show(int $customer_id, Request $request)
    {
        $this->authorize('viewAny', MonthlyServiceCharge::class);
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');

        $reading = MonthlyServiceCharge::where('customer_id', $customer_id)
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        return new MeasuredServCustomerResource($reading);
    }

    public function update(UpdateReadingRequest $request, int $monthly_service_charge_id)
    {
        try {
            $monthly_service_charge = MonthlyServiceCharge::findOrFail($monthly_service_charge_id);
            $this->authorize('update', $monthly_service_charge);
            $this->operation_change_amount_service->updateReadings($request->validated(), $monthly_service_charge);
            return response(['message' => 'Lectura actualizada correctamente.']);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al actualizar la lectura.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function filterMonthlyServiceCharges(Request $request)
    {
        $this->authorize('viewAny', MonthlyServiceCharge::class);
        $pendingReadings = MonthlyServiceCharge::pendingReadingsByClient($request->all())
            ->paginate(15);
        return new MeasuredServCustomerCollection($pendingReadings);
    }
}
