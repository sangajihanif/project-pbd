<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBarangRequest;
use App\Models\Barang;
use App\Models\Satuan;
use App\Models\VBarang;
use App\Models\VKasirBarang;
use App\Models\VSaBarangStok;
use App\Models\VSaKartuStok;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    // Daftar barang + pencarian (?q=...). Data dibaca dari VIEW v_barang.
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        // Selain Super Admin (mis. Kasir): tampilan ringkas & read-only dari view_kasir_barang
        if (Auth::user()->idrole != 1) {
            $barang = VKasirBarang::query()
                ->when($q !== '', fn ($query) => $query->where('nama_barang', 'like', "%{$q}%"))
                ->orderBy('nama_barang')
                ->paginate(10)
                ->withQueryString();

            return view('barang.index_kasir', compact('barang', 'q'));
        }

        $barang = VBarang::query()
            ->when($q !== '', fn ($query) => $query->where('nama_barang', 'like', "%{$q}%"))
            ->orderBy('nama_barang')
            ->paginate(10)
            ->withQueryString();

            $satuan = Satuan::where('status', 1)->orderBy('nama_satuan')->get();

        return view('barang.index', compact('barang', 'q', 'satuan'));

    }

    // Super Admin: harga dasar, harga jual, stok (view_sa_barang_stok) + kartu stok
    public function stok()
    {
        $barang = VSaBarangStok::orderBy('nama_barang')->get();
        $kartu = VSaKartuStok::orderByDesc('idkartu_stok')->take(20)->get();

        return view('barang.stok', compact('barang', 'kartu'));
    }

   

    public function store(CreateBarangRequest $request)
    {
        $data = $request->validated();

        Barang::create($data + ['status' => 1]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

   

    public function update(CreateBarangRequest $request, $id)
    {
        $data = $request->validated();

        Barang::findOrFail($id)->update($data);

        return redirect()->route('barang.index')->with('success', 'Perubahan barang disimpan.');
    }

    // Barang yang belum pernah dipakai transaksi boleh dihapus.
    // Kalau sudah dipakai (foreign key), gunakan Nonaktifkan.
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);

        try {
            $barang->delete();
        } catch (QueryException $e) {
            return back()->with('error', "Barang \"{$barang->nama}\" tidak dapat dihapus: " . $this->pesanDb($e)
                . ' Gunakan tombol Nonaktifkan.');
        }

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }

    public function toggleStatus(Barang $barang)
    {
        $barang->update(['status' => $barang->status ? 0 : 1]);

        $pesan = $barang->status ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Barang \"{$barang->nama}\" {$pesan}.");
    }
}
