<?php

namespace App\Models;

use App\Models\Concerns\ReadOnlyModel;
use Illuminate\Database\Eloquent\Model;

class VSaKartuStok extends Model
{
    use ReadOnlyModel;

    protected $table = 'view_sa_kartu_stok';
    protected $primaryKey = 'idkartu_stok';
}
