<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\adminAuth;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Session;
class AdminAuthController extends Controller
{


    public function index()
    {



        return view('Admin.adminRegister');
    }
    public function adminLogin()
    {



        return view('Admin.adminLogin');
    }
    public function adminDashboard()
    {



        return view('dashboard.dashboard');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:admin_auth,email',
            'password' => 'required|string|min:6',
        ]);

        $user = adminAuth::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/login')->with('success', 'Registration successful. Please login.');
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = adminAuth::where('email', $request->email)->first();
        $employee = Employee::where('email', $request->email)->first();

        // Allowed user types
        $allowedUserTypes = ['Admin', 'Store', 'Production', 'Inspection'];

        if ($employee) {
            if ($employee->user_status !== 'Active') {
                return back()->with('error', 'Your account is not active.');
            }

            if (!in_array($employee->user_type, $allowedUserTypes)) {
                return back()->with('error', 'You are not authorized to login.');
            }

            if (Hash::check($request->password, $employee->password)) {
                session([
                    'employee_logged_in' => true,
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name,
                    'employee_email' => $employee->email,
                    'employee_image' => asset('storage/' . $employee->avatar),
                    'employee_type' => $employee->user_type,
                ]);

                return redirect()->route('dashboard');
            } else {
                return back()->with('error', 'Invalid credentials');
            }
        }

        if ($user && Hash::check($request->password, $user->password)) {
            session(['admin' => $user]);

            return redirect('/')->with('success', 'Login successful!');
        }

        return back()->with('error', 'Invalid credentials');
    }


    public function myProfile()
    {
        $admin = adminAuth::first(); // Get first admin row from admin_auth table

        if (!$admin) {
            return redirect()->route('login')->with('error', 'Admin not found.');
        }

        return view('Admin.AdminMyProfile', [
            'admin' => $admin,
            'role' => 'admin'
        ]);
    }


    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $admin = adminAuth::first(); // Fetch first admin

        if (!$admin || !Hash::check($request->current_password, $admin->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        $admin->password = Hash::make($request->new_password);
        $admin->save();

        return back()->with('success', 'Password updated successfully!');
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }

}
