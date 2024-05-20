<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\UserAuthentication;
use App\Models\Customer;

class CustomerApiController extends Controller
{
    public function get_all_applications(Request $request)
    {
        $authResult = UserAuthentication::authenticateUser($request);
        $verify_customer = UserAuthentication::is_customer($authResult);
        if ($verify_customer === true) {
            $customer_id = $authResult['user']['user_id'];
            $data = Customer::get_all_applications_data($customer_id);
            return response()->json($data, 200);
        }
        return response()->json($verify_customer, 200);
    }
    public function update_password(Request $request)
    {
        $authResult = UserAuthentication::authenticateUser($request);
        $verify_customer = UserAuthentication::is_customer($authResult);
        if ($verify_customer === true) {
            $customer_id = $authResult['user']['user_id'];
            $input_data=[];
            $input_data['new_password'] = $request->input('newPassword');
            $input_data['current_password'] = $request->input('currentPassword');
            $data = Customer::update_password($customer_id , $input_data);
            return response()->json($data, 200);
        }
        return response()->json($verify_customer, 200);
    }
}
