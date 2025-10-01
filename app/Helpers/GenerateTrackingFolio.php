<?php

namespace App\Helpers;

use App\Models\Notice\Notice;
use App\Models\Reports\Report;
use App\Models\Payments\Payment;
use App\Models\Catalogs\ReportCategory;
use App\Models\Agreements\PaymentAgreement;
use App\Models\Payments\RemainingPayment;
use App\Models\Payments\AdditionalPayment;
use App\Models\Payments\ExtraordinaryAccount;

class GenerateTrackingFolio
{
    public static function generateTrackingFolioReport(int|string $report_category_id): string
    {
        //Example: REP-AG-2505-0001
        $previousFolio = Report::orderBy('id', 'desc')->first()->tracking_folio ?? null;
        $current_year = date('y');
        $current_month = date('m');
        $current_period = $current_year . $current_month;

        $category = ReportCategory::find($report_category_id);
        $category_name_explode = explode(' ', $category->name);
        $prefix = '';

        if (count($category_name_explode) > 1) {
            foreach ($category_name_explode as $value) {
                $first_letter = substr($value, 0, 1);
                $prefix .= $first_letter;
            }
        } else {
            $prefix = substr($category_name_explode[0], 0, 2);
        }

        $prefix = strtoupper($prefix);

        $new_folio = null;
        $parts = explode('-', $previousFolio);

        if (empty($previousFolio) || $parts[2] !== $current_period) {
            $new_folio = 'REP-' . $prefix . '-' . $current_period . '-' . '0001';
        } else {
            $consecutive = (int) $parts[3] + 1;
            $consecutive_str = str_pad($consecutive, 4, '0', STR_PAD_LEFT);
            $new_folio = 'REP-' . $prefix . '-' . $current_period . '-' . $consecutive_str;
        }

        return $new_folio;
    }

    public static function generateContractFolio(string $id_user,): string
    {

        //Example: SOMA-CT-DG-123456
        if (empty($id_user)) {
            return 'Es necesario un ID de usuario';
        }

        return 'SOMA-CT-DG-' . $id_user;
    }

    public static function generatePaymentFolio(): string
    {

        //Example: 86957
        $last_payment = Payment::orderBy('folio', 'desc')->first()->folio ?? 0;
        $last_remaining_payment = RemainingPayment::orderBy('payment_folio', 'desc')->first()->payment_folio ?? 0;
        $last_additional_payment = AdditionalPayment::orderBy('payment_folio', 'desc')->first()->payment_folio ?? 0;

        $array_folios = [$last_payment, $last_remaining_payment, $last_additional_payment];
        $last_folio = max($array_folios);

        //Get the last page of the tables, if it does not exist, generate a new one
        if ((int) $last_folio === 0) $last_folio = 1;

        $int_last_folio = (int) $last_folio;
        $new_folio = $int_last_folio + 1;
        return (string) $new_folio;
    }

    public static function generateRemainingPaymentFolio(): string
    {
        //Example: RECLASIF-2504-0001
        $current_year = date('y');
        $current_month = date('m');
        $current_period = $current_year . $current_month;

        $previous_remaining_payment = RemainingPayment::orderBy('id', 'desc')->first() ?? null;
        $last_folio = $previous_remaining_payment->tracking_folio ?? null;

        $new_folio = null;
        if (empty($last_folio)) {
            $new_folio = 'RECLASIF-' . $current_period . '-0001';
        } else {
            $parts = explode('-', $last_folio);
            $consecutive = (int) $parts[3] + 1;
            $consecutive_str = str_pad($consecutive, 4, '0', STR_PAD_LEFT);
            $new_folio = 'RECLASIF-' . $current_period . '-' . $consecutive_str;
        }

        return $new_folio;
    }

    public static function generateExtraordinaryAccountFolio(): string
    {
        // Example: EXTRA-0001
        $last_extraordinary_account = ExtraordinaryAccount::orderBy('id', 'desc')->first() ?? null;
        $last_folio = $last_extraordinary_account->code ?? null;

        $new_folio = null;
        if (empty($last_folio)) {
            $new_folio = 'EXTRA-0001';
        } else {
            $parts = explode('-', $last_folio);
            $consecutive = (int) $parts[1] + 1;
            $consecutive_str = str_pad($consecutive, 4, '0', STR_PAD_LEFT);
            $new_folio = 'EXTRA-' . $consecutive_str;
        }

        return $new_folio;
    }

    public static function generateTrackingFolioNotice(): string
    {

        $year = date('Y');
        $month = date('m');
        $latest_folio = Notice::orderBy('id', 'desc')->first()->tracking_folio ?? null;

        $new_folio = null;

        if (!$latest_folio) {
            $new_folio = 'NOT/' . $year . '-' . $month . '-0001';
            return $new_folio;
        }

        $explode = explode('-', $latest_folio);
        $last_number = (int) $explode[2];
        $new_number = $last_number + 1;
        $new_folio = 'NOT/' . $year . '-' . $month . '-' . str_pad($new_number, 4, '0', STR_PAD_LEFT);
        return $new_folio;
    }

    public static function generateTrackingFolioAgreement(): string
    {

        $last_folio = PaymentAgreement::orderBy('id', 'desc')->first()->tracking_folio ?? null;
        $year = date('Y');
        $month = date('m');

        $new_folio = null;
        if (empty($last_folio)) {
            $new_folio = 'CONV-' . $year . '-' . $month . '-0001';
            return $new_folio;
        } else {
            $parts = explode('-', $last_folio);
            $consecutive = (int) $parts[3] + 1;
            $consecutive_str = str_pad($consecutive, 4, '0', STR_PAD_LEFT);
            $new_folio = 'CONV-' . $year . '-' . $month . '-' . $consecutive_str;
            return $new_folio;
        }

        return $new_folio;
    }
}
