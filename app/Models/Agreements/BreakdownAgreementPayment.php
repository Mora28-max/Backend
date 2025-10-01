<?php

namespace App\Models\Agreements;

use App\Models\Payments\Payment;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use App\Models\Agreements\PaymentAgreement;
use Spatie\Activitylog\Traits\LogsActivity;

class BreakdownAgreementPayment extends Model
{
    //
    use LogsActivity;

    protected $table = 'breakdown_agreement_payments';

    protected $fillable = [
        'agreement_id',
        'amount',
        'monthly_service_charge_ids',
        'payment_date',
        'payment_id',
        'non_compliance'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('BreakdownAgreementPayment')
            ->logFillable($this->fillable)
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function agreement()
    {
        return $this->belongsTo(PaymentAgreement::class, 'agreement_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
