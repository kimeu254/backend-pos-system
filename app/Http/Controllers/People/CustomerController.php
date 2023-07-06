<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\Http\Requests\People\PeopleRequest;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(): JsonResponse
    {
        try
        {
            $customers = Customer::orderBy('updated_at','desc')->paginate(10);
            $hide = $this->hideAttrs($customers);
            $count = count(Customer::all());

            return response()->json([
                'customers' => $hide,
                'count' => $count
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'message' => 'Oops! Something went wrong.',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function search(Request $request): JsonResponse
    {
        try
        {
            $data = Customer::search($request->keyword)->orderBy('updated_at','desc')->paginate(10);
            $hide = $this->hideAttrs($data);
            $count = $data->total();

            return response()->json([
                'customers' => $hide,
                'count' => $count
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'message' => 'Oops! Something went wrong.',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function allData(): JsonResponse
    {
        try
        {
            $customers = Customer::all();
            $hide = $this->hideAttrs($customers);

            return response()->json([
                'customers' => $hide
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'message' => 'Oops! Something went wrong.',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function hideAttrs($model)
    {
        $model->makeHidden(['updated_at', 'created_at']);

        return $model;
    }

    public function show(string $id): JsonResponse
    {
        try
        {
            $customer = Customer::findOrFail($id);

            return response()->json([
                'customer' => $customer
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'message' => 'Oops! Something went wrong.',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function store(PeopleRequest $request): JsonResponse
    {
        try
        {
            $customer = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact,
                'country' => $request->country,
                'city' => $request->city,
                'address' => $request->address
            ]);

            return response()->json([
                'message' => 'Customer added.',
                'customer' => $customer
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'message' => 'Oops! Something went wrong.',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function update(PeopleRequest $request, string $id): JsonResponse
    {
        try
        {
            $customer = Customer::findOrFail($id);

            $customer->update([
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact,
                'country' => $request->country,
                'city' => $request->city,
                'address' => $request->address
            ]);

            return response()->json([
                'message' => 'Customer updated.',
                'customer' => $customer
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'message' => 'Oops! Something went wrong.',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try
        {
            $customer = Customer::findOrFail($id);
            $customer->delete();

            return response()->json([
                'message' => 'Customer deleted.'
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'message' => 'Oops! Something went wrong.',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
