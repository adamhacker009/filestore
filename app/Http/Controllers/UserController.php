<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\FilesUser;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\ApiValidateException;
use Illuminate\Support\Str;
use App\Http\Requests\RegistrationRequest;

class UserController extends Controller
{
    public function authorization(Request $request): array
    {
        $user = User::where(['email' => $request->email])->first();

        if ($user and Hash::check($request->password, $user->password)) {
            return [
                'success' => true,
                'message' => 'Success',
                'token' => $user->createToken(Str::random(5))->plainTextToken
            ];
        }
        throw new ApiValidateException(401, false, 'Login failed');
    }

    public function registration(RegistrationRequest $request): array
    {
        $user = User::create($request->except('password')+['password'=>Hash::make($request->password)]);
        $token = $user->createToken(Str::random(5))->plainTextToken;
        return [
            'success' => true,
            'message' => 'Success',
            'token' => $token
        ];
    }
    public function logout(Request $request): array
    {
        $request->user()->currentAccessToken()->delete();
        return [
            'success' => true,
            'message' => 'Logout'
        ];
    }

}
