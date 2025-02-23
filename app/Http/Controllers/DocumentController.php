<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    public function requestDocument(Request $request)
    {
        $applicationId = $request->input('application_id');
        $documentKey = $request->input('document_key');
        $message = $request->input('message');
        $application = DB::table('applications')
            ->where('id', $applicationId)
            ->first();
        if (!$application) {
            return redirect()->back()->with('error', 'Application not found!');
        }
        $formData = json_decode($application->form_data, true);
        if (array_key_exists($documentKey, $formData['formData'])) {
            $formData['formData'][$documentKey]['is_re_requested'] = true;
            $formData['formData'][$documentKey]['message'] = $message;
        } else {
            return redirect()->back()->with('error', 'Document key not found in the application form data!');
        }
        DB::table('applications')
            ->where('id', $applicationId)
            ->update([
                'form_data' => json_encode($formData),
            ]);
        return redirect()->back()->with('success', 'Document request sent successfully!');
    }

    public function reSubmitDocument(Request $request, $id, $key)
    {
        $document = $request->file('document');
        $application = DB::table('applications')->where('id', $id)->first();

        if (!$application) {
            return redirect()->back()->with('error', 'Application not found!');
        }

        $formData = json_decode($application->form_data, true);

        if (!array_key_exists($key, $formData['filePaths'])) {
            return redirect()->back()->with('error', 'Document key not found in the application form data!');
        }

        // Delete the existing file
        $existingFilePath = public_path($formData['filePaths'][$key]);
        if (file_exists($existingFilePath)) {
            unlink($existingFilePath);
        }

        // Generate a unique filename for the new file
        $fileName = time() . '_' . uniqid() . '_' . $document->getClientOriginalName();

        // Move the uploaded file to the desired directory
        $document->move(public_path('uploads/applications'), $fileName);

        // Update the file path in the form data
        $formData['filePaths'][$key] = 'uploads/applications/' . $fileName;

        // Update the flags
        if (array_key_exists($key, $formData['formData'])) {
            $formData['formData'][$key]['is_re_requested'] = false;
        }

        // Update the application record in the database
        DB::table('applications')->where('id', $id)->update([
            'form_data' => json_encode($formData),
        ]);

        return redirect()->back()->with('success', 'Document re-submitted successfully!');
    }

}
