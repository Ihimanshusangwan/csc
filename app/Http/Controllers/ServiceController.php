<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function index()
    {
        $services = DB::table('services')
            ->where( 'is_active', 1 )
            ->join('service_groups', 'services.service_group_id', '=', 'service_groups.id')
            ->select('services.*', 'service_groups.name as service_group_name')
            ->get();

        $serviceGroups = DB::table('service_groups')->get();

        return view('admin.services', ['services' => $services, 'serviceGroups' => $serviceGroups]);
    }

    public function create()
    {
        // Your create logic here

        return view('services.create');
    }

    public function store(Request $request)
    {

        DB::table('services')->insert([
            'name' => $request->input('name'),
            'service_group_id' => $request->input('service_group_id'),
            'requirements' => $request->input('requirements'),
            'form' => $request->input('form'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('services.index')->with('success', 'Service created successfully');
    }
    public function destroy($id)
    {
        DB::table('services')
            ->where('id', $id)
            ->update(['is_active' => 0]);

        return redirect()->route('services.index')->with('success', 'Service status updated successfully.');
    }
    public function updateVisibility(Request $request, $serviceId)
    {
        $visibilityId = $request->input('visibilityId');

        // Update visibility in the database
        DB::table('services')
            ->where('id', $serviceId)
            ->update(['visibility' => $visibilityId]);

        // Return response
        return response()->json(['message' => 'Visibility updated successfully'], 200);
    }
    public function updateAvailability(Request $request, $serviceId)
    {
        $availabilityId = $request->input('availabilityId');

        // Update visibility in the database
        DB::table('services')
            ->where('id', $serviceId)
            ->update(['availability' => $availabilityId]);

        // Return response
        return response()->json(['message' => 'Availability updated successfully'], 200);
    }
    public function edit($id)
{
    $service = DB::table('services')->where('id', $id)->first();
    $serviceGroups = DB::table('service_groups')->get();

    return view('services.edit', compact('service', 'serviceGroups'));
}

public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'service_group_id' => 'required|integer',
        'requirements' => 'required|string',
        'form' => 'required|string',
    ]);

    DB::table('services')->where('id', $id)->update([
        'name' => $validatedData['name'],
        'service_group_id' => $validatedData['service_group_id'],
        'requirements' => $validatedData['requirements'],
        'form' => $validatedData['form'],
        'updated_at' => now(),
    ]);

    return redirect()->route('services.index')->with('success', 'Service updated successfully');
}


}
