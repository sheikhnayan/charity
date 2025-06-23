<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
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

        if($request->group_id != null){
            $group_id = $request->group_id;
        }else{
            $group_id = null;
        }

        if($request->group_name != null){
            $group_name = $request->group_name;
        }else{
            $group_name = null;
        }

        User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->register_as,
            'group_id' => $group_id,
            'group_name' => $group_name,
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

        return redirect()->back()->with('success', 'Profile updated successfully');
    }
}
