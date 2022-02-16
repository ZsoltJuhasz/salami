<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;


class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required",
            "password" => "required",
            "confirm_password" => "required|same:password",
        ]);

        if ($validator->fails()) {
            return $this->sendError("Hitelesítési hiba", $validator->errors(), 400);
        }
        $input = $request->all();
        $input["password"] = bcrypt($input["password"]);
        $user = User::create($input);
        $success["name"] = $user->name;
        return $this->sendResponse($success, "Sikeres regisztrálás");
    }
    public function login(Request $request)   
    {
        if (Auth::attempt(["name" => $request->name, "password" => $request->password])) {
            $authUser = Auth::user();
            $success["token"] = $authUser->createToken("adoptme")->plainTextToken;
            $success["name"] = $authUser->name;
            return $this->sendResponse($success, "Sikeresen bejelentkezett");
        } else {
            return $this->sendError("Sikertelen bejelentkezés", ["error" => "Hibás adatok"], 400);
        }
    }
    public function logout(Request $request)
    {
        auth("sanctum")->user()->currentAccessToken()->delete();
        return response()->json('Sikeresen kijelentkezett');
    }
}
