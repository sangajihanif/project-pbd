<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMarginPenjualanRequest;
use App\Models\MarginPenjualan;
use App\Models\VMarginPenjualan;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MarginPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $margins = VMarginPenjualan::with('user')->get();

        return view('margin.index', compact('margins'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateMarginPenjualanRequest $request)
    {
        $payload = $request->validated();

        $margin = MarginPenjualan::create($payload + ['status' => 0, 'iduser' => Auth::id()]);

        // margin baru langsung dipakai; yang lain otomatis nonaktif (hanya satu yang aktif)
        DB::statement('CALL sp_aktifkan_margin(?)', [$margin->idmargin_penjualan]);

        return redirect()->route('margin.index')->with('success', 'Margin penjualan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateMarginPenjualanRequest $request, $id)
    {
        $payload = $request->validated();

        MarginPenjualan::findOrFail($id)->update($payload);

        return redirect()->route('margin.index')->with('success', 'Margin penjualan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $margin = MarginPenjualan::findOrFail($id);

        try {
            $margin->delete();
        } catch (QueryException $e) {
            return back()->with('error', "Margin {$margin->persen}% tidak dapat dihapus: " . $this->pesanDb($e)
                . ' Gunakan tombol Nonaktifkan.');
        }

        return redirect()->route('margin.index')->with('success', 'Margin penjualan berhasil dihapus.');
    }

    public function toggleStatus(MarginPenjualan $margin)
    {
        if ($margin->status) {
            $margin->update(['status' => 0]);
        } else {
            // mengaktifkan satu margin otomatis menonaktifkan margin lainnya
            DB::statement('CALL sp_aktifkan_margin(?)', [$margin->idmargin_penjualan]);
        }

        $pesan = $margin->fresh()->status ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Margin {$margin->persen}% {$pesan}.");
    }
}
