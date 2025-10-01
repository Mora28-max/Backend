<?php

namespace App\Http\Resources\Report;

use Illuminate\Http\Request;
use App\Helpers\UploadDataToCloudinary;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isCollection = $request->route() && ($request->route()->getActionMethod() === 'index' || $request->route()->getActionMethod() === 'filterReports');
        $isRegistered = $this->customer->id ?? false;

        $image_urls = [];
        if (!$isCollection && $this->images_for_pdf) {
            $images = explode(',', $this->images_for_pdf);
            foreach ($images as $image) {
                $quotye_removes = str_replace('"', '', $image);
                $brackets_left_removes = str_replace('[', '', $quotye_removes);
                $brackets_right_removes = str_replace(']', '', $brackets_left_removes);
                if (str_contains($brackets_right_removes, 'soapamz-')) {
                    $image_urls[] = UploadDataToCloudinary::getSignedAuthenticatedUrl($brackets_right_removes, 'image');
                } else {
                    $prefix = explode('/', $brackets_right_removes);
                    $image_urls[] = UploadDataToCloudinary::getFirstPublicImageUrlByPrefix($prefix[0], $prefix[1]);
                }
            }
        }

        $supervision_name =  $this->supervision_user ? $this->supervisionUser->firstname . ' ' . $this->supervisionUser->lastname : null;

        return [
            'id' => $this->id,
            'tracking_folio' => $this->tracking_folio,
            'customer_id' => $this->customer->id ?? null,
            'attended_name' => $isRegistered ? $this->customer->fullName : $this->name,
            'client_backup_contacts_id' => $this->client_backup_contacts_id,
            'user_id' => $this->user_id,
            'phone' => $this->phone,
            'address' => $isRegistered ? $this->customer->address : $this->address,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'user' => $this->user->fullName,
            'supervision_name' => $supervision_name,
            'images_for_pdf' => $image_urls,
            'report_category' => $this->reportCategory,
            'report_subcategory' => $this->reportSubcategory,
            'report_child_subcategory' => $this->reportChildSubcategory,
            'report_priority' => $this->reportPriority,
            'process_status' => $this->processStatus,
            'description' => $this->description,
            'should_be_paid' => $this->readable_be_paid,
            'payment_folio' => $this->readable_payment_folio,
            'created_at' => $this->created_at,
        ];
    }
}
