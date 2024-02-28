<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function index()
    {
        $locations = DB::table('locations')
            ->orderBy('district', 'asc')
            ->get();


        return view('admin.locations', ['locations' => $locations]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'district' => 'required|string|max:255',
            'state' => 'required|string|max:255',
        ]);

        DB::table('locations')->insert($validatedData);

        return redirect()->route('locations.index')->with('success', 'Location added successfully');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'district' => 'required|string|max:255',
            'state' => 'required|string|max:255',
        ]);

        DB::table('locations')->where('id', $id)->update($validatedData);

        return redirect()->route('locations.index')->with('success', 'Location updated successfully');
    }

    public function destroy($id)
    {
        // Begin a database transaction
        DB::beginTransaction();

        try {
            // Delete corresponding rows in the prices table
            DB::table('prices')->where('location_id', $id)->delete();

            // Delete the location
            DB::table('locations')->where('id', $id)->delete();
            DB::commit();

            // If everything went well, redirect with success message
            return redirect()->route('locations.index')->with('success', 'Location and corresponding prices deleted successfully');
        } catch (\Exception $e) {
            // If an exception occurs, rollback the transaction
            DB::rollback();

            // Redirect with an error message
            return redirect()->route('locations.index')->with('error', 'Failed to delete location and corresponding prices.');
        }
    }

}
