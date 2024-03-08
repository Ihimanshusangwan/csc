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

        return view('appointment', compact('serviceGroups', 'services','locations'));
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
}
