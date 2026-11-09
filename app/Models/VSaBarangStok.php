<?php

namespace App\Models;

use App\Models\Concerns\ReadOnlyModel;
use Illuminate\Database\Eloquent\Model;

class VSaBarangStok extends Model
{
    use ReadOnlyModel;

    protected $table = 'view_sa_barang_stok';
    protected $primaryKey = 'idbarang';
}
