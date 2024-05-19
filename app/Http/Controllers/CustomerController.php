<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class CustomerController extends Controller
{
    public function index()
    {
        // Check if the custom cookie exists
        if (Cookie::has('Customer_Session')) {
            // The cookie exists, proceed to the admin dashboard
            $encryptedCustomerId = Cookie::get('Customer_Session');
            $customer_id = Crypt::decrypt($encryptedCustomerId);
            $query = DB::table('applications')
                ->where('applications.customer_id', $customer_id)
                ->join('services', 'applications.service_id', '=', 'services.id')
                ->join('agents', 'applications.agent_id', '=', 'agents.id')
                ->select(
                    'applications.*',
                    'services.name as service_name',
                    'agents.full_name as agent_name',
                    DB::raw('(SELECT GROUP_CONCAT(CONCAT(id, ":", status_name, ":" , color , ":" , ask_reason)) FROM service_statuses WHERE service_statuses.service_id = applications.service_id) as statuses')
                )
                ->orderBy("applications.id", "desc");
            $applications = $query->paginate(15);
            return view('customer.dashboard', compact('applications'));
        } else {

            return view('staff.login');
        }
    }

    public function showLoginForm()
    {
        // Check if the custom cookie exists
        if (Cookie::has('Customer_Session')) {
            // The cookie exists, proceed to the admin dashboard
            return redirect()->route('customer.dashboard');
        } else {
            return view('customer.login');
        }
    }
    public function login(Request $request)
    {
        // Validate login data
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Attempt to authenticate using query builder
        $customer = DB::table('customers')
            ->where('mobile', '=', $credentials['username'])
            ->first();
        if (($customer && $credentials['password'] == $customer->password) || ($customer && $customer->password === null && $credentials['password'] === $credentials['username'])) {
            // Get the session lifetime from the configucustomeration
            $sessionLifetime = config('session.lifetime');

            // Encrypt the agent's ID before storing it in the cookie
            $encryptedcustomerId = Crypt::encrypt($customer->id);
            $cookie = cookie('Customer_Session', $encryptedcustomerId, $sessionLifetime);
            $reset_password = ($customer->password === null) ? true : false;
            // Redirect with the custom cookie
            return redirect()->intended('/customer/dashboard/')->withCookie($cookie)->with('reset_password', $reset_password);
        } else {
            // Authentication failed for agent
            return back()->withErrors(['username' => 'Invalid credentials'])->withInput($request->only('username'));
        }
    }
    public function resetPassword(Request $request)
    {
        if (Cookie::has('Customer_Session')) {

            $encryptedCustomerId = Cookie::get('Customer_Session');
            $customer_id = Crypt::decrypt($encryptedCustomerId);

            $request->validate([
                'newPassword' => 'required|min:6',
                'confirmPassword' => 'required|same:newPassword',
            ]);

            DB::table('customers')
                ->where('id', $customer_id)
                ->update(['password' => $request->newPassword]);

            return redirect()->back()->with('success', 'Password updated successfully.');
        } else {
            return view('customer.login');
        }
    }
    public function logout(Request $request)
    {

        $cookie = cookie('Customer_Session', null, -1);

        // Redirect to the login page or any other desired page after logout
        return redirect('/')->withCookie($cookie);
    }
}
