<?php

namespace App\Http\Controllers\PDF;

use App\Helpers\FormatDate;
use App\Helpers\FormQuantity;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Audit\CashRegisterAudit;
use App\Http\Resources\Audit\CashRegisterAuditResource;
use App\Models\Catalogs\PaymentType;

class CashRegisterController extends Controller
{
    public function downloadAuditReport(string|int $id)
    {
        if (!is_numeric($id)) return response(['message' => 'Invalid ID'], 400);
        $auditData = CashRegisterAudit::find($id);
        if (!$auditData) return response(['message' => 'Audit not found'], 404);

        $auditData = new CashRegisterAuditResource($auditData);

        $audit = $auditData->toArray(request());
        $audit['created_at'] = FormatDate::fullDateTime($audit['created_at']);
        $audit['cash'] = FormQuantity::formatCurrency($audit['system_cash']);
        $audit['digital'] = FormQuantity::formatCurrency($audit['digital_total']);
        $audit['total'] = FormQuantity::formatCurrency($audit['digital_total'] + $audit['system_cash']);
        $audit['counted_cash'] = FormQuantity::formatCurrency($audit['counted_cash']);

        $json_details = json_decode($audit['details'], true);

        foreach ($json_details as &$detail) {
            $key = array_key_first($detail);
            $formatted_key = FormQuantity::formatCurrency($key);
            $value = $detail[$key];
            $detail = [
                $formatted_key => $value,
                'total' => FormQuantity::formatCurrency($key * $value)
            ];
        }
        unset($detail);

        $payment_types = PaymentType::pluck('name', 'id')->toArray();
        $json_digital_details = json_decode($audit['digital_details'], true);

        $digital_details_formatted = [];
        foreach ($json_digital_details as $key => $total) {
            $type_name =  $payment_types[$key] ?? "Tipo $key";
            $digital_details_formatted[] = [
                'type' => $type_name,
                'total' => FormQuantity::formatCurrency($total)
            ];
        }

        $pdf = Pdf::loadView('pdf.cash-register-audit', [
            'audit' => $audit,
            'json_details' => $json_details,
            'digital_details' => $digital_details_formatted
        ]);
        return $pdf->stream("auditoria_corte_caja_{$id}.pdf");
    }
}
