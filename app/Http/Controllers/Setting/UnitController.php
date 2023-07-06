<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\UnitRequest;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(): JsonResponse
    {
        try
        {
            $units = Unit::orderBy('updated_at','desc')->paginate(10);
            $hide = $this->hideAttrs($units);
            $count = count(Unit::all());

            return response()->json([
                'units' => $hide,
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
            $data = Unit::search($request->keyword)->orderBy('updated_at','desc')->paginate(10);
            $hide = $this->hideAttrs($data);
            $count = $data->total();

            return response()->json([
                'units' => $hide,
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
            $units = Unit::all();
            $hide = $this->hideAttrs($units);

            return response()->json([
                'units' => $hide
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
            $unit = Unit::findOrFail($id);

            return response()->json([
                'unit' => $unit
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

    public function store(UnitRequest $request): JsonResponse
    {
        try
        {
            $unit = Unit::create([
                'name' => $request->name,
                'value' => $request->value,
                'short_name' => $request->short_name
            ]);

            return response()->json([
                'message' => 'Unit added.',
                'unit' => $unit
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

    public function update(UnitRequest $request, string $id): JsonResponse
    {
        try
        {
            $unit = Unit::findOrFail($id);

            $unit->update([
                'name' => $request->name,
                'value' => $request->value,
                'short_name' => $request->short_name
            ]);

            return response()->json([
                'message' => 'Unit updated.',
                'unit' => $unit
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
            $unit = Unit::findOrFail($id);
            $unit->delete();

            return response()->json([
                'message' => 'Unit deleted.'
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
