<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\DatabaseTroubleshooter;


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
                ->leftJoin('staff', 'applications.staff_id', '=', 'staff.id')
                ->select(
                    'staff.name as staffName',
                    'applications.*',
                    'services.name as service_name',
                    'customers.name as customer_name',
                    'agents.full_name as agent_name',
                    DB::raw('(SELECT GROUP_CONCAT(CONCAT(id, ":", status_name, ":" , color)) FROM service_statuses WHERE service_statuses.service_id = applications.service_id) as statuses')
                )
                ->orderBy("applications.id", "desc");

            // Fetch paginated applications
            $applications = $query->paginate(15);
            // dd($applications);
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
                ->where('status', 2)
                ->count();

            // Calculate pending applications count
            $pendingApplicationsCount = $totalApplicationCount - $completedApplicationsCount;
            // Pass data to the view using compact
            return view('admin.dashboard', compact('plans', 'locations', 'applications', 'sumOfPrices', 'countOfTodaysApplications', 'totalApplicationCount', 'completedApplicationsCount', 'pendingApplicationsCount'));
        } else {

            return view('admin.login');
        }
    }
    public function troubleshoot()
    {
        // Check if the custom cookie exists
        if (Cookie::has('Admin_Session')) {
            $troubleshooter = new DatabaseTroubleshooter();
            $issues = $troubleshooter->checkPricesAndFormData();
            // dd($issues);
            return view('admin.troubleshooter', compact('issues'));
        } else {

            return view('admin.login');
        }
    }
    public function customerData()
    {
        $plans = DB::table('plans')->where("is_active", 1)->get();
        $locations = DB::table('locations')->get();

        $query = DB::table('applications')
            ->join('customers', 'applications.customer_id', '=', 'customers.id')
            ->join('services', 'applications.service_id', '=', 'services.id')
            ->join('agents', 'applications.agent_id', '=', 'agents.id')
            ->leftJoin('staff', 'applications.staff_id', '=', 'staff.id')
            ->select(
                'staff.name as staffName',
                'applications.*',
                'services.name as service_name',
                'customers.name as customer_name',
                'agents.full_name as agent_name',
                DB::raw('(SELECT GROUP_CONCAT(CONCAT(id, ":", status_name)) FROM service_statuses WHERE service_statuses.service_id = applications.service_id) as statuses')
            )
            ->orderBy("applications.id", "desc");

        // Fetch paginated applications
        $applications = $query->get();
        // dd($applications);

        // // Get sum of all price column
        // $sumOfPrices = DB::table('applications')
        //     ->sum('price');

        // // Get count of today's applications
        // $countOfTodaysApplications = DB::table('applications')
        //     ->whereDate('apply_date', now()->toDateString())
        //     ->count();

        // // Get total application count
        // $totalApplicationCount = DB::table('applications')
        //     ->count();

        // // Get completed applications count which have delivery date less than today
        // $completedApplicationsCount = DB::table('applications')
        //     ->where('status', 2)
        //     ->count();

        // // Calculate pending applications count
        // $pendingApplicationsCount = $totalApplicationCount - $completedApplicationsCount;
        // Pass data to the view using compact
        // return view('admin.dashboard', compact('plans', 'locations', 'applications', 'sumOfPrices', 'countOfTodaysApplications', 'totalApplicationCount', 'completedApplicationsCount', 'pendingApplicationsCount'));
        return $applications->toJson();
    }
    public function showStaffDetails(Request $request)
    {
        // Check if the custom cookie exists
        if (Cookie::has('Admin_Session')) {
            // The cookie exists, proceed to the admin dashboard


            $query = DB::table('staff')
                ->leftJoin('locations', 'staff.location_id', '=', 'locations.id')
                ->leftJoin('service_groups', 'staff.service_group_id', '=', 'service_groups.id')
                ->select(
                    'staff.*',
                    'locations.district as city',
                    'service_groups.name as service_group_name'
                )
                ->orderBy("staff.id", "desc");

            // Fetch paginated applications
            $staffs = $query->paginate(15);

            // Pass data to the view using compact
            return view('admin.registeredStaff', compact('staffs'));
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
    public function agentView(Request $request, $id, $category)
    {
        // Check if the custom cookie exists
        if (Cookie::has('Admin_Session')) {
            // The cookie exists, proceed to the admin dashboard

            $query = DB::table('applications')
                ->where('applications.agent_id', $id)
                ->join('customers', 'applications.customer_id', '=', 'customers.id')
                ->join('services', 'applications.service_id', '=', 'services.id')
                ->join('agents', 'applications.agent_id', '=', 'agents.id')
                ->leftJoin('staff', 'applications.staff_id', '=', 'staff.id')
                ->select(
                    'staff.name as staffName',
                    'applications.*',
                    'services.name as service_name',
                    'customers.name as customer_name',
                    'agents.full_name as agent_name',
                    DB::raw('(SELECT GROUP_CONCAT(CONCAT(id, ":", status_name)) FROM service_statuses WHERE service_statuses.service_id = applications.service_id) as statuses')
                )
                ->orderBy("applications.id", "desc");
            switch ($category) {
                case "all":
                    // No need for any additional filtering
                    break;
                case "today":
                    $query->whereDate("applications.apply_date", "=", today()->toDateString());
                    break;
                case "completed":
                    $query->whereDate("applications.delivery_date", "<=", today()->toDateString());
                    break;
                case "pending":
                    $query->whereDate("applications.delivery_date", ">=", today()->toDateString())->orWhere('applications.status', '!=', 2);

                    break;
            }

            // Fetch paginated applications
            $applications = $query->paginate(15);

            // Get sum of all price column
            $sumOfPrices = $query->sum('price');

            // Get count of today's applications
            $countOfTodaysApplications = DB::table('applications')
                ->where('applications.agent_id', $id)
                ->whereDate('apply_date', now()->toDateString())
                ->count();

            // Get total application count
            $totalApplicationCount = DB::table('applications')
                ->where('agent_id', $id)
                ->count();

            // Get completed applications count which have delivery date less than today
            $completedApplicationsCount = DB::table('applications')
                ->where('applications.agent_id', $id)->whereDate('delivery_date', '<=', today()->toDateString())
                ->count();

            // Calculate pending applications count
            $pendingApplicationsCount = $totalApplicationCount - $completedApplicationsCount;
            // Pass data to the view using compact
            return view('admin.agentCustomers', compact('applications', 'sumOfPrices', 'countOfTodaysApplications', 'totalApplicationCount', 'completedApplicationsCount', 'pendingApplicationsCount', 'id', 'category'));
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
            $statuses = DB::table('service_statuses')->select('id', 'status_name')->get();

            // Retrieve form data
            $dateFrom = $request->input('dateFrom');
            $dateTo = $request->input('dateTo');
            $agentName = $request->input('agentName');
            $status = $request->input('status');
            $applicantName = $request->input('applicantName');
            $service = $request->input('services');
            $price_type = $request->input('price_type');
            $agent_type = $request->input('agent_type');

            // Start with a base query
            $query = DB::table('applications')
                ->join('customers', 'applications.customer_id', '=', 'customers.id')
                ->join('services', 'applications.service_id', '=', 'services.id')
                ->join('agents', 'applications.agent_id', '=', 'agents.id')
                ->join('service_groups', 'services.service_group_id', '=', 'service_groups.id')
                ->select(
                    'applications.*',
                    'service_groups.name as service_group_name',
                    'services.name as service_name',
                    'customers.name as customer_name',
                    'agents.full_name as agent_name',
                    DB::raw('(SELECT GROUP_CONCAT(CONCAT(id, ":", status_name, ":" ,color)) FROM service_statuses WHERE service_statuses.service_id = applications.service_id) as statuses')
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
            if ($status !== null && $status !== '') {
                $query->where('applications.status', '=', $status);
            }
            if ($price_type) {
                $query->where('price_type', '=', $price_type);
            }
            if ($agent_type !== null && $agent_type !== '') {
                $query->where('is_agent_subscribed', '=', $agent_type);
            }
            $applications = $query->get();
            $originalCollection = Collection::make($applications);
            $groupedByServiceGroup = $originalCollection->groupBy('service_group_id');
            $structuredData = [];

            foreach ($groupedByServiceGroup as $serviceGroupId => $collection) {
                $collection = new Collection($collection);
                $totalPrice = 0;
                $totalGovtPrice = 0;
                $totalCommission = 0;
                $totalTax = 0;

                foreach ($collection as $item) {
                    if (is_object($item)) {
                        // Calculate totals
                        $totalPrice += (float) ($item->price ?? 0);
                        $totalGovtPrice += (float) ($item->govt_price ?? 0);
                        $totalCommission += (float) ($item->commission ?? 0);
                        $totalTax += (float) ($item->tax ?? 0);
                    }
                }

                $servicesData = $collection->groupBy('service_name')->map(function ($serviceCollection) {
                    $serviceTotalPrice = $serviceCollection->sum('price');
                    $serviceTotalGovtPrice = $serviceCollection->sum('govt_price');
                    $serviceTotalCommission = $serviceCollection->sum('commission');
                    $serviceTotalTax = $serviceCollection->sum('tax');

                    return [
                        'total_price' => $serviceTotalPrice,
                        'total_govt_price' => $serviceTotalGovtPrice,
                        'total_commission' => $serviceTotalCommission,
                        'total_tax' => $serviceTotalTax,
                    ];
                });

                if (!empty($collection->first()->service_group_name)) {
                    $structuredData[] = [
                        'service_group_name' => $collection->first()->service_group_name,
                        'total_price' => $totalPrice,
                        'total_govt_price' => $totalGovtPrice,
                        'total_commission' => $totalCommission,
                        'total_tax' => $totalTax,
                        'services' => $servicesData,
                    ];
                }
            }
            // Get sum of all price column
            $sumOfPrices = $query
                ->sum('price');
            //total govt. price 
            $sumOfGovtPrice = $query
                ->sum('govt_price');
            //total commission
            $sumOfCommission = $query->sum('commission');
            //total tax
            $sumOfTax = $query->sum('tax');

            // Pass data to the view using compact
            return view('admin.filter', compact('applications', 'sumOfPrices', 'services', 'agents', 'statuses', 'sumOfGovtPrice', 'sumOfCommission', 'sumOfTax', 'structuredData'));
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
    public function appointments(Request $request)
    {

        $query = DB::table('appointments')
            ->where('appointments.status', "=", 0)
            ->join('locations', 'appointments.city_id', '=', 'locations.id')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->select(
                'appointments.id as appointment_id',
                'appointments.*',
                'locations.*',
                'services.name as service',
            )
            ->orderby('appointments.selected_date', 'asc');

        // Fetch paginated applications
        $appointments = $query->paginate(15);
        // dd($appointments);

        return view("admin.appointments", compact('appointments'));
    }
    public function visitedAppointments(Request $request)
    {

        $query = DB::table('appointments')
            ->where('appointments.status', 1)
            ->join('locations', 'appointments.city_id', '=', 'locations.id')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->select(

                'appointments.id as appointment_id',
                'appointments.*',
                'locations.*',
                'services.name as service',
            )
            ->orderby('appointments.id', 'desc');

        // Fetch paginated applications
        $appointments = $query->paginate(15);

        return view("admin.visitedAppointments", compact('appointments'));
    }
    public function rejectedAppointments(Request $request)
    {

        $query = DB::table('appointments')
            ->where('appointments.status', 2)
            ->join('locations', 'appointments.city_id', '=', 'locations.id')
            ->join('appointment_deletion_reasons', 'appointment_deletion_reasons.appointment_id', '=', 'appointments.id')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->select(

                'appointments.id as appointment_id',
                'appointment_deletion_reasons.reason',
                'appointments.*',
                'locations.*',
                'services.name as service',
            )
            ->orderby('appointments.id', 'desc');

        // Fetch paginated applications
        $appointments = $query->paginate(15);

        return view("admin.deletedAppointments", compact('appointments'));
    }
    public function deleteData(Request $request)
    {
        $password = $request->input('password');

        // Hardcoded password for demonstration purposes
        if ($password === 'Webifly@123') {
            // Tables to preserve
            $tablesToPreserve = ['admins'];

            // Get all table names from the database
            $tables = collect(DB::select('SHOW TABLES'))->pluck('Tables_in_' . env('DB_DATABASE'));

            // Remove tables to preserve from the list
            $tablesToDelete = $tables->reject(function ($table) use ($tablesToPreserve) {
                return in_array($table, $tablesToPreserve);
            });

            // Delete data from each table
            $tablesToDelete->each(function ($table) {
                DB::table($table)->delete();
            });

            return redirect()->back()->with('success', 'Data cleared successfully.');
        }

        return redirect()->back()->with('error', 'Incorrect password.');
    }


    public function showDeleteForm()
    {
        return view('admin.deleteData');
    }

    public function applications($category)
    {
        // Check if the custom cookie exists
        if (Cookie::has('Admin_Session')) {
            // The cookie exists, proceed to the admin dashboard

            $query = DB::table('applications')
                ->join('customers', 'applications.customer_id', '=', 'customers.id')
                ->join('services', 'applications.service_id', '=', 'services.id')
                ->join('agents', 'applications.agent_id', '=', 'agents.id')
                ->leftJoin('staff', 'applications.staff_id', '=', 'staff.id')
                ->select(
                    'staff.name as staffName',
                    'applications.*',
                    'services.name as service_name',
                    'customers.name as customer_name',
                    'agents.full_name as agent_name',
                    DB::raw('(SELECT GROUP_CONCAT(CONCAT(id, ":", status_name)) FROM service_statuses WHERE service_statuses.service_id = applications.service_id) as statuses')
                )
                ->orderBy("applications.id", "desc");
            switch ($category) {
                case "all":
                    // No need for any additional filtering
                    break;
                case "today":
                    $query->whereDate("applications.apply_date", "=", today()->toDateString());
                    break;
                case "completed":
                    $query->whereDate("applications.delivery_date", "<=", today()->toDateString());
                    break;
                case "pending":
                    $query->whereDate("applications.delivery_date", ">=", today()->toDateString())->orWhere('applications.status', '!=', 2);

                    break;
            }
            // Fetch paginated applications
            $applications = $query->paginate(15);

            // Get sum of all price column
            $sumOfPrices = $query
                ->sum('price');

            // Get count of today's applications
            $countOfTodaysApplications = DB::table('applications')
                ->whereDate('apply_date', now()->toDateString())
                ->count();

            // Get total application count
            $totalApplicationCount = DB::table('applications')
                ->count();

            // Get completed applications count which have delivery date less than today
            $completedApplicationsCount = DB::table('applications')->whereDate('delivery_date', '<=', today()->toDateString())
                ->count();

            // Calculate pending applications count
            $pendingApplicationsCount = $totalApplicationCount - $completedApplicationsCount;
            // Pass data to the view using compact
            return view('admin.applications', compact('applications', 'sumOfPrices', 'countOfTodaysApplications', 'totalApplicationCount', 'completedApplicationsCount', 'pendingApplicationsCount', 'category'));
        } else {

            return view('admin.login');
        }
    }

    public function showBill(Request $request)
    {

        // Check if the custom cookie exists
        if (Cookie::has('Admin_Session')) {
            return view('admin.bill');
        } else {

            return view('admin.login');
        }
    }

    public function submitBill(Request $request)
    {
        // Check if the custom cookie exists
        if (Cookie::has('Admin_Session')) {
            $form_data = $request->input('form_data');
            $data = json_decode($form_data, true);
            $customerName = $data['customerName'];
            $customerNumber = $data['customerNumber'];
            $description = $data['description'];
            $grandTotal = $data['grandTotal'];
            $grandNetCommission = $data['grandNetCommission'];
            $netTax = $data['netTax'];
            $items = $data['items'];


            // Insert data into the 'bills' table
            $billId = DB::table('bills')->insertGetId([
                'customer_name' => $customerName,
                'customer_number' => $customerNumber,
                'description' => $description,
                'grand_total' => $grandTotal,
                'grand_net_commission' => $grandNetCommission,
                'net_tax' => $netTax,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Insert data into the 'bill_items' table
            foreach ($items as $item) {
                DB::table('bill_items')->insert([
                    'bill_id' => $billId,
                    'item_name' => $item['itemName'],
                    'base_price' => $item['basePrice'],
                    'commission' => $item['commission'],
                    'tax' => $item['tax'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            return redirect()->route('admin.dashboard')->with(['success' => 'Bill data has been stored successfully']);
        } else {

            return view('admin.login');
        }
    }
    public function billFilter(Request $request)
    {
        // Check if the custom cookie exists
        if (Cookie::has('Admin_Session')) {

            // Retrieve form data
            $dateFrom = $request->input('dateFrom');
            $dateTo = $request->input('dateTo');
            $customerName = $request->input('customerName');
            $customerNumber = $request->input('customerNumber');
            $desc = $request->input('desc');

            // Start with a base query
            $query = DB::table('bills')
                ->select(
                    'bills.*',
                )
                ->orderBy("bills.id", "desc");




            // Apply filters conditionally based on input values
            if ($dateFrom) {
                $query->where('bills.created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->where('bills.created_at', '<=', $dateTo);
            }
            if ($customerName) {
                $query->where('bills.customer_name', 'like', '%' . $customerName . '%');
            }
            if ($customerNumber) {
                $query->where('bills.customer_number', 'like', '%' . $customerNumber . '%');
            }
            if ($desc) {
                $query->where('bills.description', 'like', '%' . $desc . '%');
            }
            $bills = $query->get();
            // Get sum of all price column
            $sumOfPrices = $query
                ->sum('grand_total');
            //total commission
            $sumOfCommission = $query->sum('grand_net_commission');
            //total tax
            $sumOfTax = $query->sum('net_tax');
            // Pass data to the view using compact
            return view('admin.billFilter', compact('bills', 'sumOfPrices', 'sumOfCommission', 'sumOfTax'));
        } else {

            return view('admin.login');
        }
    }

    public function fetchItems($billId)
    {
        try {
            // Fetch items for the specified billId using query builder
            $items = DB::table('bill_items')
                ->where('bill_id', $billId)
                ->get();

            // Return the items as JSON response
            return response()->json($items);
        } catch (\Exception $e) {
            // Handle any errors and return an error response
            return response()->json(['error' => 'Failed to fetch items.'], 500);
        }
    }
    public function deployTest()
    {


        // Enable error reporting
        error_reporting(E_ALL);

        // Define variables
        $gitRepo = "https://github.com/Ihimanshusangwan/csc";
        $branch = "test";
        $hostingerFileManagerDir = __DIR__; // Same directory as the PHP script

        // Check if Git is installed
        if (!shell_exec("git --version")) {
            echo "Error: Git is not installed or accessible. Please install Git on your server.";
            exit;
        }

        // Check if the directory is a Git repository
        if (!is_dir("$hostingerFileManagerDir/.git")) {
            // If not, initialize a new Git repository
            $output = shell_exec("git -C $hostingerFileManagerDir init 2>&1");
            echo "Git Init Output: <pre>$output</pre>";

            // Add the remote repository
            $output = shell_exec("git -C $hostingerFileManagerDir remote add origin $gitRepo 2>&1");
            echo "Git Add Remote Output: <pre>$output</pre>";
        }

        // Pull latest changes from GitHub
        $output = shell_exec("git -C $hostingerFileManagerDir pull origin $branch 2>&1");
        echo "Git Pull Output: <pre>$output</pre>";

        // Check if there were any errors during git pull
        if (strpos($output, "error") !== false) {
            echo "Error: There was an error during 'git pull'. Please check the output above for details.";
            exit;
        }

        // Check if the branch is already up to date
        if (strpos($output, "Already up to date.") !== false) {
            echo "Branch is already up to date. No need to copy files.";
            exit;
        }

        // Copy files to Hostinger file manager directory
        recursive_copy(".", $hostingerFileManagerDir);

        // Recursive function to copy files
        function recursive_copy($source, $dest)
        {
            // Check if source is a directory
            if (is_dir($source)) {
                // Create destination directory if it doesn't exist
                if (!is_dir($dest)) {
                    mkdir($dest);
                }

                // Loop through files in source directory
                $files = scandir($source);
                foreach ($files as $file) {
                    if ($file != "." && $file != ".." && $file != ".git") {
                        // Recursive copy for subdirectories
                        if (is_dir("$source/$file")) {
                            recursive_copy("$source/$file", "$dest/$file");
                        } else {
                            // Copy file
                            if (!copy("$source/$file", "$dest/$file")) {
                            } else {
                                echo "Copied file '$file' to '$dest'.<br>";
                            }
                        }
                    }
                }
            } else {
                // Copy single file
                if (!copy($source, $dest)) {
                } else {
                    echo "Copied file '$source' to '$dest'.<br>";
                }
            }
        }

        echo "Deployment successful!";
    }
}
