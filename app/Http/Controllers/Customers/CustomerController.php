<?php

namespace App\Http\Controllers\Customers;

use Illuminate\Http\Request;
use App\Models\Customers\Customer;
use App\DTOs\Customer\CustomerData;
use App\Http\Controllers\Controller;
use App\Traits\HasCustomerRelations;
use App\Services\Customer\CustomerService;
use App\Http\Resources\Customer\CustomerResource;
use App\Http\Resources\Customer\CustomerCollection;
use App\Http\Requests\Customers\StoreCustomerRequest;
use App\Http\Requests\Customers\DeleteCustomerRequest;
use App\Http\Requests\Customers\UpdateCustomerRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CustomerController extends Controller
{

    use AuthorizesRequests, HasCustomerRelations;

    public function __construct(protected CustomerService $customer_service) {}

    public function index(Request $request)
    {
        $search = $request->search;
        $this->authorize('viewAny', Customer::class);
        $customersQuery = $this->customer_service->getFilteredCustomers($search);
        $customers = $customersQuery->with($this->customerRelations())
            ->paginate(25)
            ->withQueryString();
        return new CustomerCollection($customers);
    }

    public function store(StoreCustomerRequest $request)
    {
        try {
            $this->authorize('create', Customer::class);
            $data = CustomerData::fromArray($request->validated());
            $customer = $this->customer_service->createCustomer($data->toArray());
            $customer->load($this->customerRelations());
            return response([
                'message' => 'El cliente ha sido creado correctamente.',
                'data' => new CustomerResource($customer)
            ], 201);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al crear el usuario.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function show(Customer $customer)
    {
        $customer->load($this->customerRelations());
        $this->authorize('view', $customer);
        return new CustomerResource($customer);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        try {
            $this->authorize('update', $customer);
            $customer_updated = $this->customer_service->updateCustomer($customer, $request->validated());
            $customer = $customer_updated->load($this->customerRelations());
            return response([
                'message' => 'El cliente ha sido actualizado correctamente.',
                'data' => new CustomerResource($customer)
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al actualizar el usuario.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Customer $customer, DeleteCustomerRequest $request)
    {
        try {
            $this->authorize('delete', $customer);
            $this->customer_service->cancelService($customer, $request->validated());
            return response([
                'message' => 'El cliente ha sido cancelado correctamente.'
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al cancelar el usuario.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
