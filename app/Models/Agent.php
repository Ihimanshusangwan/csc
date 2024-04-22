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
}
