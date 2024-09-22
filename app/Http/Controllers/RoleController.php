<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();

        return Inertia::render('Admin/UserManagement/Role/Role', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/UserManagement/Role/RoleCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate(
        [
            'role_name' => 'required|string|unique:roles,role_name',
        ],
        [
            'role_name.required' => 'Nama role harus diisi!',
            'role_name.unique' => 'Role '.$request->input('role_name').' Sudah ada'
        ]);

        DB::beginTransaction();
        
        try {
            Role::create([
                'role_name' => $request->input('role_name'),
                'role_uid' => rand(10000,666666),
            ]);
            DB::commit();

            return redirect(route('admin.role-management'))->with('success', 'Role berhasil di tambahkan!');
        } catch(\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('failed', 'Error! '.$e->getMessage());
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */ 
    public function edit(Role $role)
    {
        return Inertia::render('Admin/UserManagement/Role/RoleEdit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate(
        [
            'role_name' => 'required|string|unique:roles,role_name,' . $role->id
        ],
        [
            'role_name.required' => 'Role harus diisi',
            'role_name.unique' => 'Nama role '.$request->input('role_name').' sudah ada.'
        ]);

        DB::beginTransaction();

        try {
            $role->update([
                'role_name' => $request->input('role_name'),
            ]);

            DB::commit();

            return redirect(route('admin.role-management'))->with('success', 'Update role berhasil!');
        } catch(\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('failed', 'Error! '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->back()->with('success', 'Hapus role berhasil');
    }
}
