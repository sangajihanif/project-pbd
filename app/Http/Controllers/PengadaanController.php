<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\VBarang;
use App\Models\VDetailPengadaan;
use App\Models\VPenerimaan;
use App\Models\VPengadaan;
use App\Models\Vendor;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengadaanController extends Controller
{
    public function index()
    {
        $pengadaan = VPengadaan::orderByDesc('idpengadaan')->paginate(10);

        return view('pengadaan.index', compact('pengadaan'));
    }

    // Form pengadaan: pilih vendor aktif + isi jumlah & harga beli tiap barang aktif
    public function create()
    {
        $vendor = Vendor::where('status', 'A')->orderBy('nama_vendor')->get();
        $barang = VBarang::where('status', 1)->orderBy('nama_barang')->get();

        return view('pengadaan.create', compact('vendor', 'barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idvendor'        => 'required|integer',
            'items'           => 'required|array',
            'items.*.jumlah'  => 'nullable|integer|min:0',
            'items.*.harga'   => 'nullable|integer|min:0',
        ]);

        // idbarang => [jumlah, harga]; buang yang jumlahnya 0
        $items = collect($request->input('items'))
            ->filter(fn ($i) => (int) ($i['jumlah'] ?? 0) > 0);

        if ($items->isEmpty()) {
            return back()->withInput()->with('error', 'Isi jumlah minimal satu barang.');
        }

        try {
            $id = DB::transaction(function () use ($request, $items) {
                DB::statement('CALL sp_buat_pengadaan(?, ?, @new_id)', [Auth::id(), (int) $request->idvendor]);
                $id = DB::selectOne('SELECT @new_id AS id')->id;

                foreach ($items as $idbarang => $i) {
                    $harga = ($i['harga'] ?? '') !== ''
                        ? (int) $i['harga']
                        : (int) Barang::where('idbarang', $idbarang)->value('harga');

                    DB::statement('CALL sp_tambah_item_pengadaan(?, ?, ?, ?)', [
                        $id, (int) $idbarang, (int) $i['jumlah'], $harga,
                    ]);
                }

                return $id;
            });
        } catch (QueryException $e) {
            return back()->withInput()->with('error', $this->pesanDb($e));
        }

        return redirect()->route('pengadaan.show', $id)->with('success', 'Pengadaan berhasil dibuat.');
    }

    public function show($id)
    {
        $header = VPengadaan::findOrFail($id);
        $detail = VDetailPengadaan::where('idpengadaan', $id)->orderBy('iddetail_pengadaan')->get();
        $penerimaan = VPenerimaan::where('idpengadaan', $id)->orderBy('idpenerimaan')->get();

        return view('pengadaan.show', compact('header', 'detail', 'penerimaan'));
    }

    // Hanya pengadaan yang belum punya penerimaan yang boleh dihapus (dicek di stored procedure)
    public function destroy($id)
    {
        VPengadaan::findOrFail($id);

        try {
            DB::statement('CALL sp_hapus_pengadaan(?)', [$id]);
        } catch (QueryException $e) {
            return back()->with('error', $this->pesanDb($e));
        }

        return redirect()->route('pengadaan.index')->with('success', 'Pengadaan berhasil dihapus.');
    }
}
