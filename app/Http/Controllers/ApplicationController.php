<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function update(Request $request)
    {

        // Retrieve the application ID from the request
        $applicationId = $request->input('application_id');

        // Start building the update query
        $updateData = [];

        // Update the delivery date if provided
        if ($request->has('delivery_date')) {
            $updateData['delivery_date'] = $request->input('delivery_date');
        }
        // Update the status if provided
        if ($request->has('status')) {
            $statusId = $request->input('status');
            $updateData['status'] = $statusId;
            if ($request->input('reason')) {
                $updateData['reason'] = $request->input('reason');
            }
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
        // Update the receipt if provided
        if ($request->hasFile('receipt')) {
            // Retrieve the uploaded file
            $file = $request->file('receipt');

            // Generate a unique file name
            $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();

            // Move the uploaded file to the desired directory
            $file->move(public_path('uploads/applications'), $fileName);

            // Update the document path in the update data array
            $updateData['receipt'] = 'uploads/applications/' . $fileName;
        }
        // dd($updateData);
        // Update the application using the query builder
        DB::table('applications')
            ->where('id', $applicationId)
            ->update($updateData);

        // Redirect back or wherever you need after successful update
        return redirect()->back()->with('success', 'Application updated successfully');
    }
    public function changeDocApprovalStatus(Request $request, $application_id)
    {
        $isApproved = $request->input('isApproved');
        DB::table('applications')
            ->where('id', $application_id)
            ->update(['is_doc_approved' => $isApproved]);
        $application = DB::table('applications')->where('id', $application_id)->first();
        return response()->json([
            'message' => 'Approval status updated successfully.',
            'application' => $application
        ]);
    }
    public function destroy($id)
    {
        $application = DB::table('applications')->where('id', $id)->first();

        if (!$application) {
            return redirect()->back()->with('error', 'Application not found.');
        }

        DB::table('deleted_applications')->insert([
            'data' => json_encode($application),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('applications')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Application deleted and archived successfully.');
    }
}
