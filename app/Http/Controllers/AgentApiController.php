<?php

namespace App\Http\Controllers;

use App\Helpers\UserAuthentication;
use Illuminate\Http\Request;
use App\Models\Agent;

class AgentApiController extends Controller
{
    public function index(Request $request)
    {
        $authResult = UserAuthentication::authenticateUser($request);
        if ($authResult['success'] === true && $authResult['user']['role'] === "agent") {
            $agent_id = $authResult['user']['user_id'];
            $dashboard_data = Agent::get_dashboard_data($agent_id);
            return response()->json($dashboard_data, 200);
        } else if ($authResult['success'] === true) {
            return response()->json([
                'success' => false,
                'message' => "You Don't have access to this resource"
            ], 200);
        }
        return response()->json($authResult, 200);
    }
}