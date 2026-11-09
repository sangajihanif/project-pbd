<?php

namespace App\Models;

use App\Models\Concerns\ReadOnlyModel;
use Illuminate\Database\Eloquent\Model;

class VDetailRetur extends Model
{
    use ReadOnlyModel;

    protected $table = 'view_detail_retur';
    protected $primaryKey = 'iddetail_retur';
}
