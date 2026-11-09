<?php

namespace App\Models;
use App\Models\Concerns\ReadOnlyModel;
use Illuminate\Database\Eloquent\Model;

class VBarang extends Model
{
    use ReadOnlyModel;

    protected $table = 'view_barang';
    protected $primaryKey = 'idbarang';
}