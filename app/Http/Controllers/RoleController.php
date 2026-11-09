<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Role;
use Illuminate\Database\QueryException;

class RoleController extends Controller
{
    // Role bawaan sistem (dipakai oleh kode program) tidak boleh dihapus
    private const ROLE_BAWAAN = [1, 2, 3, 4, 5];

    public function index()
    {
        $role = Role::orderBy('idrole')->get();

        return view('role.index', compact('role'));
    }

    public function store(RoleRequest $request)
    {
        Role::create($request->validated());

        return redirect()->route('role.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function update(RoleRequest $request, $id)
    {
        Role::findOrFail($id)->update($request->validated());

        return redirect()->route('role.index')->with('success', 'Perubahan role disimpan.');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if (in_array((int) $role->idrole, self::ROLE_BAWAAN, true)) {
            return back()->with('error', 'Role bawaan sistem tidak boleh dihapus.');
        }

        try {
            $role->delete();
        } catch (QueryException $e) {
            return back()->with('error', "Role \"{$role->nama_role}\" tidak dapat dihapus: " . $this->pesanDb($e));
        }

        return redirect()->route('role.index')->with('success', 'Role berhasil dihapus.');
    }
}
