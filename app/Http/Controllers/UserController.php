<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Parties;
use App\Models\Role;
use App\Models\User;
use App\Models\UserTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['role', 'division'])->get();
        // $users = User::whereHas('role', function($query) {
        //     $query->where('role_name', '!=', 'ADMIN');
        // })->with(['role', 'division'])->get();

        return Inertia::render('Admin/UserManagement/User/User', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $divisions = Division::all();

        return Inertia::render('Admin/UserManagement/User/UserCreate', compact('roles', 'divisions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
        [
            'fullname' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|string|email|unique:users,email',
            'phone' => 'required|string',
            'password' => 'required|string',
            'role_id' => 'required|numeric|exists:roles,id',
            'division_id' => 'required|numeric|exists:divisions,id',
        ],
        [
            'fullname.required' => 'Fullname harus diisi',
            'username.requried' => 'Username harus diisi',
            'username.unique' => "Username ".$request->input('username')." sudah ada",
            'email.required' => 'Email harus diisi',
            'email.email' => 'Bentuk email harus seperti jhondoe@gmail.com',
            'email.unique'=> "Email ".$request->input('email')." sudah ada",
            'password.required' => 'Password harus diisi',
            'role_id.required' => 'Role harus diisi',
            'division_id.required' => 'Division harus diisi',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'fullname' => $request->input('fullname'),
                'user_uid' => rand(10000,66666),
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'phone' => $request->phone,
                'password' => Hash::make($request->input('password')),
                'role_id' => $request->input('role_id'),
                'division_id' => $request->input('division_id'),
            ]);

            // Cek apakah divisi user adalah "SALES"
            $division = Division::find($request->input('division_id'));
            if ($division && $division->division_name === 'SALES') {
                // Buat UserTarget kosong untuk user SALES
                $userTarget = new UserTarget([
                    'annual_target' => 0,
                    'monthly_target' => 0,
                    'period' => now()->year,
                ]);

                // Associate UserTarget dengan User dan simpan
                $userTarget->user()->associate($user);
                $userTarget->save();
            }

            DB::commit();

            return redirect(route('admin.user-management'))->with('success', 'User berhasil dibuat!');
        } catch(\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Error Internal!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $divisions = Division::all();
        $user = $user->load(['role', 'division']);

        return Inertia::render('Admin/UserManagement/User/UserEdit', compact('user', 'roles','divisions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'fullname' => 'required|string',
            'username' => 'required|string|unique:users,username,' . $user->id,
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|confirmed',
            'password_confirmation' => 'same:password',
            'division_id' => 'required|numeric|exists:divisions,id',
            'role_id' => 'required|numeric|exists:roles,id',
            'phone' => 'required|string'
        ]);

        DB::beginTransaction();

        try {
            // Start the update process
            $user->fullname = $request->input('fullname');
            $user->username = $request->input('username');
            $user->email = $request->input('email');
            $user->division_id = $request->input('division_id');
            $user->role_id = $request->input('role_id');
            $user->phone = $request->phone;
    
            // Update password only if it's provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }
    
            // Update password only if it's provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }
    
            $user->save();

            DB::commit();

            return redirect()->route('admin.user-management')->with('success', 'User berhasil diperbarui!');
        } catch(\Exception $e){
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->back()->with('success', 'User behasil dihapus');
    }

    /**
     * Index all sales user for assign the customer from data master (admin)
     * 
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function indexSalesUser(Request $request): Response
    {
        $sales_users = User::whereHas('division', function ($query) {
            $query->where('division_name', 'SALES');
        })
            ->with('division')
            ->orderByDesc('created_at')
            ->paginate(10);

        return Inertia::render('Admin/CustomerSales', compact('sales_users'));
    }

    public function assignCustomerSales(User $user)
    {
        $user
            ->load('division','parties')
            ->orderByDesc('updated_at')
            ->first();
        $customers = Parties::where('type_parties', 'CUSTOMER')
            ->where('users_id', null)
            ->orderByDesc('created_at')
            ->get();
    
        return Inertia::render('Admin/AssignCustomer', compact('user', 'customers'));
    }

}
