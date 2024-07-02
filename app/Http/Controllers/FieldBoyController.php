<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cookie;

use Illuminate\Support\Facades\DB;

class FieldBoyController extends Controller
{
    public function create(Request $request)
    {
        if (Cookie::has('Admin_Session')) {
            $locations = DB::table('locations')->get();
            return view('admin.registerFieldBoy', compact('locations'));
        } else {

            return view('admin.login');
        }
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'mobile' => 'required',
            'aadhar' => 'required|unique:fieldboys',
            'pancard' => 'required|unique:fieldboys',
            'address' => 'required',
            'location' => 'required|exists:locations,id',
        ]);

        $existingUser = DB::table('fieldboys')
            ->Where('aadhar', $request->aadhar)
            ->orWhere('pancard', $request->pancard)
            ->first();

        if ($existingUser) {
            $errors = [];
            if ($existingUser->aadhar == $request->aadhar) {
                $errors['aadhar'] = 'Aadhar already exists';
            }
            if ($existingUser->pancard == $request->pancard) {
                $errors['pancard'] = 'Pancard already exists';
            }
            return back()->withInput()->withErrors($errors);
        }

        $referralCode = $this->generateUniqueReferralCode();

        DB::table('fieldboys')->insert([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'aadhar' => $request->aadhar,
            'pancard' => $request->pancard,
            'address' => $request->address,
            'location_id' => $request->location,
            'referal_code' => $referralCode,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Fieldboy registered successfully.');
    }
    private function generateUniqueReferralCode()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $referralCode = '';

        for ($i = 0; $i < 6; $i++) {
            $referralCode .= $characters[rand(0, strlen($characters) - 1)];
        }

        $existingReferral = DB::table('fieldboys')->where('referal_code', $referralCode)->exists();

        if ($existingReferral) {
            return $this->generateUniqueReferralCode();
        }

        return $referralCode;
    }

    public function generateLeaderBoard(Request $request)
    {
        if (Cookie::has('Admin_Session')) {
            $dateFrom = $request->input('start_date');
            $dateTo = $request->input('end_date');
            $query = DB::table('fieldboys')
                ->leftJoin('locations', 'fieldboys.location_id', '=', 'locations.id')
                ->leftJoin('agents', 'fieldboys.referal_code', '=', 'agents.referral_code')
                ->select(
                    'fieldboys.*',
                    'locations.district as city',
                    DB::raw('(SELECT COUNT(*) FROM agents WHERE agents.referral_code = fieldboys.referal_code) as referred_agent_count')
                );
                if ($dateFrom) {
                    $query->where('agents.reg_date', '>=', $dateFrom);
                }
                if ($dateTo) {
                    $query->where('agents.reg_date', '<=', $dateTo);
                }

            $query->orderBy("referred_agent_count", "desc");

            $fieldboys = $query->get();


            return view('admin.leaderboard', compact('fieldboys'));
        } else {

            return view('admin.login');
        }
    }
}
