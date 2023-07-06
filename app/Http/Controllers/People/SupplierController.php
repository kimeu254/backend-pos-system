<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\Http\Requests\People\PeopleRequest;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(): JsonResponse
    {
        try
        {
            $suppliers = Supplier::orderBy('updated_at','desc')->paginate(10);
            $hide = $this->hideAttrs($suppliers);
            $count = count(Supplier::all());

            return response()->json([
                'suppliers' => $hide,
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
            $data = Supplier::search($request->keyword)->orderBy('updated_at','desc')->paginate(10);
            $hide = $this->hideAttrs($data);
            $count = $data->total();

            return response()->json([
                'suppliers' => $hide,
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
            $suppliers = Supplier::all();
            $hide = $this->hideAttrs($suppliers);

            return response()->json([
                'suppliers' => $hide
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
            $supplier = Supplier::findOrFail($id);

            return response()->json([
                'supplier' => $supplier
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
            $supplier = Supplier::create([
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact,
                'country' => $request->country,
                'city' => $request->city,
                'address' => $request->address
            ]);

            return response()->json([
                'message' => 'Supplier added.',
                'supplier' => $supplier
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
            $supplier = Supplier::findOrFail($id);

            $supplier->update([
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact,
                'country' => $request->country,
                'city' => $request->city,
                'address' => $request->address
            ]);

            return response()->json([
                'message' => 'Supplier updated.',
                'supplier' => $supplier
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
            $supplier = Supplier::findOrFail($id);
            $supplier->delete();

            return response()->json([
                'message' => 'Supplier deleted.'
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
