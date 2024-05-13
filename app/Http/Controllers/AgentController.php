<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;

class AgentController extends Controller
{
    private function uploadFile($file, $subfolder)
    {
        // Check if file is not null
        if ($file !== null && $file->isValid()) {
            // Generate a unique filename
            $fileName = uniqid() . '_' . time() . '_' . $file->getClientOriginalName();
            // Build the full path to the file
            $filePath = public_path("uploads/{$subfolder}/" . $fileName);

            // Move the uploaded file to the target location
            if (move_uploaded_file($file->getPathname(), $filePath)) {
                // Return the relative path to store in the database
                return "uploads/{$subfolder}/" . $fileName;
            } else {
                // Handle the case when the file upload fails
                return null;
            }
        } else {
            return null;
        }
    }
    public function store(Request $request)
    {
        // Retrieve data from the request
        $type = $request->input('type');
        $purchaseDate = ($type == "Register") ? now()->toDateString() : null;
        $fullName = $request->input('full_name');
        $mobileNumber = $request->input('mobile_number');
        $email = $request->input('email');
        $address = $request->input('address');
        $shopName = $request->input('shop_name');
        $shopAddress = $request->input('shop_address');
        $username = $request->input('username');
        $password = $request->input('password');
        $plan_id = $request->input('plan_id') ? $request->input('plan_id') : null;
        $location_id = $request->input('location_id');
        $password = $request->input('password');
        // Additional fields for the payment section
        $paymentStatus = ($type == 'Register') ? $request->input('payment_status') : null;
        $paymentMode = ($type == 'Register') ? $request->input('payment_mode') : null;
        $paidAmount = ($type == 'Register') ? $request->input('paid_amount') : null;
        $unpaidAmount = ($type == 'Register') ? $request->input('unpaid_amount') : null;


        $aadharPath = $this->uploadFile($request->file('upload_aadhar'), 'aadhar');
        $shopLicensePath = $this->uploadFile($request->file('upload_shop_license'), 'shop_license');
        $ownerPhotoPath = $this->uploadFile($request->file('upload_owner_photo'), 'owner_photo');
        $uploadSupportingDocumentPath = $this->uploadFile($request->file('uploadSupportingDocument'), 'supporting_document');

        $existingUsername = DB::table('agents')->where('username', $username)->exists();

        // If the username already exists, return an error response
        if ($existingUsername) {
            return redirect()->back()->withInput()->withErrors(['error' => 'The username is already taken. Please choose a different one.']);
        }
        if ($plan_id) {
            $planDuration = DB::table('plans')->where('id', '=', $plan_id)
                ->select('duration')
                ->get()[0]->duration;

            $expirationDate = now()->addDays($planDuration)->toDateString();
        } else {
            $expirationDate = null;
        }



        // Save the data to the database using query builder
        $agentId = DB::table('agents')->insertGetId([
            'type' => $type,
            'full_name' => $fullName,
            'mobile_number' => $mobileNumber,
            'email' => $email,
            'address' => $address,
            'shop_name' => $shopName,
            'shop_address' => $shopAddress,
            'username' => $username,
            'password' => $password,
            'plan_id' => $plan_id,
            'location_id' => $location_id,
            'payment_status' => $paymentStatus,
            'payment_mode' => $paymentMode,
            'paid_amount' => $paidAmount,
            'unpaid_amount' => $unpaidAmount,
            'aadhar_card' => $aadharPath,
            'shop_license' => $shopLicensePath,
            'owner_photo' => $ownerPhotoPath,
            'supporting_document' => $uploadSupportingDocumentPath,
            'purchase_date' => $purchaseDate,
            'expiration_date' => $expirationDate
        ]);
        return redirect()->back()->with(['success' => 'Registration successful']);
    }
    public function showAgentDetails(Request $request)
    {
        // Fetch all agents with associated plans and locations
        $query = DB::table('agents')
            ->join('plans', 'agents.plan_id', '=', 'plans.id')
            ->join('locations', 'agents.location_id', '=', 'locations.id')
            ->select(
                'agents.*',
                'plans.name as plan_name',
                'plans.duration as plan_duration',
                'locations.district',
                'locations.state'
            )
            ->where("agents.type", "=", "Register")
            ->orderBy("agents.id", "desc");

        $earnings = DB::table('agents')
            ->sum('paid_amount');
        $pending = DB::table('agents')
            ->sum('unpaid_amount');

        if ($request->has('plan_name')) {
            $planName = $request->input('plan_name');
            $query->where('plans.name', 'like', "%$planName%");
        }

        if ($request->has('status')) {
            $today = now()->toDateString();

            $query->where(function ($subQuery) use ($today, $request) {
                $status = $request->input('status');

                if ($status == 'inactive') {
                    // Show records that are expired
                    $subQuery->where('agents.expiration_date', '<', $today);
                } elseif ($status == 'expiring') {
                    // Show records that are expiring within 15 days
                    $subQuery->whereBetween('agents.expiration_date', [$today, now()->addDays(15)->toDateString()]);
                } elseif ($status == 'active') {
                    // Show records that are currently active
                    $subQuery->where('agents.expiration_date', '>=', $today);
                }
            });
        }


        // Use paginate directly on the query builder
        $agents = $query->paginate(15);

        // Fetch all plans separately
        $plans = DB::table('plans')
            ->where("is_active", '=', true)->get();

        // Render the 'admin.registered-agents' view and pass the agents and plans data to it
        return view('admin.registeredAgents', compact('agents', 'plans', 'earnings', 'pending'));
    }

    public function showRequestedAgentDetails(Request $request)
    {
        // Fetch all agents with associated plans and locations

        $query = DB::table('agents')
            ->join('locations', 'agents.location_id', '=', 'locations.id')
            ->select(
                'agents.*',
                'locations.district',
                'locations.state'
            )
            ->where("agents.type", "=", "Request")
            ->where("agents.is_hold", "=", "0")
            ->orderBy("agents.id", "desc");
        if ($request->has('date')) {
            $date = $request->input('date');
            $query->where('agents.reg_date', 'like', "%$date%");
        }

        // Use paginate directly on the query builder before calling get()
        $agents = $query->paginate(15);

        $query2 = DB::table('hold_agents')
            ->join('agents', 'hold_agents.agent_id', '=', 'agents.id')
            ->join('locations', 'agents.location_id', '=', 'locations.id')
            ->select(
                'agents.*',
                'hold_agents.*',
                'locations.district',
                'locations.state'
            )
            ->orderBy("agents.id", "desc");
        if ($request->has('date')) {
            $date = $request->input('date');
            $query->where('agents.reg_date', 'like', "%$date%");
        }

        // Use paginate directly on the query builder before calling get()
        $holds = $query2->paginate(15);

        $plans = DB::table('plans')
            ->where("is_active", '=', true)->get();

        // Render the 'admin.registered-agents' view and pass the agents data to it
        return view('admin.requestedAgents', compact('agents', 'plans', 'holds'));
    }
    // approve agent
    public function approve(Request $request)
    {
        $agent_id = $request->input('agent_id');
        $plan_id = $request->input('plan_id');
        $payment_status = $request->input('payment_status');
        $payment_mode = $request->input('payment_mode');
        $paid_amount = $request->input('paid_amount');
        $unpaid_amount = $request->input('unpaid_amount');
        $planDuration = DB::table('plans')->where('id', '=', $plan_id)
            ->select('duration')
            ->get()[0]->duration;

        $expirationDate = now()->addDays($planDuration)->toDateString();

        DB::table('agents')
            ->where('id', $agent_id)
            ->update([
                'type' => 'Register',
                'plan_id' => $plan_id,
                'payment_status' => $payment_status,
                'payment_mode' => $payment_mode,
                'paid_amount' => $paid_amount,
                'unpaid_amount' => $unpaid_amount,
                'purchase_date' => now()->toDateString(),
                'expiration_date' => $expirationDate
            ]);

        return redirect()->route('admin.requested-agents')->with(['success' => 'successfully approved']);
    }
    // reject agent
    public function reject(Request $request)
    {
        $agent_id = $request->input('agent_id');

        DB::table('agents')
            ->where('id', $agent_id)
            ->delete();
        return redirect()->route('admin.requested-agents')->with(['success' => 'successfully deleted']);
    }
    //hold agent
    public function hold(Request $request)
    {
        $agent_id = $request->input('agent_id');
        $reason = $request->input('reason');
        DB::table('agents')
            ->where('id', $agent_id)
            ->update([
                'is_hold' => 1
            ]);
        DB::table('hold_agents')->insert([
            'agent_id' => $agent_id,
            'reason' => $reason,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.requested-agents')->with(['success' => 'successfully put on Hold']);
    }
    //unhold agent
    public function unhold(Request $request)
    {
        $agent_id = $request->input('agent_id');
        DB::table('agents')
            ->where('id', $agent_id)
            ->update([
                'is_hold' => 0
            ]);
        DB::table('hold_agents')
            ->where('agent_id', $agent_id)
            ->delete();

        return redirect()->route('admin.requested-agents')->with(['success' => 'successfully UnHold']);
    }


    //agent login handling
    public function showLoginForm()
    {
        // Check if the custom cookie exists
        if (Cookie::has('Agent_Session')) {
            // The cookie exists, proceed to the admin dashboard
            return redirect()->route('agent.dashboard');
        } else {

            return view('agent.login');
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
        $agent = DB::table('agents')
            ->where('username', $credentials['username'])
            ->first();

        if ($agent && $credentials['password'] == $agent->password) {
            // Authentication passed for agent

            // Get the session lifetime from the configuration
            $sessionLifetime = config('session.lifetime');

            // Encrypt the agent's ID before storing it in the cookie
            $encryptedAgentId = Crypt::encrypt($agent->id);
            $cookie = cookie('Agent_Session', $encryptedAgentId, $sessionLifetime);

            // Redirect with the custom cookie
            return redirect()->intended('/agent/dashboard')->withCookie($cookie);
        } else {
            // Authentication failed for agent
            return back()->withErrors(['username' => 'Invalid credentials'])->withInput($request->only('username'));
        }
    }
    public function index()
    {
        // Check if the custom cookie exists
        if (Cookie::has('Agent_Session')) {
            // The cookie exists, proceed to the admin dashboard
            // Retrieve and decrypt the agent's ID from the cookie
            $encryptedAgentId = Cookie::get('Agent_Session');
            $agentId = Crypt::decrypt($encryptedAgentId);
            // Find the corresponding agent by their Id
            $plan = DB::table("agents")->select('plan_id', 'expiration_date')->where("id", "=", $agentId)->first();
            $currentDate = date('Y-m-d'); // Get the current date in the format YYYY-MM-DD

            if ($plan->expiration_date >= $currentDate) {
                $services = DB::table("services")
                    ->leftJoin("plan_services", function ($join) use ($plan) {
                        $join->on("services.id", "=", "plan_services.service_id")
                            ->where("plan_services.plan_id", "=", $plan->plan_id);
                    })
                    ->join("service_groups", "services.service_group_id", "=", "service_groups.id")
                    ->where(function ($query) {
                        $query->whereNotNull("plan_services.plan_id")
                            ->orWhere("services.availability", 2);
                    })
                    ->select("services.*", "service_groups.name as group_name", "service_groups.photo as group_photo")
                    ->get()
                    ->groupBy('service_group_id');
            } else {
                $services = DB::table("services")
                    ->where("services.availability", 2)
                    ->join("service_groups", "services.service_group_id", "=", "service_groups.id")
                    ->select("services.*", "service_groups.name as group_name", "service_groups.photo as group_photo")
                    ->get()
                    ->groupBy('service_group_id');
            }

            // Format the services data for the view
            $serviceGroups = [];
            foreach ($services as $serviceGroupId => $groupedServices) {
                $serviceGroups[] = [
                    'group_id' => $serviceGroupId,
                    'group_name' => $groupedServices->first()->group_name,
                    'group_photo' => $groupedServices->first()->group_photo,
                    'services' => $groupedServices,
                ];
            }
            // Get sum of all price column
            $sumOfPrices = DB::table('applications')
                ->where('agent_id', $agentId)
                ->sum('price');
            //agent balance
            $balance = DB::table('agents')
                ->where('id', $agentId)
                ->first(["balance"])->balance;

            // Pass data to the view using compact, including the decrypted agent ID
            return view('agent.dashboard', compact('serviceGroups', 'sumOfPrices', 'balance'));
        } else {

            return view('agent.login');
        }
    }

    public function logout(Request $request)
    {

        // Forget the 'Agent' cookie
        $cookie = cookie('Agent_Session', null, -1);

        // Redirect to the login page or any other desired page after logout
        return redirect('/')->withCookie($cookie);
    }
    public function view($serviceGroupId)
    {
        $services = DB::table('services')->where("service_group_id", "=", $serviceGroupId)->get();
        return view('agent.serviceGroup', compact('services'));
    }

    public function applications(Request $request,$category)
    {
        if (Cookie::has('Agent_Session')) {
            // Retrieve and decrypt the agent's ID from the cookie
            $encryptedAgentId = Cookie::get('Agent_Session');
            $agentId = Crypt::decrypt($encryptedAgentId);

            $query = DB::table('applications')
                ->where('applications.agent_id', $agentId)
                ->join('customers', 'applications.customer_id', '=', 'customers.id')
                ->join('services', 'applications.service_id', '=', 'services.id')
                ->select(

                    'applications.*',
                    'services.name as service_name',
                    'customers.name as customer_name',
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
                    case "completed":
                        $query->whereDate("applications.delivery_date", "<=", today()->toDateString());
                        break;
                    case "pending":
                        $query->Where('applications.status', '!=', 2);
    
                        break;
                }
    
            // Fetch paginated applications
            $applications = $query->paginate(15);

            // Get sum of all price column
            $sumOfPrices = $query->sum('price');

            // Get count of today's applications
            $countOfTodaysApplications = DB::table('applications')
                ->where('agent_id', $agentId)
                ->whereDate('apply_date', now()->toDateString())
                ->count();

            // Get total application count
            $totalApplicationCount = DB::table('applications')
                ->where('agent_id', $agentId)
                ->count();

            // Get completed applications count which have delivery date less than today
            $completedApplicationsCount = DB::table('applications')
            ->where('applications.agent_id', $agentId)->whereDate('delivery_date', '<=', today()->toDateString())
                ->count();


            // Calculate pending applications count
            $pendingApplicationsCount = $totalApplicationCount - $completedApplicationsCount;

            return view("agent.applications", compact('applications', 'sumOfPrices', 'countOfTodaysApplications', 'totalApplicationCount', 'completedApplicationsCount', 'pendingApplicationsCount','category'));
        } else {
            return view('agent.login');
        }
    }
    public function recharge(Request $request, $id)
    {
        $amount = $request->input('recharge_amount');

        // Retrieve the current balance of the agent
        $currentBalance = DB::table('agents')
            ->where('id', $id)
            ->value('balance');

        // Calculate the new balance after recharge
        $newBalance = $currentBalance + $amount;

        // Inserting data into the 'recharges' table 
        DB::table('recharges')->insert([
            'agent_id' => $id,
            'amount' => $amount,
            'balance_before' => $currentBalance,

        ]);

        // Update the balance in the database
        DB::table('agents')
            ->where('id', $id)
            ->update([
                'balance' => $newBalance
            ]);

        // Redirect back with a success message
        return back()->with('success', 'Recharge successful.');
    }
    public function rechargeHistory(Request $request)
    {
        if (Cookie::has('Agent_Session')) {
            // Retrieve and decrypt the agent's ID from the cookie
            $encryptedAgentId = Cookie::get('Agent_Session');
            $agentId = Crypt::decrypt($encryptedAgentId);

            $query = DB::table('recharges')
                ->where('agent_id', $agentId)
                ->orderBy("id", "desc");

            // Fetch paginated applications
            $recharges = $query->paginate(15);

            // Get sum of all spendings
            $spendings = DB::table('recharges')
                ->where('agent_id', $agentId)
                ->sum('amount');

            return view("agent.rechargeHistory", compact('recharges', 'spendings'));
        } else {
            return view('agent.login');
        }
    }

    public function profile()
    {
        // Check if the custom cookie exists
        if (Cookie::has('Agent_Session')) {
            // The cookie exists, proceed to the admin dashboard
            // Retrieve and decrypt the agent's ID from the cookie
            $encryptedAgentId = Cookie::get('Agent_Session');
            $agentId = Crypt::decrypt($encryptedAgentId);
            $agentData = DB::table('agents')->where('id', $agentId)->first();

            return view('agent.profile', compact('agentData'));
        } else {

            return view('agent.login');
        }
    }
}
