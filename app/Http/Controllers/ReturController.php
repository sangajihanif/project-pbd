<?php

namespace App\Http\Controllers;

use App\Models\VDetailPenerimaan;
use App\Models\VDetailRetur;
use App\Models\VPenerimaan;
use App\Models\VRetur;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturController extends Controller
{
    public function index()
    {
        $retur = VRetur::orderByDesc('idretur')->paginate(10);

        return view('retur.index', compact('retur'));
    }

    // Form retur untuk satu penerimaan: hanya barang yang masih bisa diretur
    public function create($idpenerimaan)
    {
        $header = VPenerimaan::findOrFail($idpenerimaan);

        $detail = VDetailPenerimaan::where('idpenerimaan', $idpenerimaan)
            ->where('sisa_retur', '>', 0)
            ->orderBy('iddetail_penerimaan')
            ->get();

        if ($detail->isEmpty()) {
            return redirect()->route('penerimaan.show', $idpenerimaan)
                ->with('error', 'Semua barang pada penerimaan ini sudah diretur.');
        }

        return view('retur.create', compact('header', 'detail'));
    }

    public function store(Request $request, $idpenerimaan)
    {
        VPenerimaan::findOrFail($idpenerimaan);

        $request->validate([
            'items'           => 'required|array',
            'items.*.jumlah'  => 'nullable|integer|min:0',
            'items.*.alasan'  => 'nullable|string|max:200',
        ]);

        // iddetail_penerimaan => [jumlah, alasan], buang yang jumlahnya 0
        $items = collect($request->input('items'))
            ->filter(fn ($i) => (int) ($i['jumlah'] ?? 0) > 0);

        if ($items->isEmpty()) {
            return back()->with('error', 'Isi jumlah retur minimal satu barang.');
        }

        try {
            $id = DB::transaction(function () use ($idpenerimaan, $items) {
                DB::statement('CALL sp_buat_retur(?, ?, @new_id)', [(int) $idpenerimaan, Auth::id()]);
                $id = DB::selectOne('SELECT @new_id AS id')->id;

                foreach ($items as $iddetail => $i) {
                    DB::statement('CALL sp_tambah_item_retur(?, ?, ?, ?)', [
                        $id, (int) $iddetail, (int) $i['jumlah'], (string) ($i['alasan'] ?? ''),
                    ]);
                }

                return $id;
            });
        } catch (QueryException $e) {
            return back()->withInput()->with('error', $this->pesanDb($e));
        }

        return redirect()->route('retur.show', $id)->with('success', 'Retur berhasil dicatat dan stok dikurangi.');
    }

    public function show($id)
    {
        $header = VRetur::findOrFail($id);
        $detail = VDetailRetur::where('idretur', $id)->orderBy('iddetail_retur')->get();

        return view('retur.show', compact('header', 'detail'));
    }
}
