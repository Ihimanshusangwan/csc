<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
                ->select("services.id","services.name", "service_groups.name as group_name", "service_groups.photo as group_photo")
                ->get()
                ->groupBy('service_group_id');
        } else {
            $services = DB::table("services")
                ->where("services.availability", 2)
                ->join("service_groups", "services.service_group_id", "=", "service_groups.id")
                ->select("services.id","services.name", "service_groups.name as group_name", "service_groups.photo as group_photo")
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
                // Filter completed applications
                $query->whereDate("a.delivery_date", "<=", today()->toDateString());
                break;
            case "pending":
                // Filter pending applications
                $query->whereDate("a.delivery_date", ">=", today()->toDateString())
                    ->orWhere('a.status', '!=', 2);
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
            ->where('applications.agent_id', $agent_id)->whereDate('delivery_date', '<=', today()->toDateString())
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
                        $prices = DB::table('prices')->where('location_id', $agent->location_id)->where('service_id', $service_id)->where('plan_id', $agent->plan_id)->first();
                        $defaultPrice = $prices->subscribed_default_govt_price + $prices->subscribed_default_commission_price + ($prices->subscribed_default_govt_price * $prices->subscribed_default_tax_percentage / 100);

                        $tatkalPrice = $prices->subscribed_tatkal_govt_price + $prices->subscribed_tatkal_commission_price + ($prices->subscribed_tatkal_govt_price * $prices->subscribed_tatkal_tax_percentage / 100);
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
                    $prices = DB::table('prices')->where('location_id', $agent->location_id)->where('service_id', $service_id)->where('plan_id', null)->first();
                    $defaultPrice = $prices->default_govt_price + $prices->default_commission_price + ($prices->default_govt_price * $prices->default_tax_percentage / 100);

                    $tatkalPrice = $prices->tatkal_govt_price + $prices->tatkal_commission_price + ($prices->tatkal_govt_price * $prices->tatkal_tax_percentage / 100);

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
        $prices = DB::table('prices')->where('location_id', $agent->location_id)->where('service_id', $service_id)->where('plan_id', null)->first();
        $defaultPrice = $prices->default_govt_price + $prices->default_commission_price + ($prices->default_govt_price * $prices->default_tax_percentage / 100);

        $tatkalPrice = $prices->tatkal_govt_price + $prices->tatkal_commission_price + ($prices->tatkal_govt_price * $prices->tatkal_tax_percentage / 100);

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
}
