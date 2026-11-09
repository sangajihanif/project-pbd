<?php

namespace App\Models;

use App\Models\Concerns\ReadOnlyModel;
use Illuminate\Database\Eloquent\Model;

class VSaPenjualan extends Model
{
    use ReadOnlyModel;

    protected $table = 'view_sa_penjualan';
    protected $primaryKey = 'idpenjualan';
}
