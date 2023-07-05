<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\AccountRequest;
use App\Models\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function changePassword(AccountRequest $request, string $id): Application|Response|JsonResponse|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        try
        {
            $user = User::findOrFail($id);

            #Match The Old Password
            if(!Hash::check($request->old_password, $user->password))
            {
                return response()->json([
                    "message" => "Old Password Doesn't match!"
                ], 401);
            }

            #New Password Same The Old Password
            if (strcmp($request->get('old_password'), $request->new_password) == 0)
            {
                return response()->json([
                    "message" => "New Password cannot be same as your Current Password!"
                ], 401);
            }

            #Update the new Password
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json([
                "message" => "Password changed."
            ]);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'Oops! Something went wrong.',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
