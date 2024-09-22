<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DivisionController extends Controller
{
    public function index(): Response
    {
        $divisions = Division::all();

        return Inertia::render('Admin/UserManagement/Division/Division', compact('divisions'));
    }

    public function create(): Response
    {
        return Inertia::render('Admin/UserManagement/Division/DivisionCreate');
    }

    public function store(Request $request): RedirectResponse
    {   
        $request->validate(
        [
            'division_name' => 'required|string|unique:divisions,division_name',
        ],
        [
            'division_name.required' => 'Nama divisi harus diisi!',
            'division_name.unique' => 'Divisi '.$request->input('division_name').' Sudah ada.',
        ]);

        DB::beginTransaction();

        try {
            Division::create([
                'division_name' => $request->input('division_name'),
                'division_uid' => rand(10000,60000),
            ]);

            DB::commit();

            return redirect(route('admin.division-management'))->with('success', 'Divisi berhasil dibuat!');
        } catch(\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('failed', 'Error! '.$e->getMessage());
        }
    }

    public function edit(Division $division) 
    {
        return Inertia::render('Admin/UserManagement/Division/DivisionEdit', compact('division'));
    }

    public function update(Request $request, Division $division) 
    {
        $request->validate(
            [
                'division_name' => 'required|string|unique:divisions,division_name,'.$division->id,
            ],
            [
                'division_name.required' => 'Nama divisi harus diisi',
                'division_name.unique' => 'Divisi '.$request->input('division_name').' Sudah ada',
            ]
        );

        DB::beginTransaction();

        try {
            $division->update([
                'division_name' => $request->input('division_name'),
            ]);

            DB::commit();

            return redirect(route('admin.division-management'))->with('success', 'Divisi berhasil dibuat');
        } catch(\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('failed', 'Error! '.$e->getMessage() );
        }
    }

    public function destroy(Division $division)
    {
        $division->delete();

        return redirect()->back()->with('success', 'Divisi berhasil dihapus');
    }
}
