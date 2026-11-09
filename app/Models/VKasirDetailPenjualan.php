<?php

namespace App\Models;

use App\Models\Concerns\ReadOnlyModel;
use Illuminate\Database\Eloquent\Model;

class VKasirDetailPenjualan extends Model
{
    use ReadOnlyModel;

    protected $table = 'view_kasir_detail_penjualan';
    protected $primaryKey = 'iddetail_penjualan';
}
