<?php

namespace App\DTOs\Report;

use Illuminate\Support\Facades\Auth;

class ReportData
{
    public ?string $customer_id;
    public ?string $client_backup_contacts_id;
    public string $user_id;
    public ?string $tracking_folio;
    public string $phone;
    public string $name;
    public string $address;
    public ?string $report_category_id;
    public ?string $report_subcategory_id;
    public ?string $report_child_subcategory_id;
    public ?int $report_priority_id;
    public ?int $process_status_id;
    public string $description;
    public string $breakdown;
    public bool $should_be_paid;
    public ?string $payment_folio;
    public string $images_for_pdf;

    public function __construct(array $data)
    {
        $this->customer_id = $data['customer_id'] ?? null;
        $this->client_backup_contacts_id = $data['client_backup_contacts_id'] ?? null;
        $this->user_id = $data['user_id'] ?? Auth::id();
        $this->tracking_folio = $data['tracking_folio'] ?? null;
        $this->phone = $data['phone'] ?? "";
        $this->name = $data['name'] ?? "";
        $this->address = $data['address'] ?? "";
        $this->report_category_id = $data['report_category_id'] ?? null;
        $this->report_subcategory_id = $data['report_subcategory_id'] ?? null;
        $this->report_child_subcategory_id = $data['report_child_subcategory_id'] ?? null;
        $this->report_priority_id = $data['report_priority_id'] ?? 2;
        $this->process_status_id = $data['process_status_id'] ?? 1;
        $this->description = $data['description'] ?? "";
        $this->breakdown = $data['breakdown'] ?? "";
        $this->should_be_paid = $data['should_be_paid'] ?? false;
        $this->payment_folio = $data['payment_folio'] ?? null;
        $this->images_for_pdf = $data['images_for_pdf'] ?? "";
    }

    public function toArray(): array
    {
        return [
            'customer_id' => $this->customer_id,
            'client_backup_contacts_id' => $this->client_backup_contacts_id,
            'user_id' => $this->user_id,
            'tracking_folio' => $this->tracking_folio,
            'phone' => $this->phone,
            'name' => $this->name,
            'address' => $this->address,
            'report_category_id' => $this->report_category_id,
            'report_subcategory_id' => $this->report_subcategory_id,
            'report_child_subcategory_id' => $this->report_child_subcategory_id,
            'report_priority_id' => $this->report_priority_id,
            'process_status_id' => $this->process_status_id,
            'description' => $this->description,
            'breakdown' => $this->breakdown,
            'should_be_paid' => $this->should_be_paid,
            'payment_folio' => $this->payment_folio,
            'images_for_pdf' => $this->images_for_pdf,
        ];
    }
}
