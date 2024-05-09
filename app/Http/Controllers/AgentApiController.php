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
        $verify_agent = UserAuthentication::is_agent($authResult);
        if ($verify_agent === true) {
            $agent_id = $authResult['user']['user_id'];
            $dashboard_data = Agent::get_dashboard_data($agent_id);
            return response()->json($dashboard_data, 200);
        }
        return response()->json($verify_agent, 200);
    }
    public function profile(Request $request)
    {
        $authResult = UserAuthentication::authenticateUser($request);
        $verify_agent = UserAuthentication::is_agent($authResult);
        if ($verify_agent === true) {
            $agent_id = $authResult['user']['user_id'];
            $profile_data = Agent::get_agent_profile_data($agent_id);
            if ($profile_data) {
                $response = [
                    "success" => true,
                    "data" => $profile_data
                ];
            } else {
                $response = [
                    "success" => false,
                    "message" => "Invalid Agent Id"
                ];
            }

            return response()->json($response, 200);
        }
        return response()->json($verify_agent, 200);
    }
    public function applications(Request $request)
    {
        $authResult = UserAuthentication::authenticateUser($request);
        $verify_agent = UserAuthentication::is_agent($authResult);
        if ($verify_agent === true) {
            $agent_id = $authResult['user']['user_id'];
            $category = $request->has('category') ? $request->input('category') : 'all';
            $order = $request->has('order') ? $request->input('order') : 'desc';
            $offset = $request->has('offset') ? intval($request->input('offset')) : 0;
            $limit = $request->has('limit') ? intval($request->input('limit')) : 100;
            $offset = max(0, $offset);
            $limit = max(1, $limit);
            if ($order !== "asc" || $order !== "desc") {
                $order = "desc";
            }
            $applications = Agent::get_agent_applications_for_category($agent_id, $category, $offset, $limit, $order);
            if ($applications) {
                $response = [
                    "success" => true,
                    "data" => $applications
                ];
            } else {
                $response = [
                    "success" => false,
                    "message" => "Invalid Agent Id"
                ];
            }

            return response()->json($response, 200);
        }
        return response()->json($verify_agent, 200);
    }
    public function applyService(Request $request)
    {
        $authResult = UserAuthentication::authenticateUser($request);
        $verify_agent = UserAuthentication::is_agent($authResult);
        if ($verify_agent === true) {
            $agent_id = $authResult['user']['user_id'];
            $service_id = $request->has('service') ? intval($request->input('service')) : null;
            if ($service_id) {
                $response = AGENT::get_service_data($service_id, $agent_id);
            } else {
                $response = [
                    "success" => false,
                    "message" => "Service Id Not Provided"
                ];
            }
            return response()->json($response, 200);
        }
        return response()->json($verify_agent, 200);
    }
    public function applyServiceSubmit(Request $request)
    {
        $authResult = UserAuthentication::authenticateUser($request);
        $verify_agent = UserAuthentication::is_agent($authResult);
        if ($verify_agent === true) {
            $agent_id = $authResult['user']['user_id'];
            $service_id = $request->has('service') ? intval($request->input('service')) : null;
            if ($service_id) {
                $data=[];
                $data['price_type'] = $request->input('price_type');
                $data['customer_name'] = $request->input('customer_name');
                $data['customer_number'] = $request->input('customer_name');
                $data['form_data'] = $request->input('form_data');
                $response = AGENT::store_service_data($service_id, $agent_id,$data, $request);
            } else {
                $response = [
                    "success" => false,
                    "message" => "Service Id Not Provided"
                ];
            }
            return response()->json($response, 200);
        }
        return response()->json($verify_agent, 200);
    }
}
