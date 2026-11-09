<?php

namespace App\Models;

use App\Models\Concerns\ReadOnlyModel;
use Illuminate\Database\Eloquent\Model;

class VKasirPenjualan extends Model
{
    use ReadOnlyModel;

    protected $table = 'view_kasir_penjualan';
    protected $primaryKey = 'idpenjualan';
}
