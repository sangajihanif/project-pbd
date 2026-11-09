<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $table = 'satuan';
    protected $primaryKey = 'idsatuan';
    public $timestamps = false;
    protected $fillable = ['nama_satuan', 'status'];

    // Satu satuan bisa dipakai banyak barang
    public function barang()
    {
        return $this->hasMany(Barang::class, 'idsatuan', 'idsatuan');
    }
}
