<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function update(Request $request) {
    
        // Retrieve the application ID from the request
        $applicationId = $request->input('application_id');
    
        // Start building the update query
        $updateData = [];
    
        // Update the delivery date if provided
        if ($request->has('delivery_date')) {
            $updateData['delivery_date'] = $request->input('delivery_date');
        }
    
        // Update the document if provided
        if ($request->hasFile('document')) {
            // Retrieve the uploaded file
            $file = $request->file('document');
    
            // Generate a unique file name
            $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
    
            // Move the uploaded file to the desired directory
            $file->move(public_path('uploads/applications'), $fileName);
    
            // Update the document path in the update data array
            $updateData['delivery'] = 'uploads/applications/' . $fileName;
        }
        // Update the application using the query builder
        DB::table('applications')
            ->where('id', $applicationId)
            ->update($updateData);
    
        // Redirect back or wherever you need after successful update
        return redirect()->back()->with('success', 'Application updated successfully');
    }
    
}
