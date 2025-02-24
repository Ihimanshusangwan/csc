<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

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
        $referral_code = $request->input('referral_code') ? $request->input('referral_code') : null;
        $location_id = $request->input('location_id');
        $password = $request->input('password');
        // Additional fields for the payment section
        $paymentStatus = ($type == 'Register') ? $request->input('payment_status') : null;
        $paymentMode = ($type == 'Register') ? $request->input('payment_mode') : null;
        $paidAmount = ($type == 'Register') ? $request->input('paid_amount') : null;
        $unpaidAmount = ($type == 'Register') ? $request->input('unpaid_amount') : null;

        $existingUsername = DB::table('users')->where('username', $username)->exists();

        // If the username already exists, return an error response
        if ($existingUsername) {
            return redirect()->back()->withInput()->with(['error' => 'The username is already taken. Please choose a different one.']);
        }
        if ($referral_code) {
            $fieldboy = DB::table('fieldboys')->where('referal_code', $referral_code)->exists();
            if (!$fieldboy) {
                return redirect()->back()->withInput()->with(['error' => 'The referral code is invalid']);
            }
        }
        if ($plan_id) {
            $planDuration = DB::table('plans')->where('id', '=', $plan_id)
                ->select('duration')
                ->get()[0]->duration;

            $expirationDate = now()->addDays($planDuration)->toDateString();
        } else {
            $expirationDate = null;
        }


        $aadharPath = $this->uploadFile($request->file('upload_aadhar'), 'aadhar');
        $shopLicensePath = $this->uploadFile($request->file('upload_shop_license'), 'shop_license');
        $ownerPhotoPath = $this->uploadFile($request->file('upload_owner_photo'), 'owner_photo');
        $uploadSupportingDocumentPath = $this->uploadFile($request->file('uploadSupportingDocument'), 'supporting_document');
        $user = User::create([
            'username' => $username,
            'password' => Hash::make($password),
            'name' => $fullName,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $AgentRoleId = Role::where('name', 'agent')->value('id');
        $user->roles()->attach($AgentRoleId);
        $userId = $user->id;

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
            'reg_date' => now(),
            'expiration_date' => $expirationDate,
            'referral_code' => $referral_code,
            'user_id' => $userId,
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
            ->where('agents.is_deleted', 0)
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
            ->where('is_deleted', 0)
            ->first();

        if ($agent && $credentials['password'] == $agent->password) {
            // Authentication passed for agent

            // Get the session lifetime from the configuration
            $sessionLifetime = config('session.lifetime');


            // Encrypt the agent's ID before storing it in the cookie
            $encryptedAgentId = Crypt::encrypt($agent->id);
            $cookie1 = cookie('Agent_Session', $encryptedAgentId, $sessionLifetime);
            $to_show_recharge_alert = $agent->balance < 80;
            $cookie2 = cookie('recharge_alert', $to_show_recharge_alert, $sessionLifetime);

            // Redirect with the custom cookies
            return redirect()->intended('/agent/dashboard')->withCookies([$cookie1, $cookie2]);
        } else {
            // Authentication failed for agent
            return back()->withErrors(['username' => 'Invalid credentials'])->withInput($request->only('username'));
        }
    }
    public function index()
    {
        // Check if the custom cookie exists
        if (Cookie::has('Agent_Session')) {
            $encryptedAgentId = Cookie::get('Agent_Session');
            $agentId = Crypt::decrypt($encryptedAgentId);
            // Find the corresponding agent by their Id
            $plan = DB::table("agents")->select('plan_id', 'expiration_date')->where("id", "=", $agentId)->first();
            $currentDate = date('Y-m-d'); // Get the current date in the format YYYY-MM-DD

            if ($plan->expiration_date >= $currentDate) {
                $services = DB::table("services")
                    ->leftJoin("plan_services", "services.id", "=", "plan_services.service_id")
                    ->where("plan_services.plan_id", "=", $plan->plan_id)
                    ->orWhere("services.availability", 2)
                    ->join("service_groups", "services.service_group_id", "=", "service_groups.id")
                    ->select("services.*", "service_groups.name as group_name", "service_groups.photo as group_photo")
                    ->distinct()
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
                ->where('applications.is_approved', '=', 1)
                ->where('agent_id', $agentId)
                ->sum('price');
            //agent balance
            $balance = DB::table('agents')
                ->where('id', $agentId)
                ->first(["balance"])->balance;
            $recharge_alert = false;
            if (Cookie::has('recharge_alert')) {
                $recharge_alert = Cookie::get('recharge_alert');
            }
            $cookie = cookie('recharge_alert', null, -1);
            // Pass data to the view using compact, including the decrypted agent ID
            $response = response()->view('agent.dashboard', compact('serviceGroups', 'sumOfPrices', 'balance', 'recharge_alert'));
            return $response->withCookie($cookie);
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

    public function applications(Request $request, $category)
    {
        if (Cookie::has('Agent_Session')) {
            // Retrieve and decrypt the agent's ID from the cookie
            $encryptedAgentId = Cookie::get('Agent_Session');
            $agentId = Crypt::decrypt($encryptedAgentId);
            $services = DB::table('services')->where("is_active", 1)->get();
            $statuses = DB::table('service_statuses')->select('id', 'status_name')->get();

            $dateFrom = $request->input('dateFrom');
            $dateTo = $request->input('dateTo');
            $status = $request->input('status');
            $applicantName = $request->input('applicantName');
            $applicantNumber = $request->input('applicantNumber');
            $service = $request->input('services');
            $price_type = $request->input('price_type');

            $query = DB::table('applications')
                ->where('applications.agent_id', $agentId)
                ->join('customers', 'applications.customer_id', '=', 'customers.id')
                ->join('services', 'applications.service_id', '=', 'services.id')
                ->where('applications.is_approved', '=', 1)
                ->select(
                    'applications.*',
                    'services.name as service_name',
                    'customers.name as customer_name',
                    'customers.mobile as customer_mobile',
                    DB::raw('(SELECT GROUP_CONCAT(CONCAT(id, ":", status_name, ":" , color , ":" , ask_reason)) FROM service_statuses WHERE service_statuses.service_id = applications.service_id) as statuses')
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
                    $query->Where('applications.status', '=', 2);
                    break;
                case "pending":
                    $query->Where('applications.status', '!=', 2);

                    break;
            }
            // Apply filters conditionally based on input values
            if ($dateFrom) {
                $query->where('applications.apply_date', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->where('applications.apply_date', '<=', $dateTo);
            }
            if ($service) {
                $query->where('services.id', $service);
            }
            if ($applicantName) {
                $query->where('customers.name', 'like', '%' . $applicantName . '%');
            }
            if ($applicantNumber) {
                $query->where('customers.mobile', 'like', '%' . $applicantNumber . '%');
            }
            if ($status !== null && $status !== '') {
                $query->where('applications.status', '=', $status);
            }
            if ($price_type) {
                $query->where('price_type', '=', $price_type);
            }

            // Fetch paginated applications
            $applications = $query->paginate(15);

            // Get sum of all price column
            $sumOfPrices = $query->sum('price');

            // Get count of today's applications
            $countOfTodaysApplications = DB::table('applications')
                ->where('agent_id', $agentId)
                ->where('applications.is_approved', '=', 1)
                ->whereDate('apply_date', now()->toDateString())
                ->count();

            // Get total application count
            $totalApplicationCount = DB::table('applications')
                ->where('agent_id', $agentId)
                ->where('applications.is_approved', '=', 1)
                ->count();

            // Get completed applications count which have delivery date less than today
            $completedApplicationsCount = DB::table('applications')
                ->where('applications.agent_id', $agentId)->Where('applications.status', '=', 2)
                ->where('applications.is_approved', '=', 1)
                ->count();


            // Calculate pending applications count
            $pendingApplicationsCount = $totalApplicationCount - $completedApplicationsCount;

            return view("agent.applications", compact('applications', 'sumOfPrices', 'countOfTodaysApplications', 'totalApplicationCount', 'completedApplicationsCount', 'pendingApplicationsCount', 'category', 'services', 'statuses'));
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
            'created_at' => now(),
            'updated_at' => now(),

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
    public function delete(Request $request, $id)
    {
        $reason = $request->input('delete_reason');
        DB::table('agents')
            ->where('id', $id)
            ->update([
                'is_deleted' => 1,
                'delete_reason' => $reason,
            ]);

        return back()->with('success', 'Deleted successful.');
    }
    public function update_plan(Request $request, $id)
    {
        $plan_id = $request->input('plan_id');
        $planDuration = DB::table('plans')->where('id', '=', $plan_id)
            ->select('duration')
            ->get()[0]->duration;

        $expirationDate = now()->addDays($planDuration)->toDateString();

        DB::table('agents')
            ->where('id', $id)
            ->update([

                'plan_id' => $plan_id,
                'purchase_date' => now()->toDateString(),
                'expiration_date' => $expirationDate
            ]);

        // Redirect back with a success message
        return back()->with('success', 'Plan Updated Successfully ');
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

    public function application_requests(Request $request)
    {
        if (Cookie::has('Agent_Session')) {
            $encryptedAgentId = Cookie::get('Agent_Session');
            $agentId = Crypt::decrypt($encryptedAgentId);
            $query = DB::table('applications')
                ->where('applications.agent_id', $agentId)
                ->join('customers', 'applications.customer_id', '=', 'customers.id')
                ->join('services', 'applications.service_id', '=', 'services.id')
                ->where('applications.is_applicant_customer', '=', 1)
                ->where('applications.is_approved', '=', 0)
                ->select(

                    'applications.*',
                    'services.name as service_name',
                    'customers.name as customer_name',
                    'customers.mobile as customer_mobile',
                )
                ->orderBy("applications.id", "desc");
            $applications = $query->paginate(15);

            return view('agent.applicationRequests', compact('applications'));
        } else {

            return view('agent.login');
        }
    }

    public function update_application(Request $request)
    {
        if (Cookie::has('Agent_Session')) {
            $encryptedAgentId = Cookie::get('Agent_Session');
            $agentId = Crypt::decrypt($encryptedAgentId);
            $data = DB::table("agents")->select('location_id', 'expiration_date', 'plan_id')->where("id", "=", $agentId)->first();
            $currentDate = date('Y-m-d');
            $locationId = $data->location_id;
            $totalPrice = 0;
            $govtPrice = 0;
            $commission = 0;
            $tax = 0;
            $is_agent_subscribed = false;
            $priceType = $request->input('price_type');
            $reason = $request->input('reason');
            $id = $request->input('id');
            if ($reason) {
                DB::table('applications')->update([
                    'price' => 0,
                    'price_type' => $priceType,
                    'govt_price' => 0,
                    'commission' => 0,
                    'is_agent_subscribed' => $is_agent_subscribed,
                    'is_approved' => 1,
                    'reason' => $reason,
                    'status' => -1,
                    'tax' => 0,
                    'apply_date' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return back()->with('success', 'Rejected Successfully');
            }
            $service_id = DB::table('applications')->where('id', $id)->value('service_id');
            if ($data->plan_id && $data->expiration_date >= $currentDate) {
                $is_agent_subscribed = true;
                // active plan check
                $prices = DB::table('prices')->where('location_id', $locationId)->where('service_id', $service_id)->where('plan_id', $data->plan_id)->first();
                if ($priceType === 'default') {
                    $totalPrice =  $prices->subscribed_default_govt_price + $prices->subscribed_default_commission_price + ($prices->subscribed_default_govt_price * $prices->subscribed_default_tax_percentage / 100);
                    $govtPrice = $prices->subscribed_default_govt_price;
                    $commission = $prices->subscribed_default_commission_price;
                    $tax =  ($prices->subscribed_default_govt_price * $prices->subscribed_default_tax_percentage / 100);
                } else {
                    $totalPrice = $prices->subscribed_tatkal_govt_price + $prices->subscribed_tatkal_commission_price + ($prices->subscribed_tatkal_govt_price * $prices->subscribed_tatkal_tax_percentage / 100);
                    $govtPrice = $prices->subscribed_tatkal_govt_price;
                    $commission = $prices->subscribed_tatkal_commission_price;
                    $tax = ($prices->subscribed_tatkal_govt_price * $prices->subscribed_tatkal_tax_percentage / 100);
                }
            } else {
                //free plan
                $prices = DB::table('prices')->where('location_id', $locationId)->where('service_id', $service_id)->where('plan_id', null)->first();

                if ($priceType === 'default') {
                    $totalPrice = $prices->default_govt_price + $prices->default_commission_price + ($prices->default_govt_price * $prices->default_tax_percentage / 100);
                    $govtPrice = $prices->default_govt_price;
                    $commission = $prices->default_commission_price;
                    $tax =  ($prices->default_govt_price * $prices->default_tax_percentage / 100);
                } else {
                    $totalPrice = $prices->tatkal_govt_price + $prices->tatkal_commission_price + ($prices->tatkal_govt_price * $prices->tatkal_tax_percentage / 100);
                    $govtPrice = $prices->tatkal_govt_price;
                    $commission = $prices->tatkal_commission_price;
                    $tax = ($prices->tatkal_govt_price * $prices->tatkal_tax_percentage / 100);
                }
            }

            //update balance of agent
            // Retrieve the current balance of the agent
            $currentBalance = DB::table('agents')
                ->where('id', $agentId)
                ->value('balance');

            // Calculate the new balance after recharge
            $newBalance = $currentBalance - $totalPrice;
            if ($newBalance < 1) {

                return back()->with('error', 'Insufficient Balance');
            }

            // Update the balance in the database
            DB::table('agents')
                ->where('id', $agentId)
                ->update([
                    'balance' => $newBalance
                ]);

            DB::table('applications')->update([
                'price' => $totalPrice,
                'price_type' => $priceType,
                'govt_price' => $govtPrice,
                'commission' => $commission,
                'is_agent_subscribed' => $is_agent_subscribed,
                'is_approved' => 1,
                'tax' => $tax,
                'apply_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);





            return back()->with('success', 'Approved Successfully');
        } else {

            return view('agent.login');
        }
    }
}
