<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
class EmployeeController extends Controller
{
    public function index()
    {

        return view('Employees.AddEmployee');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone' => [
                'required',
                'regex:/^[0-9]{10}$/',
            ],
            'address' => 'nullable|string',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|min:6',
            'user_type' => 'required|in:Admin,Store,Production,Inspection', // ✅ Updated here
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'adhar_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'pan_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'phone.regex' => 'Phone number must be exactly 10 digits.',
            'email.unique' => 'This email is already registered.',
        ]);

        if ($request->password !== $request->password_confirmation) {
            return response()->json([
                'errors' => [
                    'password' => ['Password and Confirm Password do not match.']
                ]
            ], 422);
        }

        // ✅ Custom Image Upload Function
        function uploadToStorage1($file, $folder)
        {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destinationPath = base_path("/public/storage/$folder");

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);

            return "$folder/$fileName";
        }

        // ✅ File Uploads to custom folders
        if ($request->hasFile('avatar')) {
            $validatedData['avatar'] = uploadToStorage1($request->file('avatar'), 'avatars');
        }

        if ($request->hasFile('adhar_image')) {
            $validatedData['adhar_image'] = uploadToStorage1($request->file('adhar_image'), 'adhar_images');
        }

        if ($request->hasFile('pan_image')) {
            $validatedData['pan_image'] = uploadToStorage1($request->file('pan_image'), 'pan_images');
        }

        // ✅ Hash the password
        $validatedData['password'] = Hash::make($validatedData['password']);

        // ✅ Create Employee
        $employee = Employee::create($validatedData);

        // ✅ Handle redirection/message based on user_type
        switch ($employee->user_type) {
            case 'Admin':
                $redirectRoute = route('admin.list');
                $message = 'Admin added successfully!';
                break;
            case 'Store':
                $redirectRoute = route('admin.list');
                $message = 'Store user added successfully!';
                break;
            case 'Production':
                $redirectRoute = route('admin.list');
                $message = 'Production user added successfully!';
                break;
            case 'Inspection':
                $redirectRoute = route('admin.list');
                $message = 'Inspection user added successfully!';
                break;
            default:
                $redirectRoute = route('dashboard');
                $message = 'User added successfully!';
        }

        return response()->json([
            'message' => $message,
            'redirect' => $redirectRoute
        ]);
    }



    // public function AdminList()
    // {
    //     $admins = Employee::where('user_type', 'Admin')
    //         ->orderBy('created_at', 'desc')
    //         ->paginate(10);

    //     return view('Employees.AdminList', compact('admins'));
    // }


    // public function AdminList(Request $request)
    // {
    //     $search = $request->input('search');             // Name / Phone
    //     $userType = $request->input('user_type');       // User Type
    //     $date = $request->input('created_date');        // Created Date
    //     $status = $request->input('status');            // Active / Inactive

    //     $admins = Employee::when($search, function ($query, $search) {
    //         $query->where(function ($q) use ($search) {
    //             $q->where('name', 'like', '%' . $search . '%')
    //                 ->orWhere('phone', 'like', '%' . $search . '%');
    //         });
    //     })
    //         ->when($userType, function ($query, $userType) {
    //             $query->where('user_type', $userType);
    //         })
    //         ->when($date, function ($query, $date) {
    //             $query->whereDate('created_at', $date);
    //         })
    //         ->when($status, function ($query, $status) {
    //             $query->where('user_status', $status);
    //         })
    //         ->orderBy('created_at', 'asc')
    //         ->paginate(10);

    //     $admins->appends([
    //         'search' => $search,
    //         'user_type' => $userType,
    //         'created_date' => $date,
    //         'status' => $status
    //     ]);

    //     return view('Employees.AdminList', compact('admins', 'search', 'userType', 'date', 'status'));
    // }
public function AdminList(Request $request)
{
    $search = $request->input('search');           // Single search text
    $fromDate = $request->input('from_date');     // From date
    $toDate = $request->input('to_date');         // To date

    $admins = Employee::when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('user_type', 'like', '%' . $search . '%')
                  ->orWhere('user_status', 'like', '%' . $search . '%');
            });
        })
        ->when($fromDate, function ($query, $fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        })
        ->when($toDate, function ($query, $toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        })
        ->orderBy('created_at', 'asc')
        ->paginate(10);

    // Preserve filters in pagination links
    $admins->appends([
        'search' => $search,
        'from_date' => $fromDate,
        'to_date' => $toDate
    ]);

    return view('Employees.AdminList', compact('admins', 'search', 'fromDate', 'toDate'));
}



    public function StoreList()
    {
        $stores = Employee::where('user_type', 'Store')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('Employees.StoreList', compact('stores'));
    }


    public function ProductionList()
    {
        $productions = Employee::where('user_type', 'Production')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('Employees.ProductionList', compact('productions'));
    }
    public function InspectionList()
    {
        $inspections = Employee::where('user_type', 'Inspection')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('Employees.InspectionList', compact('inspections'));
    }


    public function show($id)
    {
        $employee = Employee::findOrFail($id);
        return view('Employees.ViewEmployee', compact('employee'));
    }

    // Edit form
    public function editEmployee($id)
    {
        $employee = Employee::findOrFail($id);

        // Optional: You can conditionally load a different view if required
        if ($employee->user_type === 'Admin') {
            return view('Employees.EditAdmin', compact('employee'));
        } else {
            return view('Employees.EditEmployee', compact('employee'));
        }
    }


    // Update employee
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'user_type' => 'required|in:Admin,Store,Production,Inspection', // ✅ Updated
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'adhar_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'pan_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // ✅ Custom file upload helper
        function uploadToStorage1($file, $folder)
        {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destinationPath = base_path("/public/storage/$folder");

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);

            return "$folder/$fileName";
        }

        // ✅ File uploads
        if ($request->hasFile('avatar')) {
            $validatedData['avatar'] = uploadToStorage1($request->file('avatar'), 'avatars');
        }

        if ($request->hasFile('adhar_image')) {
            $validatedData['adhar_image'] = uploadToStorage1($request->file('adhar_image'), 'adhar_images');
        }

        if ($request->hasFile('pan_image')) {
            $validatedData['pan_image'] = uploadToStorage1($request->file('pan_image'), 'pan_images');
        }

        // ✅ Update employee data
        $employee->update($validatedData);

        // ✅ Redirect based on user_type
        switch ($employee->user_type) {
            case 'Admin':
                return redirect()->route('admin.list')->with('success', 'Admin updated successfully!');
            case 'Store':
                return redirect()->route('store.list')->with('success', 'Store user updated successfully!');
            case 'Production':
                return redirect()->route('production.list')->with('success', 'Production user updated successfully!');
            case 'Inspection':
                return redirect()->route('inspection.list')->with('success', 'Inspection user updated successfully!');
            default:
                return redirect()->route('dashboard')->with('success', 'User updated successfully!');
        }
    }


    // Delete employee
    public function adminDestroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->route('admin.list')->with('success', 'Admin deleted successfully!');
    }
    public function storeDestroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->route('store.list')->with('success', 'Store deleted successfully!');
    }
    public function productionDestroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->route('production.list')->with('success', 'Production deleted successfully!');
    }
    public function inspectionDestroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->route('inspection.list')->with('success', 'Inspection deleted successfully!');
    }

    public function activate($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->user_status = 'Active';
        $employee->save();

        $message = ucfirst($employee->user_type) . ' activated successfully!';

        return back()->with('success', $message);
    }

    public function deactivate($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->user_status = 'Inactive';
        $employee->save();

        $message = ucfirst($employee->user_type) . ' deactivated successfully!';

        return back()->with('success', $message);
    }



    public function myProfile()
    {
        // ✅ Check if Admin is logged in
        if (session('admin')) {
            $admin = (object) [
                'id' => session('admin_id'),
                'name' => session('admin_name'),
                'email' => session('admin_email'),
                'avatar' => session('admin_image') ?? null,
                'phone' => '', // optional: set if needed
                'address' => '', // optional: set if needed
                'user_type' => 'Admin',
            ];

            return view('Employees.OperatorMyProfile', ['employee' => $admin, 'role' => 'admin']);
        }

        // ✅ Check if Employee is logged in
        if (session('employee_logged_in')) {
            $employee = Employee::find(session('employee_id'));

            if (!$employee) {
                return redirect()->route('login')->with('error', 'User not found.');
            }

            return view('Employees.OperatorMyProfile', ['employee' => $employee, 'role' => 'employee']);
        }


        return redirect()->route('login')->with('error', 'Please log in to view your profile.');
    }


    public function updateMyProfile(Request $request)
    {
        // ✅ If Admin
        if (session('admin_logged_in')) {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $validatedData['avatar'] = $request->file('avatar')->store('avatars', 'public');
                session(['admin_image' => $validatedData['avatar']]);
            }

            session(['admin_name' => $validatedData['name']]);
            session(['admin_email' => $validatedData['email']]);

            return redirect()->back()->with('success', 'Admin profile updated successfully!');
        }

        // ✅ If Employee
        if (session('employee_logged_in')) {
            $employee = Employee::find(session('employee_id'));

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:employees,email,' . $employee->id,
                'phone' => 'required|string|max:20',
                'address' => 'nullable|string',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'user_type' => 'required|in:Admin,Operator',
            ]);

            if ($request->hasFile('avatar')) {
                if ($employee->avatar && Storage::disk('public')->exists($employee->avatar)) {
                    Storage::disk('public')->delete($employee->avatar);
                }
                $validatedData['avatar'] = $request->file('avatar')->store('avatars', 'public');
            }

            $employee->update($validatedData);

            return redirect()->back()->with('success', 'Employee profile updated successfully!');
        }

        return redirect()->route('login')->with('error', 'Unauthorized action.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        // ✅ If Admin
        if (session('admin_logged_in')) {
            $adminEmail = 'admin@gmail.com';
            $adminPassword = 'admin123';

            if (
                session('admin_email') === $adminEmail &&
                $request->current_password === $adminPassword
            ) {
                // 🔒 Hardcoded password cannot be changed here
                return back()->with('error', 'Admin password is static and cannot be changed.');
            }

            return back()->with('error', 'Unauthorized access.');
        }

        // ✅ If Employee
        if (session('employee_logged_in')) {
            $employee = Employee::find(session('employee_id'));

            if (!$employee || !Hash::check($request->current_password, $employee->password)) {
                return back()->with('error', 'Current password is incorrect.');
            }

            $employee->password = Hash::make($request->new_password);
            $employee->save();

            return back()->with('success', 'Password updated successfully.');
        }

        return redirect()->route('login')->with('error', 'Unauthorized access.');
    }


    public function employeeDestroy($id = null)
    {
        if (session('admin_logged_in')) {
            session()->flush();
            return redirect()->route('login')->with('success', 'Admin account logged out successfully.');
        }

        if (session('employee_logged_in')) {
            $employee = Employee::findOrFail(session('employee_id'));
            $employee->delete();
            session()->flush();
            return redirect()->route('login')->with('success', 'Your account has been deleted successfully.');
        }

        return redirect()->route('login')->with('error', 'Unauthorized action.');
    }

}
