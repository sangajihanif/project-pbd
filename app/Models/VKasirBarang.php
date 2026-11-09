<?php

namespace App\Models;

use App\Models\Concerns\ReadOnlyModel;
use Illuminate\Database\Eloquent\Model;

class VKasirBarang extends Model
{
    use ReadOnlyModel;

    protected $table = 'view_kasir_barang';
    protected $primaryKey = 'idbarang';
}
