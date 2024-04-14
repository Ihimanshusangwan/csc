<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index($serviceId)
    {
        $locations = DB::table('locations')->get();
        $appointmentPrice = DB::table('services')->select('appointment_price')->where('id','=',$serviceId)->first()->appointment_price;
        return view('admin.prices', ['locations' => $locations, 'serviceId' => $serviceId ,'appointmentPrice'=>$appointmentPrice]);
        
    }
    public function planBasedPrices($serviceId,$locationId)
    {
        $locations = DB::table('plans')
            ->leftJoin('prices', function ($join) use ($serviceId,$locationId) {
                $join->on('plans.id', '=', 'prices.plan_id')
                    ->where('prices.service_id', '=', $serviceId)
                    ->where('prices.location_id', '=', $locationId)
                    ->where('prices.plan_id', "!=", null);
            })
            ->select('prices.*','plans.name as planName','plans.id as main_plan_id')
            ->orderBy('plans.id', 'desc')
            ->distinct()
            ->get();

        $pricesWithoutSubscription = DB::table('prices')->where('service_id', $serviceId)->where('location_id',$locationId)->where('plan_id', null)->select('*')->first();
        return view('admin.planBasedPrices', ['locations' => $locations,'pricesWithoutSubscription' => $pricesWithoutSubscription, 'serviceId' => $serviceId,'locationId'=>$locationId]); 
    }

    public function store(Request $request, $serviceId, $locationId)
    {
        $planId = $request->input('plan_id');
        DB::table('prices')->insert([
            'service_id' => $serviceId,
            'location_id' => $locationId,
            'plan_id' => $planId,
            'default_govt_price' => $request->input('default_govt_price'),
            'default_commission_price' => $request->input('default_commission_price'),
            'default_tax_percentage' => $request->input('default_tax_percentage'),
            'tatkal_govt_price' => $request->input('tatkal_govt_price'),
            'tatkal_commission_price' => $request->input('tatkal_commission_price'),
            'tatkal_tax_percentage' => $request->input('tatkal_tax_percentage'),
            'subscribed_default_govt_price' => $request->input('subscribed_default_govt_price'),
            'subscribed_default_commission_price' => $request->input('subscribed_default_commission_price'),
            'subscribed_default_tax_percentage' => $request->input('subscribed_default_tax_percentage'),
            'subscribed_tatkal_govt_price' => $request->input('subscribed_tatkal_govt_price'),
            'subscribed_tatkal_commission_price' => $request->input('subscribed_tatkal_commission_price'),
            'subscribed_tatkal_tax_percentage' => $request->input('subscribed_tatkal_tax_percentage'),
        ]);
        return redirect()->back()
            ->with('success', 'Prices successfully stored.');
    }
    public function update(Request $request)
    {
        $priceId = $request->input('price_id');

        // Check if the price record exists
        $existingPrice = DB::table('prices')->find($priceId);

        if (!$existingPrice) {
            // Handle the case where the price record is not found
            return redirect()->back()->with('error', 'Price not found.');
        }

        // Update the price record with the new values
        DB::table('prices')
            ->where('id', $priceId)
            ->update([
                'default_govt_price' => $request->input('default_govt_price'),
                'default_commission_price' => $request->input('default_commission_price'),
                'default_tax_percentage' => $request->input('default_tax_percentage'),
                'tatkal_govt_price' => $request->input('tatkal_govt_price'),
                'tatkal_commission_price' => $request->input('tatkal_commission_price'),
                'tatkal_tax_percentage' => $request->input('tatkal_tax_percentage'),
                'subscribed_default_govt_price' => $request->input('subscribed_default_govt_price'),
                'subscribed_default_commission_price' => $request->input('subscribed_default_commission_price'),
                'subscribed_default_tax_percentage' => $request->input('subscribed_default_tax_percentage'),
                'subscribed_tatkal_govt_price' => $request->input('subscribed_tatkal_govt_price'),
                'subscribed_tatkal_commission_price' => $request->input('subscribed_tatkal_commission_price'),
                'subscribed_tatkal_tax_percentage' => $request->input('subscribed_tatkal_tax_percentage'),
            ]);

        return redirect()->back()->with('success', 'Price successfully updated.');
    }

    public function updateAppointmentPrice(Request $request, $serviceId)
    {
        $newPrice = $request->input('appointment_price');

        DB::table('services')
            ->where('id', $serviceId)
            ->update(['appointment_price' => $newPrice]);

        return redirect()->back()->with('success', 'Appointment price updated successfully.');
    }


}




