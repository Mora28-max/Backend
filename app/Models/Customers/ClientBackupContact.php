<?php

namespace App\Models\Customers;

use App\Models\Reports\Report;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientBackupContact extends Model
{
    /** @use HasFactory<\Database\Factories\ClientBackupContactsFactory> */
    use HasFactory, LogsActivity;

    protected $table = 'client_backup_contacts';

    protected $fillable = [
        'customer_id',
        'phone',
        'email',
        'firstname',
        'lastname',
        'kind',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('ClientBackupContacts')
            ->logFillable($this->fillable)
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => "{$this->firstname} {$this->lastname}"
        );
    }
}
