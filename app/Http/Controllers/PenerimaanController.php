<?php

namespace App\Http\Controllers;

use App\Models\VDetailPenerimaan;
use App\Models\VDetailPengadaan;
use App\Models\VPenerimaan;
use App\Models\VPengadaan;
use App\Models\VRetur;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
{
    public function index()
    {
        $penerimaan = VPenerimaan::orderByDesc('idpenerimaan')->paginate(10);

        return view('penerimaan.index', compact('penerimaan'));
    }

    // Form penerimaan untuk satu pengadaan: tampilkan sisa barang yang belum datang
    public function create($idpengadaan)
    {
        $header = VPengadaan::findOrFail($idpengadaan);

        if ($header->status !== 'P') {
            return redirect()->route('pengadaan.show', $idpengadaan)
                ->with('error', 'Pengadaan ini sudah selesai diterima.');
        }

        $detail = VDetailPengadaan::where('idpengadaan', $idpengadaan)
            ->where('sisa', '>', 0)
            ->orderBy('iddetail_pengadaan')
            ->get();

        return view('penerimaan.create', compact('header', 'detail'));
    }

    public function store(Request $request, $idpengadaan)
    {
        VPengadaan::findOrFail($idpengadaan);

        $request->validate([
            'items'   => 'required|array',
            'items.*' => 'nullable|integer|min:0',
        ]);

        // idbarang => jumlah, buang yang 0
        $items = collect($request->input('items'))
            ->map(fn ($qty) => (int) $qty)
            ->filter(fn ($qty) => $qty > 0);

        if ($items->isEmpty()) {
            return back()->with('error', 'Isi jumlah diterima minimal satu barang.');
        }

        try {
            $id = DB::transaction(function () use ($idpengadaan, $items) {
                DB::statement('CALL sp_buat_penerimaan(?, ?, @new_id)', [(int) $idpengadaan, Auth::id()]);
                $id = DB::selectOne('SELECT @new_id AS id')->id;

                foreach ($items as $idbarang => $qty) {
                    DB::statement('CALL sp_tambah_item_penerimaan(?, ?, ?)', [$id, (int) $idbarang, $qty]);
                }

                return $id;
            });
        } catch (QueryException $e) {
            return back()->withInput()->with('error', $this->pesanDb($e));
        }

        return redirect()->route('penerimaan.show', $id)->with('success', 'Penerimaan barang berhasil dicatat.');
    }

    public function show($id)
    {
        $header = VPenerimaan::findOrFail($id);
        $detail = VDetailPenerimaan::where('idpenerimaan', $id)->orderBy('iddetail_penerimaan')->get();
        $retur = VRetur::where('idpenerimaan', $id)->orderBy('idretur')->get();

        return view('penerimaan.show', compact('header', 'detail', 'retur'));
    }
}
