<?php

namespace App\Models;

use Exception;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Agent extends Model
{
    protected $table = 'agents';

    public static function get_dashboard_data($agent_id): array
    {
        $plan = DB::table("agents")->select('plan_id', 'expiration_date', 'balance')->where("id", "=", $agent_id)->first();
        $balance = $plan->balance;
        $current_date = date('Y-m-d');

        if ($plan->expiration_date >= $current_date) {
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
                ->select("services.id", "services.name", "service_groups.name as group_name", "service_groups.photo as group_photo")
                ->get()
                ->groupBy('service_group_id');
        } else {
            $services = DB::table("services")
                ->where("services.availability", 2)
                ->join("service_groups", "services.service_group_id", "=", "service_groups.id")
                ->select("services.id", "services.name", "service_groups.name as group_name", "service_groups.photo as group_photo")
                ->get()
                ->groupBy('service_group_id');
        }

        // Format the services data for the view
        $service_groups = [];
        foreach ($services as $service_group_id => $grouped_services) {
            $service_groups[] = [
                'group_id' => $service_group_id,
                'group_name' => $grouped_services->first()->group_name,
                'group_photo' => $grouped_services->first()->group_photo,
                'services' => $grouped_services,
            ];
        }
        // Get sum of all price column
        $earnings = DB::table('applications')
            ->where('agent_id', $agent_id)
            ->sum('price');

        return [
            "service_groups" => $service_groups,
            "earnings" => $earnings,
            'balance' => $balance
        ];
    }
    public static function get_agent_profile_data($agent_id): ?array
    {
        $agent_data = DB::table('agents')
            ->where('agents.id', $agent_id)
            ->join('locations', 'locations.id', '=', 'agents.location_id')
            ->first();
        if ($agent_data) {
            if ($agent_data->plan_id) {
                $plan_data = DB::table('plans')->where('id', $agent_data->plan_id)->first();
            }
            $formatted_data = [
                "name" => $agent_data->full_name,
                "type" => $agent_data->type,
                "mobile" => $agent_data->mobile_number,
                "email" => $agent_data->email,
                "location" => [
                    "district" => $agent_data->district,
                    "state" => $agent_data->state,
                ],
                "address" => $agent_data->address,
                "shop_name" => $agent_data->shop_name,
                "shop_address" => $agent_data->shop_address,
                "documents" => [
                    "aadhar_card" => $agent_data->aadhar_card,
                    "shop_license" => $agent_data->shop_license,
                    "owner_photo" => $agent_data->owner_photo,
                    "supporting_documents" => $agent_data->supporting_document,
                ],
                "username" => $agent_data->username,
                "password" => $agent_data->password,
                "balance" => $agent_data->balance,
                "plan_name" => $plan_data->name,
                "plan_purchase_date" => $agent_data->purchase_date,
                "plan_expiration_date" => $agent_data->expiration_date,

            ];
            return $formatted_data;
        }

        return null;
    }
    public static function get_agent_applications_for_category($agent_id, $category, $offset, $limit, $order)
    {
        // Initialize the query
        $query = DB::table('applications as a')
            ->where('a.agent_id', $agent_id)
            ->join('customers', 'a.customer_id', '=', 'customers.id')
            ->join('services', 'a.service_id', '=', 'services.id')
            ->select(
                'a.id',
                'a.apply_date',
                'a.delivery_date',
                'a.form_data',
                'a.delivery as delivery_document',
                'a.price',
                'a.price_type',
                'a.status as status_id',
                'a.reason as rejection_reason',
                'services.name as service_name',
                'customers.name as customer_name',
                'customers.mobile as customer_mobile',
                DB::raw('(SELECT GROUP_CONCAT(CONCAT(id, ":", status_name, ":" , color)) FROM service_statuses WHERE service_statuses.service_id = a.service_id) as custom_statuses')
            )
            ->orderBy("a.id", $order);

        // Apply additional filters based on the category
        switch ($category) {
            case "today":
                // Filter applications applied today
                $query->whereDate("a.apply_date", "=", today()->toDateString());
                break;
            case "completed":
                $query->Where('applications.status', '==', 2);
                break;
            case "pending":
                $query->Where('applications.status', '!=', 2);

                break;
            default:
                // No additional filtering for other categories
                break;
        }


        // Apply offset and limit
        $query->offset($offset)->limit($limit);

        // Fetch paginated applications
        $applications = $query->get();

        // Get sum of all price column
        $sumOfPrices = $query->sum('price');

        $countOfTodaysApplications = DB::table('applications')
            ->where('agent_id', $agent_id)
            ->whereDate('apply_date', now()->toDateString())
            ->count();

        // Get total application count
        $totalApplicationCount = DB::table('applications')
            ->where('agent_id', $agent_id)
            ->count();

        // Get completed applications count which have delivery date less than today
        $completedApplicationsCount = DB::table('applications')
            ->where('applications.agent_id', $agent_id)->Where('applications.status', '==', 2)
            ->count();
        // Calculate pending applications count
        $pendingApplicationsCount = $totalApplicationCount - $completedApplicationsCount;

        // Return the results
        return [
            'applications' => $applications,
            'sumOfPrices' => $sumOfPrices,
            'countOfTodaysApplications' => $countOfTodaysApplications,
            'totalApplicationCount' => $totalApplicationCount,
            'completedApplicationsCount' => $completedApplicationsCount,
            'pendingApplicationsCount' => $pendingApplicationsCount,
            'category' => $category
        ];
    }

    public static function get_service_data($service_id, $agent_id): array
    {
        $service = DB::table('services')->where('id', $service_id)->first();
        if (!$service) {
            return [
                "success" => false,
                "message" => "Invalid Service ID"
            ];
        }
        if ($service->visibility === 1) {
            return [
                "success" => false,
                "message" => "Service is only available for appointments"
            ];
        }
        $agent = DB::table('agents')->where('id', $agent_id)->first();
        if ($service->availability === 1) {
            //paid service
            if ($agent->plan_id) {
                $is_applicable = DB::table('plan_services')->where('plan_id', $agent->plan_id)->where('service_id', $service_id)->first();
                if ($is_applicable) {
                    if ($agent->expiration_date >= now()->toDateString()) {
                        try {
                            $prices = DB::table('prices')
                                ->where('location_id', $agent->location_id)
                                ->where('service_id', $service_id)
                                ->where('plan_id', $agent->plan_id)
                                ->first();

                            if ($prices) {
                                $defaultPrice = $prices->subscribed_default_govt_price + $prices->subscribed_default_commission_price + ($prices->subscribed_default_govt_price * $prices->subscribed_default_tax_percentage / 100);

                                $tatkalPrice = $prices->subscribed_tatkal_govt_price + $prices->subscribed_tatkal_commission_price + ($prices->subscribed_tatkal_govt_price * $prices->subscribed_tatkal_tax_percentage / 100);
                            } else {
                                throw new Exception("Prices not configured for service, contact Admin");
                            }
                        } catch (Exception $e) {
                            return [
                                "success" => false,
                                "message" => $e->getMessage()
                            ];
                        }

                        //active plan
                        $data = [
                            "agent_balance" => $agent->balance,
                            "default_price" => $defaultPrice,
                            "tatkal_price" => $tatkalPrice,
                            "service_id" => $service_id,
                            "service_name" => $service->name,
                            "document_requirements" => explode(',', $service->requirements),
                            "form" => json_decode($service->form)

                        ];
                        return [
                            "success" => true,
                            "data" => $data
                        ];
                    }
                    //expired plan
                    try {
                        $prices = DB::table('prices')->where('location_id', $agent->location_id)->where('service_id', $service_id)->where('plan_id', null)->first();
                        if ($prices) {
                            $defaultPrice = $prices->default_govt_price + $prices->default_commission_price + ($prices->default_govt_price * $prices->default_tax_percentage / 100);

                            $tatkalPrice = $prices->tatkal_govt_price + $prices->tatkal_commission_price + ($prices->tatkal_govt_price * $prices->tatkal_tax_percentage / 100);
                        } else {
                            throw new Exception("Prices not configured for service, contact Admin");
                        }
                    } catch (Exception $e) {
                        return [
                            "success" => false,
                            "message" => $e->getMessage()
                        ];
                    }

                    $data = [
                        "agent_balance" => $agent->balance,
                        "default_price" => $defaultPrice,
                        "tatkal_price" => $tatkalPrice,
                        "service_id" => $service_id,
                        "service_name" => $service->name,
                        "document_requirements" => explode(',', $service->requirements),
                        "form" => json_decode($service->form)

                    ];
                    return [
                        "success" => true,
                        "data" => $data
                    ];
                } else {
                    return [
                        "success" => false,
                        "message" => "Agent doesn't have this service included in his plan"
                    ];
                }
            }
            return [
                "success" => false,
                "message" => "Agent doesn't have purchased any plan yet"
            ];
        }
        //free service
        try {
            $prices = DB::table('prices')->where('location_id', $agent->location_id)->where('service_id', $service_id)->where('plan_id', null)->first();
            if ($prices) {

                $defaultPrice = $prices->default_govt_price + $prices->default_commission_price + ($prices->default_govt_price * $prices->default_tax_percentage / 100);

                $tatkalPrice = $prices->tatkal_govt_price + $prices->tatkal_commission_price + ($prices->tatkal_govt_price * $prices->tatkal_tax_percentage / 100);
            } else {
                throw new Exception("Prices not configured for service, contact Admin");
            }
        } catch (Exception $e) {
            return [
                "success" => false,
                "message" => $e->getMessage()
            ];
        }

        $data = [
            "agent_balance" => $agent->balance,
            "default_price" => $defaultPrice,
            "tatkal_price" => $tatkalPrice,
            "service_id" => $service_id,
            "service_name" => $service->name,
            "document_requirements" => explode(',', $service->requirements),
            "form" => json_decode($service->form)

        ];
        return [
            "success" => true,
            "data" => $data
        ];
    }
    public static function store_service_data($service_id, $agent_id, $data , Request $request): array
    {
        $service = DB::table('services')->where('id', $service_id)->first();
        if (!$service) {
            return [
                "success" => false,
                "message" => "Invalid Service ID"
            ];
        }
        if ($service->visibility === 1) {
            return [
                "success" => false,
                "message" => "Service is only available for appointments"
            ];
        }
        $agent = DB::table('agents')->where('id', $agent_id)->first();
        
        if ($service->availability === 1) {
            //paid service
            if ($agent->plan_id) {
                $is_applicable = DB::table('plan_services')->where('plan_id', $agent->plan_id)->where('service_id', $service_id)->first();
                if ($is_applicable) {
                    if ($agent->expiration_date >= now()->toDateString()) {
                        try {
                            $prices = DB::table('prices')
                                ->where('location_id', $agent->location_id)
                                ->where('service_id', $service_id)
                                ->where('plan_id', $agent->plan_id)
                                ->first();

                            if ($prices) {
                                $defaultPrice = $prices->subscribed_default_govt_price + $prices->subscribed_default_commission_price + ($prices->subscribed_default_govt_price * $prices->subscribed_default_tax_percentage / 100);

                                $tatkalPrice = $prices->subscribed_tatkal_govt_price + $prices->subscribed_tatkal_commission_price + ($prices->subscribed_tatkal_govt_price * $prices->subscribed_tatkal_tax_percentage / 100);
                            } else {
                                throw new Exception("Prices not configured for service, contact Admin");
                            }
                        } catch (Exception $e) {
                            return [
                                "success" => false,
                                "message" => $e->getMessage()
                            ];
                        }
                         $price_type = isset($data['price_type']) ? $data['price_type'] : null;
        $customer_name = isset($data['customer_name']) ? $data['customer_name'] : null;
        $customer_number = isset($data['customer_number']) ? $data['customer_number'] : null;
        if(!$customer_name || !$customer_number){
            return [
                "success" => false,
                "message" => 'Customer Name and Number are required'
            ];
        }

                        if ($price_type) {
                            if ($price_type == "default") {
                                $totalPrice = $defaultPrice;
                                $govtPrice = $prices->subscribed_default_govt_price;
                                $commission = $prices->subscribed_default_commission_price;
                                $tax =  ($prices->subscribed_default_govt_price * $prices->subscribed_default_tax_percentage / 100);
                            } else {
                                $totalPrice = $tatkalPrice;
                                $govtPrice = $prices->subscribed_tatkal_govt_price;
                                $commission = $prices->subscribed_tatkal_commission_price;
                                $tax = ($prices->subscribed_tatkal_govt_price * $prices->subscribed_tatkal_tax_percentage / 100);
                            }
                            $locationId = $agent->location_id;
                            $serviceGroupId = $service->service_group_id;
                            $nextStaffId = Staff::get_staff_id($serviceGroupId, $locationId);


                            try {
                                // Start a transaction
                                DB::beginTransaction();

                                // Create a new customer record
                                $customerId = DB::table('customers')->insertGetId([
                                    'name' => $data['customer_name'],
                                    'mobile' => $data['customer_number'],
                                    'agent_id' => $agent_id,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                                // Get all form input data excluding specific fields
                                $formData = $data['form_data'];

                                $filePaths = [];
                                // Loop through the uploaded files
                                foreach ($request->allFiles() as $fieldName => $files) {
                                    // If there's only one file, convert it to an array to simplify processing
                                    if (!is_array($files)) {
                                        $files = [$files];
                                    }

                                    foreach ($files as $file) {
                                        // Generate a unique filename
                                        $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();

                                        // Move the uploaded file to the desired directory
                                        $file->move(public_path('uploads/applications'), $fileName);

                                        // Add the file name and path to the array with input name as key
                                        $filePaths[$fieldName] = 'uploads/applications/' . $fileName;
                                    }
                                }

                                // Convert the array to JSON
                                $formDataJson = json_encode([
                                    'formData' => $formData,
                                    'filePaths' => $filePaths
                                ]);
                                //update balance of agent
                                // Retrieve the current balance of the agent
                                $currentBalance = $agent->balance;

                                // Calculate the new balance after recharge
                                $newBalance = $currentBalance - $totalPrice;

                                // Update the balance in the database
                                DB::table('agents')
                                    ->where('id', $agent_id)
                                    ->update([
                                        'balance' => $newBalance
                                    ]);

                                // Create a new application record
                                DB::table('applications')->insert([
                                    'agent_id' => $agent_id,
                                    'customer_id' => $customerId,
                                    'service_id' => $service_id,
                                    'form_data' => $formDataJson,
                                    'price' => $totalPrice,
                                    'location_id' => $locationId,
                                    'service_group_id' => $serviceGroupId,
                                    'staff_id' => $nextStaffId,
                                    'price_type' => $price_type,
                                    'govt_price' => $govtPrice,
                                    'commission' => $commission,
                                    'is_agent_subscribed' => true,
                                    'tax' => $tax,
                                    'apply_date' => now(),
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);

                                // Commit the transaction
                                DB::commit();
                                return [
                                    "success" => true,
                                    "message" => "Successfully Applied "
                                ];
                            } catch (Exception $e) {
                                // If an exception occurs, rollback the transaction
                                DB::rollback();

                                // Handle the exception, log it, etc.
                                return [
                                    "success" => false,
                                    "message" => "Unexpected Error occured while Applying"
                                ];
                            }
                        }

                        return [
                            "success" => false,
                            "message" => "Price Type not sent"
                        ];
                    }
                    //expired plan
                    try {
                        $prices = DB::table('prices')->where('location_id', $agent->location_id)->where('service_id', $service_id)->where('plan_id', null)->first();
                        if ($prices) {
                            $defaultPrice = $prices->default_govt_price + $prices->default_commission_price + ($prices->default_govt_price * $prices->default_tax_percentage / 100);

                            $tatkalPrice = $prices->tatkal_govt_price + $prices->tatkal_commission_price + ($prices->tatkal_govt_price * $prices->tatkal_tax_percentage / 100);
                        } else {
                            throw new Exception("Prices not configured for service, contact Admin");
                        }
                    } catch (Exception $e) {
                        return [
                            "success" => false,
                            "message" => $e->getMessage()
                        ];
                    }
                     $price_type = isset($data['price_type']) ? $data['price_type'] : null;
        $customer_name = isset($data['customer_name']) ? $data['customer_name'] : null;
        $customer_number = isset($data['customer_number']) ? $data['customer_number'] : null;
        if(!$customer_name || !$customer_number){
            return [
                "success" => false,
                "message" => 'Customer Name and Number are required'
            ];
        }

                    if ($price_type) {
                        if ($price_type == "default") {
                            $totalPrice = $defaultPrice;
                            $govtPrice = $prices->default_govt_price;
                            $commission = $prices->default_commission_price;
                            $tax =  ($prices->default_govt_price * $prices->default_tax_percentage / 100);
                        } else {
                            $totalPrice = $tatkalPrice;
                            $govtPrice = $prices->tatkal_govt_price;
                            $commission = $prices->tatkal_commission_price;
                            $tax = ($prices->tatkal_govt_price * $prices->tatkal_tax_percentage / 100);
                        }
                        $locationId = $agent->location_id;
                        $serviceGroupId = $service->service_group_id;
                        $nextStaffId = Staff::get_staff_id($serviceGroupId, $locationId);


                        try {
                            // Start a transaction
                            DB::beginTransaction();

                            // Create a new customer record
                            $customerId = DB::table('customers')->insertGetId([
                                'name' => $data['customer_name'],
                                'mobile' => $data['customer_number'],
                                'agent_id' => $agent_id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                            // Get all form input data excluding specific fields
                            $formData = $data['form_data'];

                            $filePaths = [];
                            // Loop through the uploaded files
                            foreach ($request->allFiles() as $fieldName => $files) {
                                // If there's only one file, convert it to an array to simplify processing
                                if (!is_array($files)) {
                                    $files = [$files];
                                }

                                foreach ($files as $file) {
                                    // Generate a unique filename
                                    $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();

                                    // Move the uploaded file to the desired directory
                                    $file->move(public_path('uploads/applications'), $fileName);

                                    // Add the file name and path to the array with input name as key
                                    $filePaths[$fieldName] = 'uploads/applications/' . $fileName;
                                }
                            }

                            // Convert the array to JSON
                            $formDataJson = json_encode([
                                'formData' => $formData,
                                'filePaths' => $filePaths
                            ]);
                            //update balance of agent
                            // Retrieve the current balance of the agent
                            $currentBalance = $agent->balance;

                            // Calculate the new balance after recharge
                            $newBalance = $currentBalance - $totalPrice;

                            // Update the balance in the database
                            DB::table('agents')
                                ->where('id', $agent_id)
                                ->update([
                                    'balance' => $newBalance
                                ]);

                            // Create a new application record
                            DB::table('applications')->insert([
                                'agent_id' => $agent_id,
                                'customer_id' => $customerId,
                                'service_id' => $service_id,
                                'form_data' => $formDataJson,
                                'price' => $totalPrice,
                                'location_id' => $locationId,
                                'service_group_id' => $serviceGroupId,
                                'staff_id' => $nextStaffId,
                                'price_type' => $price_type,
                                'govt_price' => $govtPrice,
                                'commission' => $commission,
                                'is_agent_subscribed' => true,
                                'tax' => $tax,
                                'apply_date' => now(),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);

                            // Commit the transaction
                            DB::commit();
                            return [
                                "success" => true,
                                "message" => "Successfully Applied "
                            ];
                        } catch (Exception $e) {
                            // If an exception occurs, rollback the transaction
                            DB::rollback();

                            // Handle the exception, log it, etc.
                            return [
                                "success" => false,
                                "message" => "Unexpected Error occured while Applying"
                            ];
                        }
                    }

                    return [
                        "success" => false,
                        "message" => "Price Type not sent"
                    ];
                } else {
                    return [
                        "success" => false,
                        "message" => "Agent doesn't have this service included in his plan"
                    ];
                }
            }
            return [
                "success" => false,
                "message" => "Agent doesn't have purchased any plan yet"
            ];
        }
        //free service
        try {
            $prices = DB::table('prices')->where('location_id', $agent->location_id)->where('service_id', $service_id)->where('plan_id', null)->first();
            if ($prices) {

                $defaultPrice = $prices->default_govt_price + $prices->default_commission_price + ($prices->default_govt_price * $prices->default_tax_percentage / 100);

                $tatkalPrice = $prices->tatkal_govt_price + $prices->tatkal_commission_price + ($prices->tatkal_govt_price * $prices->tatkal_tax_percentage / 100);
            } else {
                throw new Exception("Prices not configured for service, contact Admin");
            }
        } catch (Exception $e) {
            return [
                "success" => false,
                "message" => $e->getMessage()
            ];
        }
         $price_type = isset($data['price_type']) ? $data['price_type'] : null;
        $customer_name = isset($data['customer_name']) ? $data['customer_name'] : null;
        $customer_number = isset($data['customer_number']) ? $data['customer_number'] : null;
        if(!$customer_name || !$customer_number){
            return [
                "success" => false,
                "message" => 'Customer Name and Number are required'
            ];
        }
        $customer_name = isset($data['customer_name']) ? $data['customer_name'] : null;
        $customer_number = isset($data['customer_number']) ? $data['customer_number'] : null;
        if(!$customer_name || !$customer_number){
            return [
                "success" => false,
                "message" => 'Customer Name and Number are required'
            ];
        }

        if ($price_type) {
            if ($price_type == "default") {
                $totalPrice = $defaultPrice;
                $govtPrice = $prices->default_govt_price;
                $commission = $prices->default_commission_price;
                $tax =  ($prices->default_govt_price * $prices->default_tax_percentage / 100);
            } else {
                $totalPrice = $tatkalPrice;
                $govtPrice = $prices->tatkal_govt_price;
                $commission = $prices->tatkal_commission_price;
                $tax = ($prices->tatkal_govt_price * $prices->tatkal_tax_percentage / 100);
            }
            $locationId = $agent->location_id;
            $serviceGroupId = $service->service_group_id;
            $nextStaffId = Staff::get_staff_id($serviceGroupId, $locationId);


            try {
                // Start a transaction
                DB::beginTransaction();

                // Create a new customer record
                $customerId = DB::table('customers')->insertGetId([
                    'name' => $data['customer_name'],
                    'mobile' => $data['customer_number'],
                    'agent_id' => $agent_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                // Get all form input data excluding specific fields
                $formData = $data['form_data'];

                $filePaths = [];
                // Loop through the uploaded files
                foreach ($request->allFiles() as $fieldName => $files) {
                    // If there's only one file, convert it to an array to simplify processing
                    if (!is_array($files)) {
                        $files = [$files];
                    }

                    foreach ($files as $file) {
                        // Generate a unique filename
                        $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();

                        // Move the uploaded file to the desired directory
                        $file->move(public_path('uploads/applications'), $fileName);

                        // Add the file name and path to the array with input name as key
                        $filePaths[$fieldName] = 'uploads/applications/' . $fileName;
                    }
                }

                // Convert the array to JSON
                $formDataJson = json_encode([
                    'formData' => $formData,
                    'filePaths' => $filePaths
                ]);
                //update balance of agent
                // Retrieve the current balance of the agent
                $currentBalance = $agent->balance;

                // Calculate the new balance after recharge
                $newBalance = $currentBalance - $totalPrice;

                // Update the balance in the database
                DB::table('agents')
                    ->where('id', $agent_id)
                    ->update([
                        'balance' => $newBalance
                    ]);

                // Create a new application record
                DB::table('applications')->insert([
                    'agent_id' => $agent_id,
                    'customer_id' => $customerId,
                    'service_id' => $service_id,
                    'form_data' => $formDataJson,
                    'price' => $totalPrice,
                    'location_id' => $locationId,
                    'service_group_id' => $serviceGroupId,
                    'staff_id' => $nextStaffId,
                    'price_type' => $price_type,
                    'govt_price' => $govtPrice,
                    'commission' => $commission,
                    'is_agent_subscribed' => false,
                    'tax' => $tax,
                    'apply_date' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Commit the transaction
                DB::commit();
                return [
                    "success" => true,
                    "message" => "Successfully Applied "
                ];
            } catch (Exception $e) {
                // If an exception occurs, rollback the transaction
                DB::rollback();

                // Handle the exception, log it, etc.
                return [
                    "success" => false,
                    "message" => "Unexpected Error occured while Applying"
                ];
            }
        }

        return [
            "success" => false,
            "message" => "Price Type not sent"
        ];
    }
}
