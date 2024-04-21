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
}
