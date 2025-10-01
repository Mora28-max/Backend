<?php

namespace App\Services\Catalogs;

use Illuminate\Http\Request;
use App\Helpers\GenerateTrackingFolio;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Payments\ExtraordinaryAccount;
use Illuminate\Pagination\LengthAwarePaginator;


class ExtraordinaryAccountService
{
    public function create(array $data): ExtraordinaryAccount
    {
        $code = GenerateTrackingFolio::generateExtraordinaryAccountFolio();
        $data['code'] = $code;
        return ExtraordinaryAccount::create($data);
    }

    public function delete(ExtraordinaryAccount $extraordinary_account)
    {
        $extraordinary_account->delete();
    }

    public function search(Request $request): LengthAwarePaginator | Collection
    {
        if (empty($request->get('search')))   return ExtraordinaryAccount::paginate(15);

        $length = strlen($request->get('search'));
        if ($length >= 5)
            return ExtraordinaryAccount::where('name', 'like', '%' . $request->get('search') . '%')->get();

        throw new \Exception('El término de búsqueda debe tener al menos 5 caracteres');
    }
}
