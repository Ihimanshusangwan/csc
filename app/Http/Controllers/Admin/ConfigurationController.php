<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    public function index()
    {
        $photoMakingCharge = Configuration::getValue('photo_making_charge');

        return view('admin.configurations.index', compact('photoMakingCharge'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'value' => 'required',
        ]);

        Configuration::setValue($request->key, $request->value);

        return response()->json(['success' => true, 'message' => 'Configuration updated successfully.']);
    }
}
