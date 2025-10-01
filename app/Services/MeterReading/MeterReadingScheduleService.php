<?php

namespace App\Services\MeterReading;

use App\Models\Customers\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Helpers\UploadDataToCloudinary;
use App\Models\Payments\MonthlyServiceCharge;
use App\Models\Readings\MeterReadingSchedule;
use App\Services\MonthlyAmountsMutation\OperationChangeAmountService;

class MeterReadingScheduleService
{
    public function __construct(protected OperationChangeAmountService $operation_change_amount_service) {}

    public function storeMeterReadScheduleRequest(array $data): MeterReadingSchedule
    {

        $exists = MeterReadingSchedule::where('montly_service_charge_id', $data['montly_service_charge_id'])->exists();
        if ($exists) throw new \Exception('Ya existe en agenda.');

        $exist_msc = MonthlyServiceCharge::findOrFail($data['montly_service_charge_id']);
        if (!empty($exist_msc->new_reading)) throw new \Exception('Ya existe una lectura registrada para el mes seleccionado.');

        $customer_has_measured_serv = Customer::find($data['customer_id']);
        if (!$customer_has_measured_serv) throw new \Exception('El usuario debe tener servicio medido.');

        $data['user_id'] = Auth::user()->id;
        unset($data['customer_id']);
        return MeterReadingSchedule::create($data);
    }

    public function updateReadingSchedule(array $data, MeterReadingSchedule $meterReadingSchedule): MeterReadingSchedule
    {
        return DB::transaction(function () use ($data, $meterReadingSchedule) {
            $meterReadingSchedule->meter_reading = $data['meter_reading'];
            $name = 'LECT-'
                . $meterReadingSchedule->montlyServiceCharge->customer_id
                . '-'
                . $meterReadingSchedule->montlyServiceCharge->year
                . '-'
                . $meterReadingSchedule->montlyServiceCharge->month;

            if (!empty($data['evidence'])) {
                $url_evidence = UploadDataToCloudinary::uploadImage($name, $data['evidence'], 'readings');
            }

            $meterReadingSchedule->evidence = $url_evidence ?? null;
            $meterReadingSchedule->save();

            $data['year'] = $meterReadingSchedule->montlyServiceCharge->year;
            $data['month'] = $meterReadingSchedule->montlyServiceCharge->month;
            $data['new_reading'] = $meterReadingSchedule->meter_reading;

            $this->operation_change_amount_service->updateReadings($data, $meterReadingSchedule->montlyServiceCharge);

            $meterReadingSchedule->process_status_id = 5;
            $meterReadingSchedule->save();
            return $meterReadingSchedule->fresh();
        });
    }
}
