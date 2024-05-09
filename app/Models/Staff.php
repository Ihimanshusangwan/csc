<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Staff extends Model
{
    public static function get_staff_id($service_group_id, $location_id): ?int
    {
        $latest_staff_id = DB::table('applications')
            ->where('service_group_id', $service_group_id)
            ->where('location_id', $location_id)
            ->latest()
            ->value('staff_id');

        // Retrieve all staff IDs
        $staff_ids = DB::table('staff')
            ->where('location_id', $location_id)
            ->where('service_group_id', $service_group_id)
            ->pluck('id')
            ->toArray();

        // Check if the staff IDs array is empty
        if (count($staff_ids) > 0) {
            // Find the index of the last staff ID in the array
            $last_staff_id_index = array_search($latest_staff_id, $staff_ids);

            // Calculate the index of the next staff ID
            $next_staff_id_index = ($last_staff_id_index + 1) % count($staff_ids);

            // Get the next staff ID
            return $staff_ids[$next_staff_id_index];
        } else {
            return null;
        }
    }
}
