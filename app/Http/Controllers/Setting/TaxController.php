<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\TaxRequest;
use App\Models\Tax;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index(): JsonResponse
    {
        try
        {
            $taxes = Tax::orderBy('updated_at','desc')->paginate(10);
            $hide = $this->hideAttrs($taxes);
            $count = count(Tax::all());

            return response()->json([
                'taxes' => $hide,
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
            $data = Tax::search($request->keyword)->orderBy('updated_at','desc')->paginate(10);
            $hide = $this->hideAttrs($data);
            $count = $data->total();

            return response()->json([
                'taxes' => $hide,
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
            $taxes = Tax::where('status',1)->get();
            $hide = $this->hideAttrs($taxes);

            return response()->json([
                'taxes' => $hide
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
            $tax = Tax::findOrFail($id);

            return response()->json([
                'tax' => $tax
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

    public function store(TaxRequest $request): JsonResponse
    {
        try
        {
            $tax = Tax::create([
                'name' => $request->name,
                'rate' => $request->rate
            ]);

            return response()->json([
                'message' => 'Tax added.',
                'tax' => $tax
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

    public function update(TaxRequest $request, string $id): JsonResponse
    {
        try
        {
            $tax = Tax::findOrFail($id);

            $tax->update([
                'name' => $request->name,
                'rate' => $request->rate,
                'status' => $request->status
            ]);

            return response()->json([
                'message' => 'Tax updated.',
                'tax' => $tax
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
            $tax = Tax::findOrFail($id);
            $tax->delete();

            return response()->json([
                'message' => 'Tax deleted.'
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
