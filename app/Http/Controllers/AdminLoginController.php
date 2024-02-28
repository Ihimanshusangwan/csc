<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class AdminLoginController extends Controller
{
    public function index()
    {
        // Check if the custom cookie exists
        if (Cookie::has('Admin_Session')) {
            // The cookie exists, proceed to the admin dashboard
            // Fetch data from plans and locations tables using the query builder            
            $plans = DB::table('plans')->where("is_active", 1)->get();
            $locations = DB::table('locations')->get();

            $query = DB::table('applications')
                ->join('customers', 'applications.customer_id', '=', 'customers.id')
                ->join('services', 'applications.service_id', '=', 'services.id')
                ->join('agents', 'applications.agent_id', '=', 'agents.id')
                ->select(
                    'applications.*',
                    'services.name as service_name',
                    'customers.name as customer_name',
                    'agents.full_name as agent_name',
                )
                ->orderBy("applications.id", "desc");

            // Fetch paginated applications
            $applications = $query->paginate(15);

            // Get sum of all price column
            $sumOfPrices = DB::table('applications')
                ->sum('price');

            // Get count of today's applications
            $countOfTodaysApplications = DB::table('applications')
                ->whereDate('apply_date', now()->toDateString())
                ->count();

            // Get total application count
            $totalApplicationCount = DB::table('applications')
                ->count();

            // Get completed applications count which have delivery date less than today
            $completedApplicationsCount = DB::table('applications')
                ->whereDate('delivery_date', '<=', now()->toDateString())
                ->count();

            // Calculate pending applications count
            $pendingApplicationsCount = $totalApplicationCount - $completedApplicationsCount;
            // Pass data to the view using compact
            return view('admin.dashboard', compact('plans', 'locations', 'applications', 'sumOfPrices', 'countOfTodaysApplications', 'totalApplicationCount', 'completedApplicationsCount', 'pendingApplicationsCount'));
        } else {

            return view('admin.login');
        }
    }
    // Display the login form
    public function showLoginForm()
    {
        // Check if the custom cookie exists
        if (Cookie::has('Admin_Session')) {
            // The cookie exists, proceed to the admin dashboard
            return redirect()->route('admin.dashboard');
        } else {

            return view('admin.login');
        }
    }

    // Handle login
    public function login(Request $request)
    {
        // Validate login data
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate
        if (Auth::guard('admins')->attempt($credentials)) {
            // Authentication passed for admin guard

            // Get the session lifetime from the configuration
            $sessionLifetime = config('session.lifetime');

            // Set a custom cookie with the same lifetime as the session
            $customValue = 'true';
            $cookie = cookie('Admin_Session', $customValue, $sessionLifetime);

            // Redirect with the custom cookie
            return redirect()->intended('/admin/dashboard')->withCookie($cookie);
        } else {
            // Authentication failed for admin guard
            return back()->withErrors(['email' => 'Invalid credentials'])->withInput($request->only('email'));
        }

    }
    public function logout(Request $request)
    {
        // Logout the user from the admin guard
        Auth::guard('admins')->logout();

        // Forget the 'Admin_Session' cookie
        $cookie = cookie('Admin_Session', null, -1);

        // Redirect to the login page or any other desired page after logout
        return redirect('/')->withCookie($cookie);
    }
    public function agentView(Request $request, $id)
    {
        // Check if the custom cookie exists
        if (Cookie::has('Admin_Session')) {
            // The cookie exists, proceed to the admin dashboard

            $query = DB::table('applications')
                ->where('applications.agent_id', $id)
                ->join('customers', 'applications.customer_id', '=', 'customers.id')
                ->join('services', 'applications.service_id', '=', 'services.id')
                ->join('agents', 'applications.agent_id', '=', 'agents.id')
                ->select(
                    'applications.*',
                    'services.name as service_name',
                    'customers.name as customer_name',
                    'agents.full_name as agent_name',
                )
                ->orderBy("applications.id", "desc");

            // Fetch paginated applications
            $applications = $query->paginate(15);

            // Get sum of all price column
            $sumOfPrices = DB::table('applications')
                ->where('agent_id', $id)
                ->sum('price');

            // Get count of today's applications
            $countOfTodaysApplications = DB::table('applications')
                ->where('agent_id', $id)
                ->whereDate('apply_date', now()->toDateString())
                ->count();

            // Get total application count
            $totalApplicationCount = DB::table('applications')
                ->where('agent_id', $id)
                ->count();

            // Get completed applications count which have delivery date less than today
            $completedApplicationsCount = DB::table('applications')
                ->where('agent_id', $id)
                ->whereDate('delivery_date', '<=', now()->toDateString())
                ->count();

            // Calculate pending applications count
            $pendingApplicationsCount = $totalApplicationCount - $completedApplicationsCount;
            // Pass data to the view using compact
            return view('admin.agentCustomers', compact('applications', 'sumOfPrices', 'countOfTodaysApplications', 'totalApplicationCount', 'completedApplicationsCount', 'pendingApplicationsCount'));
        } else {

            return view('admin.login');
        }
    }
    public function filter(Request $request)
    {
        // Check if the custom cookie exists
        if (Cookie::has('Admin_Session')) {
            // The cookie exists, proceed to the admin dashboard
            $services = DB::table('services')->where("is_active", 1)->get();
            $agents = DB::table('agents')->get();

            // Retrieve form data
            $dateFrom = $request->input('dateFrom');
            $dateTo = $request->input('dateTo');
            $agentName = $request->input('agentName');
            $status = $request->input('status');
            $applicantName = $request->input('applicantName');
            $service = $request->input('services');

            // Start with a base query
            $query = DB::table('applications')
                ->join('customers', 'applications.customer_id', '=', 'customers.id')
                ->join('services', 'applications.service_id', '=', 'services.id')
                ->join('agents', 'applications.agent_id', '=', 'agents.id')
                ->select(
                    'applications.*',
                    'services.name as service_name',
                    'customers.name as customer_name',
                    'agents.full_name as agent_name',
                )
                ->orderBy("applications.id", "desc");

            // Apply filters conditionally based on input values
            if ($dateFrom) {
                $query->where('applications.apply_date', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->where('applications.apply_date', '<=', $dateTo);
            }
            if ($agentName) {
                $query->where('agents.id', $agentName);
            }
            if ($service) {
                $query->where('services.id', $service);
            }
            if ($applicantName) {
                $query->where('customers.name', 'like', '%' . $applicantName . '%');
            }
            if ($status) {
                $today = strtotime('today');
                if ($status == "completed") {
                    $query->where('applications.delivery_date', ">=", $today);
                } elseif ($status == "initiated") {
                    $query->where('applications.apply_date', "=", $today);
                } else {
                    $query->where('applications.delivery_date', "<", $today);
                }
            }
            // Fetch paginated applications
            $applications = $query->paginate(15);

            // Get sum of all price column
            $sumOfPrices = $query
                ->sum('price');


            // Pass data to the view using compact
            return view('admin.filter', compact('applications', 'sumOfPrices', 'services', 'agents'));
        } else {

            return view('admin.login');
        }
    }
    public function rechargeHistory(Request $request)
    {

        $query = DB::table('recharges')
            ->join('agents', 'recharges.agent_id', '=', 'agents.id')
            ->select(
                'recharges.*',
                'agents.full_name as agent_name',
            )
            ->orderBy("recharges.id", "desc");

        // Fetch paginated applications
        $recharges = $query->paginate(15);

        // Get sum of all earnings
        $earnings = DB::table('recharges')
            ->sum('amount');

        return view("admin.rechargeHistory", compact('recharges', 'earnings'));

    }
}
