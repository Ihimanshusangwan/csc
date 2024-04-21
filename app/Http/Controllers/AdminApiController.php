<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\UserAuthentication;
use Illuminate\Support\Facades\DB;
class AdminApiController extends Controller
{
    public function loginAndCreateToken(Request $request)
    {
        $email = trim($request->input('username'));
        $password = trim($request->input('password'));
        $role = trim($request->input('role'));

        $authResult = UserAuthentication::login($email, $password ,$role);

        if ($authResult['success']) {
            return response()
                ->json([
                    'success' => true,
                    'message' => $authResult['message'],
                    'token' =>  $authResult['token']
                ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => $authResult['message']
            ], 200);
        }
    }
    public function checkAuth(Request $request)
    {
      return  UserAuthentication::authenticateUser($request);
    }
}
