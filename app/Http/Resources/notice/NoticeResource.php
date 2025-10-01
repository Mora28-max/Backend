<?php

namespace App\Http\Resources\Notice;

use Illuminate\Http\Request;
use App\Traits\HasDefaultImage;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NoticeResource extends JsonResource
{
    use HasDefaultImage;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' =>  $this->customer->id,
            'customer_name' =>  $this->customer->fullName,
            'tracking_folio' => $this->tracking_folio,
            'months_behind' => (int) $this->months_behind,
            'amount' => (float) $this->amount,
            'period' => $this->period,
            'process_status' => $this->processStatus,
            'notice_type' => $this->noticeType,
            'comment' => $this->comment,
            'evidence' => $this->getImageUrl($this->evidence),
            'cost' => (float) $this->cost,
            'user_id' => $this->user->id,
            'user_name' => $this->user->fullName,
            'notification_date' => $this->notification_date,
            'period_ended' => $this->hasPeriodEnded(),
        ];
    }

    private function hasPeriodEnded(): bool
    {
        if (empty($this->notification_date))  return false;
        $now = Carbon::now();
        $period_finished = $now->diffInDays($this->notification_date, true) >= 1;
        $isFinished = $this->processStatus->name === 'Terminado' || $this->processStatus->name === 'Cancelado';
        return $period_finished && !$isFinished;
    }
}
