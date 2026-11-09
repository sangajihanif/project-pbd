<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVendorRequest;
use App\Models\Vendor;
use App\Models\VVendor;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $vendors = VVendor::query()
            ->when($q !== '', fn($query) => $query->where('nama_vendor', 'LIKE', "%{$q}%"))
            ->orderBy('nama_vendor')
            ->paginate(10)
            ->withQueryString();

        return view('vendor.index', compact('vendors', 'q'));
    }
   

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateVendorRequest $request)
    {
        $data = $request->validated();

        Vendor::create($data + ['status' => 'A']);

        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vendor $vendor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateVendorRequest $request, $id)
    {
        $data = $request->validated();

        Vendor::findOrFail($id)->update($data);

        return redirect()->route('vendor.index')->with('success', 'Perubahan disimpan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);

        try {
            $vendor->delete();
        } catch (QueryException $e) {
            return back()->with('error', "Vendor \"{$vendor->nama_vendor}\" tidak dapat dihapus: " . $this->pesanDb($e)
                . ' Gunakan tombol Nonaktifkan.');
        }

        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil dihapus.');
    }

    public function toggleStatus(Vendor $vendor)
    {

        $newStatus = $vendor->status == 'A' ? 'N' : 'A';

        $vendor->update(['status' => $newStatus]);

        $pesan = $newStatus == 'A' ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Vendor \"{$vendor->nama_vendor}\" berhasil {$pesan}.");
    }
}
