<?php

namespace App\Models\Payments;

use App\Models\User;
use App\Models\Customers\Customer;
use Spatie\Activitylog\LogOptions;
use App\Models\Catalogs\PaymentType;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Payment extends Model
{
    //
    use LogsActivity;
    protected $table = 'payments';

    protected $fillable = [
        'customer_id',
        'user_id',
        'folio',
        'total',
        'payment_type_id',
        'payment_date',
        'notes',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Payment')
            ->logFillable($this->fillable)
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
