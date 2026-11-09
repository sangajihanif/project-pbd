<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'idbarang';
    public $timestamps = false;
    protected $fillable = ['jenis', 'nama', 'idsatuan', 'status', 'harga'];

    // Setiap barang punya SATU satuan (barang.idsatuan -> satuan.idsatuan)
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'idsatuan', 'idsatuan');
    }
}
