<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Website;
use Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (Auth::user()) {
                if (Auth::user()->role == 'admin') {
                    # code...
                    return redirect()->intended('/admins')->with('success', 'Login successful');
                }else{
                    # code...
                    return redirect()->intended('/users')->with('success', 'Login successful');
                }
            }

        }

        return redirect('login')->with('error', 'Invalid credentials');
    }

    public function register(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users|same:confirm_email',
            'password' => 'required|string|min:8|same:confirm_password',
        ]);

        // Handle teacher_id for individual registrations
        $teacher_id = null;
        if ($request->register_as === 'individual' && $request->teacher_id) {
            $teacher_id = $request->teacher_id;
        }

        $url = url()->current();
        if( $url == 'fundably.org' || $url == 'https://fundably.org' || $url == 'http://fundably.org' || $url == 'http://127.0.0.1:8000') {
            return redirect()->route('admin.index', 1);
        }
        $doamin = parse_url($url, PHP_URL_HOST);
        $check = Website::where('domain', $doamin)->first();

        User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->register_as,
            'teacher_id' => $teacher_id,
            'website_id' => $check->id,
        ]);

        return redirect('login')->with('success', 'Registration successful');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('login')->with('success', 'Logout successful');
    }

    public function updateProfile(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $user->photo = 'images/' . $filename;
        }
        $user->goal = $request->goal;
        $user->description = $request->description;
        $user->teacher_id = $request->teacher_id;
        $user->size = $request->size;
        $user->grade = $request->grade;

        $user->save();

        // Handle investor profile for customers
        if ($user->role === 'customer' && $request->has('investor_type')) {
            $investorType = $request->input('investor_type');
            $investorData = $request->input('investor_data', []);
            
            if ($investorType) {
                \App\Models\UserInvestorProfile::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'investor_type' => $investorType,
                        'investor_data' => $investorData,
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    public function saveInvestorProfile(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $investorType = $request->input('investor_type');
            
            if (!$investorType) {
                return response()->json(['success' => false, 'message' => 'Investor type is required'], 422);
            }

            // Collect all investor data
            $investorData = [];
            
            // Individual fields
            if ($investorType === 'individual') {
                $investorData = [
                    'individual_name' => $request->input('individual_name'),
                    'date_of_birth' => $request->input('date_of_birth'),
                    'ssn' => $request->input('ssn'),
                ];
            }
            // Joint fields
            elseif ($investorType === 'joint') {
                $investorData = [
                    'primary_name' => $request->input('primary_name'),
                    'primary_dob' => $request->input('primary_dob'),
                    'primary_ssn' => $request->input('primary_ssn'),
                    'secondary_name' => $request->input('secondary_name'),
                    'secondary_dob' => $request->input('secondary_dob'),
                    'secondary_ssn' => $request->input('secondary_ssn'),
                    'joint_type' => $request->input('joint_type'),
                ];
            }
            // Corporation fields
            elseif ($investorType === 'corporation') {
                $investorData = [
                    'corporation_name' => $request->input('corporation_name'),
                    'ein' => $request->input('ein'),
                    'incorporation_state' => $request->input('incorporation_state'),
                    'accredited_investor' => $request->input('accredited_investor'),
                ];
            }
            // Trust fields
            elseif ($investorType === 'trust') {
                $investorData = [
                    'trust_name' => $request->input('trust_name'),
                    'trust_ein' => $request->input('trust_ein'),
                    'trust_type' => $request->input('trust_type'),
                ];
            }
            // IRA fields
            elseif ($investorType === 'ira') {
                $investorData = [
                    'ira_holder_name' => $request->input('ira_holder_name'),
                    'ira_type' => $request->input('ira_type'),
                    'custodian' => $request->input('custodian'),
                ];
            }

            // Update or create investor profile
            $profile = \App\Models\UserInvestorProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'investor_type' => $investorType,
                    'investor_data' => $investorData,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Investor profile saved successfully',
                'profile' => $profile
            ]);

        } catch (\Exception $e) {
            \Log::error('Save investor profile error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred'], 500);
        }
    }

    public function getInvestorProfile(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $profile = \App\Models\UserInvestorProfile::where('user_id', $user->id)->first();

            return response()->json([
                'success' => true,
                'profile' => $profile
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred'], 500);
        }
    }
}

