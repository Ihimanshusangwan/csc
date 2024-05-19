<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use function PHPUnit\Framework\returnSelf;

class UserAuthentication
{
    public static function login($username = null, $password = null, $role = null)
    {
        switch ($role) {
            case "admin":
                $user =  DB::table('admins')->where('email', $username)->first();
                break;
            case "agent":
                $user =  DB::table('agents')->where('username', $username)->first();
                break;
            case "staff":
                $user =  DB::table('staff')->where('username', $username)->first();
                break;
            case "customer":
                $user =  DB::table('customers')->where('mobile', $username)->first();
                break;
            default:
                $user = null;
        }

        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found'
            ];
        }
        if ($role === "admin") {
            $passwordCheck = password_verify($password, $user->password);
        }
        else if ($role === "customer") {
            $validPassword = ($user->password === null)? $user->mobile : $user->password;
            $passwordCheck = ($validPassword === $password);
        } else {
            $passwordCheck = $password === $user->password;
        }
        if ($passwordCheck) {
            $db_token = Str::random(40);
            $payload = [
                'user_id' => $user->id,
                'iat' => time(),
                'exp' => time() + 432000,
                'db_token' => $db_token,
                'role' => $role
            ];
            $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
            switch ($role) {
                case "admin":
                    DB::table('admins')
                        ->where('id', $user->id)
                        ->update([
                            'token' => $db_token,
                            'token_updated_at' => now(),
                        ]);
                    break;
                case "agent":
                    DB::table('agents')
                        ->where('id', $user->id)
                        ->update([
                            'token' => $db_token,
                            'token_updated_at' => now(),
                        ]);
                    break;
                case "staff":
                    DB::table('staff')
                        ->where('id', $user->id)
                        ->update([
                            'token' => $db_token,
                            'token_updated_at' => now(),
                        ]);
                    break;
                case "customer":
                    DB::table('customers')
                        ->where('id', $user->id)
                        ->update([
                            'token' => $db_token,
                            'token_updated_at' => now(),
                        ]);
                    break;
                default:
                    break;
            }
            return [
                'success' => true,
                'message' => 'Login successful',
                'token' => $token
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Incorrect password'
            ];
        }
    }
    private static function authenticate_and_get_user_id_and_role($token): ?array
    {
        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            $user_id = $decoded->user_id ?? null;
            $role = $decoded->role ?? null;
            if ($user_id && $role) {
                switch ($role) {
                    case "admin":
                        $user =  DB::table('admins')->where('id', $user_id)->first();
                        break;
                    case "agent":
                        $user =  DB::table('agents')->where('id', $user_id)->first();
                        break;
                    case "staff":
                        $user =  DB::table('staff')->where('id', $user_id)->first();
                        break;
                    case "customer":
                        $user =  DB::table('customers')->where('id', $user_id)->first();
                        break;
                    default:
                        $user = null;
                }
                if ($user && $user->token === $decoded->db_token) {
                    return ["user_id" => $user_id, "role" => $role];
                }
            }
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }
    public static function authenticateUser(Request $request): array
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return [
                'success' => false,
                'message' => "Authorization header is missing"
            ];
        }

        if (!preg_match('/^Bearer\s+(.*?)$/', $token, $matches)) {
            return [
                'success' => false,
                'message' => "Invalid token format"
            ];
        }

        $token = $matches[1];
        $user = UserAuthentication::authenticate_and_get_user_id_and_role($token);

        if ($user !== null) {
            return [
                'success' => true,
                'message' => "Authentication Successful",
                'user' => $user
            ];
        }

        return [
            'success' => false,
            'message' => "Authentication Failed"
        ];
    }
    public static function logout(Request $request)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return [
                'success' => false,
                'message' => "Authorization header is missing"
            ];
        }

        if (!preg_match('/^Bearer\s+(.*?)$/', $token, $matches)) {
            return [
                'success' => false,
                'message' => "Invalid token format"
            ];
        }

        $token = $matches[1];
        $user = UserAuthentication::authenticate_and_get_user_id_and_role($token);
        if ($user) {
            switch ($user['role']) {
                case "admin":
                    DB::table('admins')
                        ->where('id', $user['user_id'])
                        ->update([
                            'token' => null,
                            'token_updated_at' => now(),
                        ]);
                    break;
                case "agent":
                    DB::table('agents')
                        ->where('id', $user['user_id'])
                        ->update([
                            'token' => null,
                            'token_updated_at' => now(),
                        ]);
                    break;
                case "staff":
                    DB::table('staff')
                        ->where('id', $user['user_id'])
                        ->update([
                            'token' => null,
                            'token_updated_at' => now(),
                        ]);
                    break;
                case "customer":
                    DB::table('customers')
                        ->where('id', $user['user_id'])
                        ->update([
                            'token' => null,
                            'token_updated_at' => now(),
                        ]);
                    break;
                default:
                    break;
            }

            return [
                'success' => true,
                'message' => "Logout Successful"
            ];
        }
        return  [
            'success' => false,
            'message' => "Logout Unsuccessful"
        ];
    }

    public static function is_agent($auth_result): bool|array
    {
        if ($auth_result['success'] === true && $auth_result['user']['role'] === "agent") {
            return true;
        } else if ($auth_result['success'] === true) {
            return [
                'success' => false,
                'message' => "You Don't have access to this resource"
            ];
        } else {
            return $auth_result;
        }
    }
    public static function is_customer($auth_result): bool|array
    {
        if ($auth_result['success'] === true && $auth_result['user']['role'] === "customer") {
            return true;
        } else if ($auth_result['success'] === true) {
            return [
                'success' => false,
                'message' => "You Don't have access to this resource"
            ];
        } else {
            return $auth_result;
        }
    }
}
