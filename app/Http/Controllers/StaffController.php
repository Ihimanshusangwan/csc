<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class StaffController extends Controller
{
    public function create(Request $request)
    {
        // Check if the custom cookie exists
        if (Cookie::has('Admin_Session')) {
            $locations = DB::table('locations')->get();
            $serviceGroups = DB::table('service_groups')->get();

            return view('admin.registerStaff', compact('locations', 'serviceGroups'));
        } else {

            return view('admin.login');
        }
    }
    public function showLoginForm()
    {
        // Check if the custom cookie exists
        if (Cookie::has('Staff_Session')) {
            // The cookie exists, proceed to the admin dashboard
            return redirect()->route('staff.dashboard', ['category' => 'all']);
        } else {

            return view('staff.login');
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
        $staff = DB::table('staff')
            ->where('username', $credentials['username'])
            ->first();

        if ($staff && $credentials['password'] == $staff->password) {
            // Get the session lifetime from the configustaffation
            $sessionLifetime = config('session.lifetime');

            // Encrypt the agent's ID before storing it in the cookie
            $encryptedStaffId = Crypt::encrypt($staff->id);
            $cookie = cookie('Staff_Session', $encryptedStaffId, $sessionLifetime);

            // Redirect with the custom cookie
            return redirect()->intended('/staff/dashboard/all')->withCookie($cookie);
        } else {
            // Authentication failed for agent
            return back()->withErrors(['username' => 'Invalid credentials'])->withInput($request->only('username'));
        }
    }
    public function index($category)
    {
        // Check if the custom cookie exists
        if (Cookie::has('Staff_Session')) {
            // The cookie exists, proceed to the admin dashboard
            // Retrieve and decrypt the agent's ID from the cookie
            $encryptedStaffId = Cookie::get('Staff_Session');
            $staffId = Crypt::decrypt($encryptedStaffId);
            $query = DB::table('applications')
                ->where('applications.staff_id', $staffId)->where(function ($query) {
                    $query->whereDate("applications.delivery_date", ">=", today()->toDateString())
                        ->orWhereNull("applications.delivery_date");
                })
                ->join('customers', 'applications.customer_id', '=', 'customers.id')
                ->join('services', 'applications.service_id', '=', 'services.id')
                ->join('agents', 'applications.agent_id', '=', 'agents.id')
                ->select(
                    'applications.*',
                    'services.name as service_name',
                    'customers.name as customer_name',
                    'customers.mobile as customer_mobile',
                    'agents.full_name as agent_name',
                    DB::raw('(SELECT GROUP_CONCAT(CONCAT(id, ":", status_name, ":" , color)) FROM service_statuses WHERE service_statuses.service_id = applications.service_id) as statuses')
                )
                ->orderBy("applications.id", "desc");
            switch ($category) {
                case "all":
                    // No need for any additional filtering
                    break;
                case "today":
                    $query->whereDate("applications.apply_date", "=", today()->toDateString());
                    break;
                case "pending":
                    break;
            }

            // Fetch paginated applications
            $applications = $query->paginate(15);


            // Get count of today's applications
            $countOfTodaysApplications = DB::table('applications')
                ->where('applications.staff_id', $staffId)
                ->whereDate('apply_date', now()->toDateString())
                ->count();

            // Get total application count
            $totalApplicationCount = DB::table('applications')
                ->where('applications.staff_id', $staffId)
                ->count();

            // Get completed applications count which have delivery date less than today
            $completedApplicationsCount = DB::table('applications')
                ->where('applications.staff_id', $staffId)
                ->whereDate('delivery_date', '<=', today()->toDateString())
                ->count();

            // Calculate pending applications count
            $pendingApplicationsCount = $totalApplicationCount - $completedApplicationsCount;
            // Pass data to the view using compact
            return view('staff.dashboard', compact('applications', 'countOfTodaysApplications', 'totalApplicationCount', 'completedApplicationsCount', 'pendingApplicationsCount', 'category'));
        } else {

            return view('staff.login');
        }
    }
    public function logout(Request $request)
    {

        // Forget the 'Agent' cookie
        $cookie = cookie('Staff_Session', null, -1);

        // Redirect to the login page or any other desired page after logout
        return redirect('/')->withCookie($cookie);
    }

    public function register(Request $request)
    {
        // Perform validation
        $request->validate([
            'username' => 'required|unique:staff',
            'password' => 'required',
            'name' => 'required',
            'mobile' => 'required',
            'location' => 'required|exists:locations,id',
            'servicegroup' => 'required|exists:service_groups,id',
        ]);
        // Check if the username already exists
        $existingUser = DB::table('staff')->where('username', $request->username)->first();
        if ($existingUser) {
            return back()->withInput()->withErrors(['username' => 'Username already exists']);
        }

        // Insert staff data into the database
        DB::table('staff')->insert([
            'username' => $request->username,
            'password' => $request->password,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'location_id' => $request->location,
            'service_group_id' => $request->servicegroup,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Redirect after successful registration
        return redirect()->route('admin.dashboard')->with('success', 'Staff registered successfully.');
    }
}
