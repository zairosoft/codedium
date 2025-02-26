<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class ApiController extends Controller
{
    // https://www.cloudways.com/blog/rest-api-laravel-passport-authentication/
    public function accessToken(Request $request)
    {
        $validate = $this->validations($request, "login");
        if ($validate["error"]) {
            return $this->prepareResult(false, [], $validate['errors'], "Error while validating user");
        }
        $user = User::where("email", $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                return responseResult([
                    "accessToken" => $user->createToken('Backoffice')->accessToken,
                    "user" => $user
                ], [], "User Verified", 200);
            } else {
                return responseResult([], ["password" => "Wrong passowrd"], "Password not matched", 422);
            }
        } else {
            return responseResult([], ["password" => "Unable to find user"], "User not found", 422);
        }
    }

    public function validations($request, $type)
    {
        $errors = [];
        $error = false;
        if ($type == "login") {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:255',
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                $error = true;
                $errors = $validator->errors();
            }
        }
        return ["error" => $error, "errors" => $errors];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function prepareResult($status, $data, $errors, $msg, $code = 200)
    {
        return response()->json(['status' => $status, 'data' => $data, 'message' => $msg, 'code' => $code, 'errors' => $errors], $code);
    }
}
