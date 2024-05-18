<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{
    public function index($service_id)
    {
        // Retrieve all status records for a specific service_id from the database
        $statuses = DB::table('service_statuses')
                    ->where('service_id', $service_id)
                    ->orderBy('id','desc')
                    ->get();
        
        // Return the view with the retrieved status records and service_id
        return view('admin.serviceStatuses', ['statuses' => $statuses, 'service_id' => $service_id]);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'status_name' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'service_id' => 'required',
            'ask_reason' => 'required',
        ]);
    
        // Insert the new status into the database
        DB::table('service_statuses')->insert([
            'status_name' => $validatedData['status_name'],
            'color' => $validatedData['color'],
            'service_id' => $validatedData['service_id'],
            'ask_reason' => $validatedData['ask_reason'],
        ]);
    
        // Redirect back with success message
        return redirect()->back()->with('success', 'Status added successfully');
    }
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'status_name' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'ask_reason' => 'required',
        ]);
    
        // Update the status record in the database
        DB::table('service_statuses')
            ->where('id', $id)
            ->update([
                'status_name' => $validatedData['status_name'],
                'color' => $validatedData['color'],
                'ask_reason' => $validatedData['ask_reason'],
            ]);
    
        // Redirect back with success message
        return redirect()->back()->with('success', 'Status updated successfully');
    }

}
