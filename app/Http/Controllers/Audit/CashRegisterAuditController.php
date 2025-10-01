<?php

namespace App\Http\Controllers\Audit;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Audit\CashRegisterAudit;
use App\Services\Audit\CashRegisterAuditService;
use App\Http\Resources\Audit\CashRegisterAuditResource;
use App\Http\Resources\Audit\CashRegisterAuditCollection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\Audit\StoreCashRegisterAuditRequest;

class CashRegisterAuditController extends Controller
{

    use AuthorizesRequests;

    public function __construct(protected CashRegisterAuditService $cash_register_audit_service) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', CashRegisterAudit::class);
        $cash_register_audits = CashRegisterAudit::latest()->paginate(15);
        return new CashRegisterAuditCollection($cash_register_audits);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCashRegisterAuditRequest $request)
    {
        try {
            $this->authorize('create', CashRegisterAudit::class);
            $this->cash_register_audit_service->createAudit($request->validated());
            return response([
                'message' => 'Arqueo creado exitosamente'
            ], 201);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al crear el arqueo',
                'error' => $th->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CashRegisterAudit $cash_register_audit)
    {
        $this->authorize('view', $cash_register_audit);
        return new CashRegisterAuditResource($cash_register_audit);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CashRegisterAudit $cash_register_audit)
    {
        $this->authorize('delete', $cash_register_audit);
        $cash_register_audit->delete();
        return response('Arqueo eliminado exitosamente');
    }

    public function getAmountByDateRange(Request $request)
    {
        $this->authorize('viewAny', CashRegisterAudit::class);
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $amounts = $this->cash_register_audit_service->getTotalAmountByDateRange($start_date, $end_date);
        return response(['data' => $amounts]);
    }
}
