<?php

namespace App\Http\Controllers;

use App\Http\Requests\SatuanRequest;
use App\Models\Satuan;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $satuan = Satuan::query()
            ->when($q !== '', fn ($query) => $query->where('nama_satuan', 'like', "%{$q}%"))
            ->orderBy('nama_satuan')
            ->paginate(10)
            ->withQueryString();

        return view('satuan.index', compact('satuan', 'q'));
    }

    public function store(SatuanRequest $request)
    {
        Satuan::create($request->validated() + ['status' => 1]);

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil ditambahkan.');
    }

    public function update(SatuanRequest $request, $id)
    {
        Satuan::findOrFail($id)->update($request->validated());

        return redirect()->route('satuan.index')->with('success', 'Perubahan satuan disimpan.');
    }

    public function toggleStatus(Satuan $satuan)
    {
        $satuan->update(['status' => $satuan->status ? 0 : 1]);

        $pesan = $satuan->status ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Satuan \"{$satuan->nama_satuan}\" {$pesan}.");
    }

    public function destroy($id)
    {
        $satuan = Satuan::findOrFail($id);

        try {
            $satuan->delete();
        } catch (QueryException $e) {
            return back()->with('error', "Satuan \"{$satuan->nama_satuan}\" tidak dapat dihapus: " . $this->pesanDb($e));
        }

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil dihapus.');
    }
}
