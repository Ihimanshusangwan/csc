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
                ->select('location_id', 'balance')
                ->first();

            $locationId = $agentData->location_id ?? null;
            $balance = $agentData->balance ?? null;

            $prices = DB::table('prices')->where('location_id', $locationId)->where('service_id', $id)->get();
            $service = DB::table('services')->where('id', $id)->get();
            return view("agent.directApply", compact('service', 'prices', 'balance'));

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
            $locationId = DB::table('agents')->where('id', $agentId)->value('location_id');
            $prices = DB::table('prices')->where('location_id', $locationId)->where('service_id', $id)->get();

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

                // Calculate the total price based on the selected price type
                if ($priceType === 'default') {
                    $totalPrice = $prices[0]->default_govt_price + $prices[0]->default_commission_price + ($prices[0]->default_govt_price * $prices[0]->default_tax_percentage / 100);
                } else {
                    $totalPrice = $prices[0]->tatkal_govt_price + $prices[0]->tatkal_commission_price + ($prices[0]->tatkal_govt_price * $prices[0]->tatkal_tax_percentage / 100);
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