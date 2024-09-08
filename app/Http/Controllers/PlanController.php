<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    public function index()
    {
        $plans = DB::table('plans')
            ->where("plans.is_active", 1)
            ->leftJoin('plan_services', 'plans.id', '=', 'plan_services.plan_id')
            ->leftJoin('services', 'plan_services.service_id', '=', 'services.id')
            ->leftJoin('service_groups', 'services.service_group_id', '=', 'service_groups.id')
            ->select(
                'plans.id as plan_id',
                'plans.name as plan_name',
                'plans.duration',
                'plans.price',
                'services.id as service_id',
                'services.name as service_name',
                'service_groups.name as service_group_name'
            )
            ->get();

        $groupedPlans = $plans->groupBy('plan_id');

        $serviceGroups = DB::table('service_groups')->where('visibility', 1)->where('availability', 0)->get();
        $services = DB::table('services')
            ->where("is_active", 1)
            ->where("visibility", 2)
            ->orwhere("visibility", 3)->get();

        return view('admin.plans', compact('groupedPlans', 'serviceGroups', 'services'));
    }

    public function store(Request $request)
    {
        // Extract data from the request
        $name = $request->input('name');
        $price = $request->input('price');
        $duration = $request->input('duration');

        // Extract selected services
        $selectedServices = $request->input('selected_services');

        // Convert comma-separated values to array
        $servicesArray = $selectedServices ? array_unique($selectedServices) : [];

        // Insert data into the 'plans' table
        $planId = DB::table('plans')->insertGetId([
            'name' => $name,
            'price' => $price,
            'duration' => $duration,
        ]);

        // Insert selected services into the 'plan_services' table
        foreach ($servicesArray as $serviceId) {
            DB::table('plan_services')->insert([
                'plan_id' => $planId,
                'service_id' => $serviceId,
            ]);
        }

        // Redirect or respond as needed after storing plans
        return redirect()->route('plans.index')->with('success', 'Plan created successfully');
    }

    public function update(Request $request, $id)
    {
        // Extract data from the request
        $name = $request->input('name');
        $price = $request->input('price');
        $duration = $request->input('duration');

        // Update plan details
        DB::table('plans')->where('id', $id)->update([
            'name' => $name,
            'price' => $price,
            'duration' => $duration,
        ]);

        // Extract selected services
        $selectedServices = $request->input('selected_services');

        // Convert comma-separated values to array
        $servicesArray = $selectedServices ? array_unique($selectedServices) : [];

        // Delete existing services for the plan
        DB::table('plan_services')->where('plan_id', $id)->delete();

        // Insert selected services into the 'plan_services' table
        foreach ($servicesArray as $serviceId) {
            DB::table('plan_services')->insert([
                'plan_id' => $id,
                'service_id' => $serviceId,
            ]);
        }

        // Redirect or respond as needed after updating plans
        return redirect()->route('plans.index')->with('success', 'Plan updated successfully');
    }

    public function destroy($id)
    {
        DB::table('plans')
            ->where('id', $id)
            ->update(['is_active' => 0]);

        return redirect()->route('plans.index')->with('success', 'Plan deleted successfully');
    }
}
