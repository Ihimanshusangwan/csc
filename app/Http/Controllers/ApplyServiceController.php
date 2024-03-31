<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;

class ApplyServiceController extends Controller
{
    public function direct($id)
    {
        if (Cookie::has('Agent_Session')) {
            // The cookie exists, proceed to the admin dashboard
            // Retrieve and decrypt the agent's ID from the cookie
            $encryptedAgentId = Cookie::get('Agent_Session');
            $agentId = Crypt::decrypt($encryptedAgentId);
            // Retrieve location_id and balance in a single query
            $agentData = DB::table('agents')
                ->where('id', $agentId)
                ->select('location_id', 'balance', 'expiration_date')
                ->first();

            $locationId = $agentData->location_id ?? null;
            $balance = $agentData->balance ?? 0;
            $currentDate = date('Y-m-d');
            $prices = DB::table('prices')->where('location_id', $locationId)->where('service_id', $id)->get();
            if ($agentData->expiration_date >= $currentDate) {

                $defaultPrice = $prices[0]->subscribed_default_govt_price + $prices[0]->subscribed_default_commission_price + ($prices[0]->subscribed_default_govt_price * $prices[0]->subscribed_default_tax_percentage / 100);

                $tatkalPrice = $prices[0]->subscribed_tatkal_govt_price + $prices[0]->subscribed_tatkal_commission_price + ($prices[0]->subscribed_tatkal_govt_price * $prices[0]->subscribed_tatkal_tax_percentage / 100);
            } else {

                $defaultPrice = $prices[0]->default_govt_price + $prices[0]->default_commission_price + ($prices[0]->default_govt_price * $prices[0]->default_tax_percentage / 100);

                $tatkalPrice = $prices[0]->tatkal_govt_price + $prices[0]->tatkal_commission_price + ($prices[0]->tatkal_govt_price * $prices[0]->tatkal_tax_percentage / 100);
            }

            $service = DB::table('services')->where('id', $id)->get();
            return view("agent.directApply", compact('service', 'defaultPrice', 'balance', 'tatkalPrice'));
        } else {
            return view('agent.login');
        }
    }
    public function submitForm(Request $request, $id)
    {

        if (Cookie::has('Agent_Session')) {
            // The cookie exists, proceed to the admin dashboard
            // Retrieve and decrypt the agent's ID from the cookie
            $encryptedAgentId = Cookie::get('Agent_Session');
            $agentId = Crypt::decrypt($encryptedAgentId);
            $data = DB::table("agents")->select('location_id', 'expiration_date')->where("id", "=", $agentId)->first();
            $currentDate = date('Y-m-d');
            $locationId = $data->location_id;
            $serviceGroup = DB::table("services")->select('service_group_id')->where("id", "=", $id)->first();
            $serviceGroupId = $serviceGroup->service_group_id;
            $prices = DB::table('prices')->where('location_id', $locationId)->where('service_id', $id)->get();
            $latestStaffId = DB::table('applications')
                ->where('service_group_id', $serviceGroupId)
                ->where('location_id', $locationId)
                ->latest()
                ->value('staff_id');

            // Retrieve all staff IDs
            $staffIds = DB::table('staff')
                ->where('location_id', $locationId)
                ->where('service_group_id', $serviceGroupId)
                ->pluck('id')
                ->toArray();

            // Check if the staff IDs array is empty
            if (count($staffIds) > 0) {
                // Find the index of the last staff ID in the array
                $lastStaffIdIndex = array_search($latestStaffId, $staffIds);

                // Calculate the index of the next staff ID
                $nextStaffIdIndex = ($lastStaffIdIndex + 1) % count($staffIds);

                // Get the next staff ID
                $nextStaffId = $staffIds[$nextStaffIdIndex];
            } else {
                // If the array is empty, set the next staff ID to null
                $nextStaffId = null;
            }


            try {
                // Start a transaction
                DB::beginTransaction();

                // Create a new customer record
                $customerId = DB::table('customers')->insertGetId([
                    'name' => $request->input('customerName'),
                    'mobile' => $request->input('mobileNumber'),
                    'agent_id' => $agentId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Retrieve the selected price type
                $priceType = $request->input('price_type');

                if ($data->expiration_date >= $currentDate) {
                    if ($priceType === 'default') {
                        $totalPrice = $prices[0]->subscribed_default_govt_price + $prices[0]->subscribed_default_commission_price + ($prices[0]->subscribed_default_govt_price * $prices[0]->subscribed_default_tax_percentage / 100);
                    } else {
                        $totalPrice = $prices[0]->subscribed_tatkal_govt_price + $prices[0]->subscribed_tatkal_commission_price + ($prices[0]->subscribed_tatkal_govt_price * $prices[0]->subscribed_tatkal_tax_percentage / 100);
                    }
                } else {
                    // Calculate the total price based on the selected price type
                    if ($priceType === 'default') {
                        $totalPrice = $prices[0]->default_govt_price + $prices[0]->default_commission_price + ($prices[0]->default_govt_price * $prices[0]->default_tax_percentage / 100);
                    } else {
                        $totalPrice = $prices[0]->tatkal_govt_price + $prices[0]->tatkal_commission_price + ($prices[0]->tatkal_govt_price * $prices[0]->tatkal_tax_percentage / 100);
                    }
                }




                // Get all form input data excluding specific fields
                $formData = $request->except('_token', 'customerName', 'mobileNumber');

                $filePaths = [];
                // Loop through the uploaded files
                foreach ($request->allFiles() as $fieldName => $files) {
                    // If there's only one file, convert it to an array to simplify processing
                    if (!is_array($files)) {
                        $files = [$files];
                    }

                    foreach ($files as $file) {
                        // Generate a unique filename
                        $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();

                        // Move the uploaded file to the desired directory
                        $file->move(public_path('uploads/applications'), $fileName);

                        // Add the file name and path to the array with input name as key
                        $filePaths[$fieldName] = 'uploads/applications/' . $fileName;
                    }
                }

                // Convert the array to JSON
                $formDataJson = json_encode([
                    'formData' => $formData,
                    'filePaths' => $filePaths
                ]);
                //update balance of agent
                // Retrieve the current balance of the agent
                $currentBalance = DB::table('agents')
                    ->where('id', $agentId)
                    ->value('balance');

                // Calculate the new balance after recharge
                $newBalance = $currentBalance - $totalPrice;

                // Update the balance in the database
                DB::table('agents')
                    ->where('id', $agentId)
                    ->update([
                        'balance' => $newBalance
                    ]);

                // Create a new application record
                DB::table('applications')->insert([
                    'agent_id' => $agentId, // Assuming agent_id is authenticated agent's ID
                    'customer_id' => $customerId,
                    'service_id' => $id,
                    'form_data' => $formDataJson,
                    'price' => $totalPrice,
                    'location_id' => $locationId,
                    'service_group_id' => $serviceGroupId,
                    'staff_id' => $nextStaffId,
                    'apply_date' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Commit the transaction
                DB::commit();
                return redirect()->route('agent.dashboard')->with('success', 'Application submitted successfully!');
            } catch (\Exception $e) {
                // If an exception occurs, rollback the transaction
                DB::rollback();

                // Handle the exception, log it, etc.
                return redirect()->route('agent.dashboard')->with('error', 'An error occurred while submitting the application.');
            }
        } else {

            return view('agent.login');
        }
    }
}
