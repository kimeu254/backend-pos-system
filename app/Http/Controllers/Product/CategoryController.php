<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CategoryRequest;
use App\Models\Category;
use App\Services\DestroyImageService;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        try
        {
            $categories = Category::orderBy('updated_at','desc')->paginate(10);
            $hide = $this->hideAttrs($categories);
            $count = count(Category::all());

            return response()->json([
                'categories' => $hide,
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
            $data = Category::search($request->keyword)->orderBy('updated_at','desc')->paginate(10);
            $hide = $this->hideAttrs($data);
            $count = $data->total();

            return response()->json([
                'categories' => $hide,
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
            $categories = Category::all();
            $hide = $this->hideAttrs($categories);

            return response()->json([
                'categories' => $hide
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
            $category = Category::findOrFail($id);

            return response()->json([
                'category' => $category
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

    public function store(CategoryRequest $request): JsonResponse
    {
        try
        {
            $category = new Category;

            $category->name = $request->name;

            if ($request->hasFile('image'))
            {
                (new ImageService)->updateImage($category, $request, 'app/public/categories/', 'store');
            }

            $category->save();

            return response()->json([
                'message' => 'Category added.',
                'category' => $category
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

    public function update(CategoryRequest $request, string $id): JsonResponse
    {
        try
        {
            $category = Category::findOrFail($id);

            if ($request->hasFile('image'))
            {
                (new ImageService)->updateImage($category, $request, 'app/public/categories/', 'update');
            }

            $category->name = $request->name;

            $category->save();

            return response()->json([
                'message' => 'Category updated.',
                'category' => $category
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
            $category = Category::findOrFail($id);

            if ($category->image)
            {
                (new DestroyImageService)->destroyImage($category,'app/public/categories/');
            }

            $category->delete();

            return response()->json([
                'message' => 'Category deleted.'
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
