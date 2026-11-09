<?php

namespace App\Models;

use App\Models\Concerns\ReadOnlyModel;
use Illuminate\Database\Eloquent\Model;

class VDetailPengadaan extends Model
{
    use ReadOnlyModel;

    protected $table = 'view_detail_pengadaan';
    protected $primaryKey = 'iddetail_pengadaan';
}
