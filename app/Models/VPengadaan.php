<?php

namespace App\Models;

use App\Models\Concerns\ReadOnlyModel;
use Illuminate\Database\Eloquent\Model;

class VPengadaan extends Model
{
    use ReadOnlyModel;

    protected $table = 'view_pengadaan';
    protected $primaryKey = 'idpengadaan';
}
