<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\BrandRequest;
use App\Models\Brand;
use App\Services\DestroyImageService;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(): JsonResponse
    {
        try
        {
            $brands = Brand::orderBy('updated_at','desc')->paginate(10);
            $hide = $this->hideAttrs($brands);
            $count = count(Brand::all());

            return response()->json([
                'brands' => $hide,
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
            $data = Brand::search($request->keyword)->orderBy('updated_at','desc')->paginate(10);
            $hide = $this->hideAttrs($data);
            $count = $data->total();

            return response()->json([
                'brands' => $hide,
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
            $brands = Brand::all();
            $hide = $this->hideAttrs($brands);

            return response()->json([
                'brands' => $hide
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
            $brand = Brand::findOrFail($id);

            return response()->json([
                'brand' => $brand
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

    public function store(BrandRequest $request): JsonResponse
    {
        try
        {
            $brand = new Brand;

            $brand->name = $request->name;

            if ($request->hasFile('image'))
            {
                (new ImageService)->updateImage($brand, $request, 'app/public/brands/', 'store');
            }

            $brand->save();

            return response()->json([
                'message' => 'Brand added.',
                'brand' => $brand
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

    public function update(BrandRequest $request, string $id): JsonResponse
    {
        try
        {
            $brand = Brand::findOrFail($id);

            if ($request->hasFile('image'))
            {
                (new ImageService)->updateImage($brand, $request, 'app/public/brands/', 'update');
            }

            $brand->name = $request->name;

            $brand->save();

            return response()->json([
                'message' => 'Brand updated.',
                'brand' => $brand
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
            $brand = Brand::findOrFail($id);

            if ($brand->image)
            {
                (new DestroyImageService)->destroyImage($brand,'app/public/brands/');
            }

            $brand->delete();

            return response()->json([
                'message' => 'Brand deleted.'
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
