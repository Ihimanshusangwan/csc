<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffManagerController extends Controller
{
    public function dashboard(Request $request)
    {
        // Initialize the query
        $query = DB::table('applications')
            ->leftJoin('staff', 'applications.staff_id', '=', 'staff.id')
            ->leftJoin('customers', 'applications.customer_id', '=', 'customers.id')
            ->leftJoin('services', 'applications.service_id', '=', 'services.id')
            ->leftJoin('agents', 'applications.agent_id', '=', 'agents.id')
            ->select(
                'applications.id',
                'applications.created_at',
                'applications.status',
                'applications.delivery_date',
                'customers.name as customer_name',
                'customers.mobile as customer_mobile',
                'services.name as service_name',
                'agents.full_name as agent_name',
                'agents.shop_name as shop_name',
                'staff.name as staff_name',
                'staff.id as staff_id',
                DB::raw('(SELECT GROUP_CONCAT(CONCAT(id, ":", status_name, ":" , color , ":" , ask_reason)) FROM service_statuses WHERE service_statuses.service_id = applications.service_id) as statuses')
            );

        // Apply filters if provided
        if ($request->filled('customer_name')) {
            $query->where('customers.name', 'like', '%' . $request->customer_name . '%');
        }

        if ($request->filled('date')) {
            $query->whereDate('applications.created_at', $request->date);
        }

        // Fetch applications and their assigned staff
        $applications = $query->orderBy('applications.id', 'desc')->paginate(15);

        // Fetch all staff members for the dropdown
        $staff = DB::table('staff')->select('id', 'name')->get();

        // Pass data to the view using compact
        return view('staff_manager.dashboard', compact('applications', 'staff'));
    }

    public function updateStaff(Request $request)
    {
        $request->validate([
            'application_id' => 'required|integer',
            'staff_id' => 'required|integer',
        ]);

        DB::table('applications')
            ->where('id', $request->application_id)
            ->update(['staff_id' => $request->staff_id]);

        return redirect()->route('staff_manager.dashboard')->with('success', 'Staff updated successfully.');
    }

}
