<?php

namespace App\Models\Payments;

use App\Models\Customers\Customer;
use Spatie\Activitylog\LogOptions;
use App\Models\Catalogs\PaymentType;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\Payments\MonthlyPaymentStatement;

class RemainingPayment extends Model
{
    //
    use LogsActivity;
    protected $table = 'remaining_payments';

    protected $fillable = [
        'customer_id',
        'tracking_folio',
        'subtotal',
        'discount',
        'vat',
        'breakdown',
        'total',
        'payment_folio',
        'monthly_payment_statement_id',
        'payment_type_id',
        'payment_date',
        'canceled',
        'note',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('RemainingPayments')
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
    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }
}
