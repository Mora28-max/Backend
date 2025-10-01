<?php

namespace App\Http\Controllers\Customers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customers\ClientBackupContact;
use App\Services\Customer\ClientBackupContactService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Resources\Customer\ClientBackupContactResource;
use App\Http\Resources\Customer\ClientBackupContactCollection;
use App\Http\Requests\Customers\StoreClientBackupContactRequest;
use App\Http\Requests\Customers\UpdateClientBackupContactRequest;

class ClientBackupContactController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected ClientBackupContactService $client_backup_contact_service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', ClientBackupContact::class);
        $beneficiaries = $this->client_backup_contact_service->getAllByCustomerId($request->customer_id);
        return new ClientBackupContactCollection($beneficiaries);
    }


    public function store(StoreClientBackupContactRequest $request)
    {
        try {
            $this->authorize('create', ClientBackupContact::class);
            $beneficiary = $this->client_backup_contact_service->create($request->validated());
            $beneficiary->fresh();
            return response([
                'message' => 'Beneficiario creado correctamente.',
                'data' => new ClientBackupContactResource($beneficiary),
            ], 201);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al crear el beneficiario.',
                'error' => $th->getMessage()
            ], 500);
        }
    }


    public function show(ClientBackupContact $beneficiary)
    {
        $this->authorize('view', $beneficiary);
        return response([
            'data' => new ClientBackupContactResource($beneficiary)
        ], 200);
    }


    public function update(UpdateClientBackupContactRequest $request, ClientBackupContact $beneficiary)
    {
        try {
            $this->authorize('update', $beneficiary);
            $beneficiary = $this->client_backup_contact_service->update($beneficiary, $request->validated());
            $beneficiary = $beneficiary->fresh();
            return response([
                'status' => 'Beneficiario actualizado correctamente.',
                'data' => new ClientBackupContactResource($beneficiary),
            ], 200);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al actualizar el beneficiario.',
                'error' => $th->getMessage()
            ], 500);
        }
    }


    public function destroy(ClientBackupContact $beneficiary)
    {
        try {
            $this->authorize('delete', $beneficiary);
            $this->client_backup_contact_service->delete($beneficiary);
            return response([
                'message' => 'Beneficiario eliminado correctamente.',
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al eliminar el beneficiario.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
