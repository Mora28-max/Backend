<?php

namespace App\Models\Customers;

use App\Models\Notice\Notice;
use App\Models\Locations\Zone;
use App\Models\Reports\Report;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Catalogs\UseOfType;
use Spatie\Activitylog\LogOptions;
use App\Models\Catalogs\ServiceType;
use App\Models\Catalogs\CustomerType;
use App\Models\Catalogs\ServiceStatus;
use Illuminate\Database\Eloquent\Model;
use App\Models\Readings\WaterMeterFolios;
use App\Models\Catalogs\ClassificationType;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\Catalogs\AdditionalObservation;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Customer extends Model
{
    //
    use LogsActivity, HasApiTokens;


    protected $fillable = [
        'folio',
        'first_name',
        'last_name',
        'phone',
        'email',
        'rfc',
        'voter_key',
        'type_person',
        'address',
        'int_num',
        'ext_num',
        'zone_id',
        'geolocation',
        'reference',
        'url_image',
        'customer_type_id',
        'use_of_type_id',
        'service_type_id',
        'service_status_id',
        'classification_type_id',
        'drainage_use',
        'additional_observation_id',
        'meter',
        'storage_capacity',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Customer')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
    public function customerType()
    {
        return $this->belongsTo(CustomerType::class);
    }
    public function useOfType()
    {
        return $this->belongsTo(UseOfType::class);
    }
    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }
    public function classificationType()
    {
        return $this->belongsTo(ClassificationType::class);
    }
    public function serviceStatus()
    {
        return $this->belongsTo(ServiceStatus::class);
    }
    public function additionalObservation()
    {
        return $this->belongsTo(AdditionalObservation::class);
    }
    public function clientBackupContacts()
    {
        return $this->hasMany(ClientBackupContact::class);
    }
    public function notices()
    {
        return $this->hasMany(Notice::class);
    }
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function waterMeterFolios()
    {
        return $this->hasMany(WaterMeterFolios::class);
    }

    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => "{$this->first_name} {$this->last_name}"
        );
    }

    public function readableTypePerson(): Attribute
    {
        return Attribute::make(
            get: fn() => (string) $this->type_person === '1' ? 'Persona Física' : 'Persona Moral'
        );
    }
}
