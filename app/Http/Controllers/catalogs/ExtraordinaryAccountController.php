<?php

namespace App\Http\Controllers\Catalogs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Payments\ExtraordinaryAccount;
use App\Services\Catalogs\ExtraordinaryAccountService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Resources\Catalogs\ExtraordinaryAccountCollection;
use App\Http\Requests\Catalogs\StoreExtraordinaryAccountRequest;

class ExtraordinaryAccountController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected ExtraordinaryAccountService $extraordinary_account_service) {}

    public function index(Request $request)
    {
        try {
            $this->authorize('viewAny', ExtraordinaryAccount::class);
            $extraordinary_accounts = $this->extraordinary_account_service->search($request);
            return new ExtraordinaryAccountCollection($extraordinary_accounts);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al obtener las cuentas extraordinarias',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function store(StoreExtraordinaryAccountRequest $request)
    {
        try {
            $this->authorize('create', ExtraordinaryAccount::class);
            $extraordinary_account = $this->extraordinary_account_service->create($request->validated());
            return response([
                'message' => 'Cuenta extraordinaria creada con éxito',
                'extraordinary_account' => $extraordinary_account,
            ], 201);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al crear la cuenta extraordinaria',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(ExtraordinaryAccount $extraordinary_account)
    {
        try {
            $this->authorize('delete', $extraordinary_account);
            $this->extraordinary_account_service->delete($extraordinary_account);
            return response(['message' => 'El recurso se eliminó con éxito']);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al eliminar la cuenta extraordinaria',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
