<?php

namespace App\Models\Reports;

use App\Models\User;
use App\Models\Customers\Customer;
use Spatie\Activitylog\LogOptions;
use App\Models\Pivots\NoticeReport;
use App\Models\Catalogs\ProcessStatus;
use App\Models\Catalogs\ReportCategory;
use App\Models\Catalogs\ReportPriority;
use Illuminate\Database\Eloquent\Model;
use App\Models\Catalogs\ReportSubcategory;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\Customers\ClientBackupContact;
use App\Models\Catalogs\ReportChildSubcategory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    /** @use HasFactory<\Database\Factories\ReportFactory> */
    use HasFactory, LogsActivity;

    protected $fillable = [
        'customer_id',
        'client_backup_contacts_id',
        'user_id',
        'tracking_folio',
        'phone',
        'name',
        'address',
        'report_category_id',
        'report_subcategory_id',
        'report_child_subcategory_id',
        'report_priority_id',
        'process_status_id',
        'description',
        'breakdown',
        'supervision_user',
        'should_be_paid',
        'payment_folio',
        'images_for_pdf',
        'lat',
        'lng',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('Report')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function clientBackupContacts()
    {
        return $this->belongsTo(ClientBackupContact::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function reportCategory()
    {
        return $this->belongsTo(ReportCategory::class);
    }
    public function reportSubcategory()
    {
        return $this->belongsTo(ReportSubcategory::class);
    }
    public function reportPriority()
    {
        return $this->belongsTo(ReportPriority::class);
    }
    public function processStatus()
    {
        return $this->belongsTo(ProcessStatus::class);
    }
    public function reportChildSubcategory()
    {
        return $this->belongsTo(ReportChildSubcategory::class);
    }
    public function supervisionUser()
    {
        return $this->belongsTo(User::class, 'supervision_user');
    }
    public function noticeReports()
    {
        return $this->hasMany(NoticeReport::class);
    }

    public function scopeSearch($query, $search)
    {
        if (!$search) return $query;
        return $query->where(function ($qry) use ($search) {
            $qry->where('tracking_folio', 'like', "%{$search}%")
                ->orWhere('customer_id', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhereHas('customer', function ($qry2) use ($search) {
                    $qry2->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
        });
    }

    public function scopeCategory($query, $categories)
    {
        if (!$categories) return $query;
        return $query->whereIn('report_category_id', $categories);
    }

    public function scopeSubcategory($query, $subcategories)
    {
        if (!$subcategories) return $query;
        return $query->whereIn('report_subcategory_id', $subcategories);
    }

    public function scopeChildSubcategory($query, $childSubcategories)
    {
        if (!$childSubcategories) return $query;
        return $query->whereIn('report_child_subcategory_id', $childSubcategories);
    }

    public function scopePriority($query, $priorities)
    {
        if (!$priorities) return $query;
        return $query->whereIn('report_priority_id', $priorities);
    }

    public function scopeProcessStatus($query, $processStatuses)
    {
        if (!$processStatuses) return $query;
        return $query->whereIn('process_status_id', $processStatuses);
    }

    public function readableBePaid(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->should_be_paid ? "Obligatorio" : "No Obligatorio"
        );
    }
    public function readablePaymentFolio(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->should_be_paid ? $this->payment_folio : "No Aplica"
        );
    }
}
