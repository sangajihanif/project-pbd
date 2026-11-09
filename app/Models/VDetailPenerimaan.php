<?php

namespace App\Models;

use App\Models\Concerns\ReadOnlyModel;
use Illuminate\Database\Eloquent\Model;

class VDetailPenerimaan extends Model
{
    use ReadOnlyModel;

    protected $table = 'view_detail_penerimaan';
    protected $primaryKey = 'iddetail_penerimaan';
}
