<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\ProfileRequest;
use App\Models\User;
use App\Services\DestroyImageService;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function user(string $id): JsonResponse
    {
        try
        {
            $user = User::findOrFail($id);

            return response()->json([
                'user' => $user,
                'role' => $user->roles->first()->display_name
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

    public function update(ProfileRequest $request, string $id): JsonResponse
    {
        try
        {
            $user = User::findOrFail($id);

            if ($request->hasFile('image'))
            {
                (new ImageService)->updateImage($user, $request, 'app/public/profiles/', 'update');
            }

            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->phone = $request->phone;
            $user->email = $request->email;

            $user->save();

            return response()->json([
                'message' => 'Profile updated.',
                'user' => $user
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

    public function destroyPicture(string $id): JsonResponse
    {
        try
        {
            $user = User::findOrFail($id);

            if ($user->image)
            {
                (new DestroyImageService)->destroyImage($user,'app/public/profiles/');
            }

            return response()->json([
                'message' => 'Image removed.'
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
