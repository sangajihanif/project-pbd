<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'idpenjualan';
    public $timestamps = false;

    public function marginPenjualan()
    {
        return $this->belongsTo(MarginPenjualan::class, 'idmargin_penjualan', 'idmargin_penjualan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'id');
    }
}
