<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index($serviceId)
    {
        $locations = DB::table('locations')
            ->select(
                'locations.id AS location_id_1',
                'locations.*',
                'prices.id AS price_id',
                'prices.*'
            )
            ->leftJoin('prices', function ($join) use ($serviceId) {
                $join->on('locations.id', '=', 'prices.location_id')
                    ->where('prices.service_id', '=', $serviceId);
            })
            ->orderBy('locations.district', 'asc')
            ->distinct()
            ->get();
        return view('admin.prices', ['locations' => $locations, 'serviceId' => $serviceId]);
    }

    public function store(Request $request, $serviceId)
    {
        $locationId = $request->input('location_id');
        DB::table('prices')->insert([
            'service_id' => $serviceId,
            'location_id' => $locationId,
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
        return redirect()->route('prices.index', ['serviceId' => $serviceId])
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


}




