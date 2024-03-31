<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;


class AppointmentController extends Controller
{
    public function index()
    {
        $serviceGroups = DB::table('service_groups')->get();
        $services = DB::table('services')
            ->where("is_active", 1)
            ->where("visibility", 1)
            ->orwhere("visibility", 3)->get();
        $locations = DB::table('locations')
            ->orderBy('district', 'asc')
            ->get();

        return view('appointment', compact('serviceGroups', 'services', 'locations'));
    }
    public function store(Request $request)
    {
        // Parse JSON data received from the frontend
        $data = json_decode($request->getContent(), true);

        // Extract fields from the parsed JSON data
        $name = $data['name'];
        $email = $data['email'];
        $phone = $data['phone'];
        $address = $data['address'];
        $cityId = $data['city'];
        // Convert selectedDate to the correct format
        $selectedDate = date('Y-m-d', strtotime($data['selectedDate']));
        $selectedTimeSlot = $data['selectedTimeSlot'];
        $servicePrice = $data['servicePrice'];
        $serviceId = $data['selectedTileId'];

        // Insert data into the appointments table 
        DB::table('appointments')->insert([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'city_id' => $cityId,
            'selected_date' => $selectedDate,
            'selected_time_slot' => $selectedTimeSlot,
            'service_price' => $servicePrice,
            'service_id' => $serviceId,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Appointment created successfully'], 200);
    }
    public function markVisited(Request $request)
    {
        $appointmentId = $request->input('appointment_id');

        // Perform update using query builder
        DB::table('appointments')->where('id', $appointmentId)->update(['status' => 1]);

        // Redirect back to the previous page
        return redirect()->back();
    }

    public function rejectAppointment(Request $request)
    {
        $appointmentId = $request->input('appointment_id');

        // Get reason for rejection
        $reason = $request->input('reason');

        // Perform update using query builder
        DB::table('appointments')->where('id', $appointmentId)->update(['status' => 2]);

        // Save reason for rejection in appointment_deletion_reasons table
        DB::table('appointment_deletion_reasons')->insert([
            'appointment_id' => $appointmentId,
            'reason' => $reason,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Redirect back to the previous page
        return redirect()->back();
    }
}
