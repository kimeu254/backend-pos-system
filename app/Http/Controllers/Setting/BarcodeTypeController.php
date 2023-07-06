<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\BarcodeTypeRequest;
use App\Models\BarcodeType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BarcodeTypeController extends Controller
{
    public function index(): JsonResponse
    {
        try
        {
            $types = BarcodeType::orderBy('updated_at','desc')->paginate(10);
            $hide = $this->hideAttrs($types);
            $count = count(BarcodeType::all());

            return response()->json([
                'barcode_types' => $hide,
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
            $data = BarcodeType::search($request->keyword)->orderBy('updated_at','desc')->paginate(10);
            $hide = $this->hideAttrs($data);
            $count = $data->total();

            return response()->json([
                'barcode_types' => $hide,
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
            $types = BarcodeType::where('status',1)->get();
            $hide = $this->hideAttrs($types);

            return response()->json([
                'barcode_types' => $hide
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
            $type = BarcodeType::findOrFail($id);

            return response()->json([
                'barcode_type' => $type
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

    public function store(BarcodeTypeRequest $request): JsonResponse
    {
        try
        {
            $type = BarcodeType::create([
                'name' => $request->name
            ]);

            return response()->json([
                'message' => 'Barcode symbol added.',
                'barcode_type' => $type
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

    public function update(BarcodeTypeRequest $request, string $id): JsonResponse
    {
        try
        {
            $type = BarcodeType::findOrFail($id);

            $type->update([
                'name' => $request->name,
                'status' => $request->status
            ]);

            return response()->json([
                'message' => 'Barcode symbol updated.',
                'barcode_type' => $type
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
            $type = BarcodeType::findOrFail($id);
            $type->delete();

            return response()->json([
                'message' => 'Barcode symbol deleted.'
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
