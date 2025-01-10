<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Staff extends Model
{
    public static function get_staff_id($service_id, $location_id): ?int
    {
        // Retrieve the latest assigned staff ID from the applications table for the given location
        $latest_staff_id = DB::table('applications')
            ->where('location_id', $location_id)
            ->latest()
            ->value('staff_id');

        // Retrieve all staff IDs linked to the specified service and location
        $staff_ids = DB::table('staffs_services')
            ->join('staff', 'staffs_services.staff_id', '=', 'staff.id')
            ->where('staff.location_id', $location_id)
            ->where('staffs_services.service_id', $service_id)
            ->pluck('staff.id')
            ->toArray();

        // Check if the staff IDs array is empty
        if (count($staff_ids) > 0) {
            // Find the index of the latest staff ID in the array
            $last_staff_id_index = array_search($latest_staff_id, $staff_ids);

            if ($last_staff_id_index === false) {
                // If the latest staff ID is not found, default to the first staff in the list
                return $staff_ids[0];
            }

            // Calculate the index of the next staff ID
            $next_staff_id_index = ($last_staff_id_index + 1) % count($staff_ids);

            // Return the next staff ID
            return $staff_ids[$next_staff_id_index];
        }

        // Return null if no staff is available
        return null;
    }
}
