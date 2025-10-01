<?php

namespace App\Models\Agreements;

use App\Models\Customers\Customer;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\Agreements\BreakdownAgreementPayment;

class PaymentAgreement extends Model
{
    //
    use LogsActivity;
    protected $table = 'payment_agreements';

    protected $fillable = [
        'tracking_folio',
        'customer_id',
        'process_status_id',
        'total_debt',
        'initial_payment_amount',
        'payment_breakdown',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('PaymentAgreement')
            ->logFillable($this->fillable)
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function breakdownAgreementPayments()
    {
        return $this->hasMany(BreakdownAgreementPayment::class, 'agreement_id');
    }
}
