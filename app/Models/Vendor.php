<?php

namespace App\Models;

use App\Models\Pengadaan;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendor';
    protected $primaryKey = 'idvendor';
    public $timestamps = false;
    
    protected $fillable = ['nama_vendor', 'badan_hukum', 'status'];

    public function pengadaan(){
        return $this->hasMany(Pengadaan::class, 'vendor_idvendor', 'idvendor');
    }
}
