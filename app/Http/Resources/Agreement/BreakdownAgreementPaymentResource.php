<?php

namespace App\Http\Resources\Agreement;

use App\Helpers\FormatDate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BreakdownAgreementPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $paymentDate = Carbon::parse($this->payment_date)->startOfDay();
        $today = Carbon::now()->startOfDay();

        $is_next_day = $today->equalTo($paymentDate->copy()->addDay());
        $is_delayed = $today->greaterThan($paymentDate) && empty($this->payment);

        return [
            'id' => $this->id,
            'agreement' => $this->agreement->tracking_folio,
            'payment_day' => FormatDate::fullDateTime($this->payment_date),
            'is_next_day' => $is_next_day,
            'payment' => $this->payment->folio ?? null,
            'is_delayed' => $is_delayed,
            'amount_to_pay' => $this->amount,
        ];
    }
}
