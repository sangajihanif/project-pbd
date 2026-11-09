<?php

namespace App\Http\Controllers;

use App\Models\VKasirBarang;
use App\Models\VKasirDetailPenjualan;
use App\Models\VKasirPenjualan;
use App\Models\VSaPenjualan;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    // Super Admin: semua penjualan (view_sa_penjualan)
    // Kasir     : hanya penjualannya sendiri (view_kasir_penjualan)
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->idrole == 1;

        $penjualan = $isAdmin
            ? VSaPenjualan::orderByDesc('idpenjualan')->paginate(10)
            : VKasirPenjualan::where('iduser', $user->id)->orderByDesc('idpenjualan')->paginate(10);

        return view('penjualan.index', compact('penjualan', 'isAdmin'));
    }

    // Form kasir: hanya barang aktif yang masih ada stok
    public function create()
    {
        $barang = VKasirBarang::where('stok', '>', 0)->orderBy('nama_barang')->get();

        return view('penjualan.create', compact('barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'   => 'required|array',
            'items.*' => 'nullable|integer|min:0',
        ]);

        // idbarang => jumlah, buang yang jumlahnya 0
        $items = collect($request->input('items'))
            ->map(fn ($qty) => (int) $qty)
            ->filter(fn ($qty) => $qty > 0);

        if ($items->isEmpty()) {
            return back()->with('error', 'Isi jumlah minimal satu barang.');
        }

        try {
            $idpenjualan = DB::transaction(function () use ($items) {
                DB::statement('CALL sp_buat_penjualan(?, @new_id)', [Auth::id()]);
                $id = DB::selectOne('SELECT @new_id AS id')->id;

                foreach ($items as $idbarang => $qty) {
                    DB::statement('CALL sp_tambah_item_penjualan(?, ?, ?)', [$id, (int) $idbarang, $qty]);
                }

                return $id;
            });
        } catch (QueryException $e) {
            // pesan dari SIGNAL di trigger / stored procedure
            return back()->withInput()->with('error', $this->pesanDb($e));
        }

        return redirect()->route('penjualan.show', $idpenjualan)
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    public function show($id)
    {
        $user = Auth::user();
        $isAdmin = $user->idrole == 1;

        $header = $isAdmin
            ? VSaPenjualan::findOrFail($id)
            : VKasirPenjualan::where('iduser', $user->id)->where('idpenjualan', $id)->firstOrFail();

        $detail = VKasirDetailPenjualan::where('idpenjualan', $id)->get();

        return view('penjualan.show', compact('header', 'detail', 'isAdmin'));
    }

    // Super Admin: laporan per kasir dari stored procedure
    public function laporan(Request $request)
    {
        $data = $request->validate([
            'dari'   => 'nullable|date',
            'sampai' => 'nullable|date|after_or_equal:dari',
        ]);

        $dari = $data['dari'] ?? now()->startOfMonth()->toDateString();
        $sampai = $data['sampai'] ?? now()->toDateString();

        $laporan = DB::select('CALL sp_laporan_penjualan(?, ?)', [$dari, $sampai]);

        return view('penjualan.laporan', compact('laporan', 'dari', 'sampai'));
    }
}
