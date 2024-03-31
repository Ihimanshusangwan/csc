<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class ServiceGroupController extends Controller
{
    public function index()
    {
        $serviceGroups = DB::table('service_groups')
            ->leftJoin('services', 'service_groups.id', '=', 'services.service_group_id')
            ->select('service_groups.*', 'services.name as service_name')
            ->get();

        // Group the results by service group ID
        $groupedServiceGroups = collect($serviceGroups)->groupBy('id');
        // dd($groupedServiceGroups);

        return view('admin.serviceGroups', compact('groupedServiceGroups'));
    }
    public function store(Request $request)
    {
        // Check if a file has been uploaded
        if ($request->hasFile('photo')) {
            // Generate a unique name for the photo
            $imageName = time() . '_' . uniqid() . '.' . $request->file('photo')->extension();
            // Move the uploaded image to the 'public/uploads/service-groups' directory with a unique name
            $request->file('photo')->move(public_path('uploads/service-groups'), $imageName);

            // Store the service group using the Query Builder
            DB::table('service_groups')->insert([
                'name' => $request->input('name'),
                'photo' => 'uploads/service-groups/' . $imageName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('service-groups.index')->with('success', 'Service group added successfully.');
        } else {
            return redirect()->back()->with('error', 'Please upload a valid image.');
        }
    }
    public function update(Request $request, $id)
    {


        $serviceGroup = DB::table('service_groups')->find($id);

        if (!$serviceGroup) {
            return redirect()->route('service-groups.index')->with('error', 'Service group not found.');
        }

        // Update service group details
        DB::table('service_groups')->where('id', $id)->update([
            'name' => $request->input('name'),
            'updated_at' => now(),
        ]);

        // Update photo if a new one is provided
        if ($request->hasFile('photo')) {
            $imageName = time() . '_' . uniqid() . '.' . $request->file('photo')->extension();
            $request->file('photo')->move(public_path('uploads/service-groups'), $imageName);
            DB::table('service_groups')->where('id', $id)->update([
                'photo' => 'uploads/service-groups/' . $imageName,
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('service-groups.index')->with('success', 'Service group updated successfully.');
    }
    public function edit($id)
    {
        $serviceGroup = DB::table('service_groups')->find($id);

        if (!$serviceGroup) {
            return redirect()->route('service-groups.index')->with('error', 'Service group not found.');
        }

        return view('admin.editServiceGroup', compact('serviceGroup'));
    }
    public function updateVisibility(Request $request, $groupId)
    {
        $visibility = $request->input('visibility') ? 1 : 0;

        DB::table('service_groups')
            ->where('id', $groupId)
            ->update(['visibility' => $visibility]);

        return response()->json(['message' => 'Visibility updated successfully.']);
    }
    public function updateAvailability(Request $request, $groupId)
    {
        $availability = $request->input('availability') ? 1 : 0;
        try {
            DB::beginTransaction();
        
            if ($availability) {
                DB::table('services')->where('service_group_id', $groupId)->update(['availability' => 2]);
            } else {
                DB::table('services')->where('service_group_id', $groupId)->update(['availability' => 1]);
            }
            DB::table('service_groups')
            ->where('id', $groupId)
            ->update(['availability' => $availability]);
        
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            // Handle exception, log error, or throw further
        }
        

        return response()->json(['message' => 'Avalability updated successfully.']);
    }
   
}
