<?php

namespace App\Models;

use App\Models\Penjualan;
use Illuminate\Database\Eloquent\Model;

class MarginPenjualan extends Model
{
    protected $table = 'margin_penjualan';
    protected $primaryKey = 'idmargin_penjualan';

    protected $fillable = ['persen', 'status', 'iduser'];
    
    public function user(){
        return $this->belongsTo(User::class, 'iduser', 'id');
    }

    public function penjualan(){
        return $this->hasMany(Penjualan::class, 'idmargin_penjualan', 'idmargin_penjualan');
    }
}
