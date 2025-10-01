<?php

namespace App\Models\Payments;

use App\Models\User;
use Spatie\Activitylog\LogOptions;
use App\Models\Catalogs\PaymentType;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AdditionalPayment extends Model
{
    //
    use LogsActivity;

    protected $table = 'additional_payments';
    protected $fillable = [
        'code',
        'name',
        'address',
        'concept',
        'notes',
        'subtotal',
        'vat',
        'total',
        'payment_folio',
        'payment_type_id',
        'canceled',
        'user_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('AdditionalPayment')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
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
