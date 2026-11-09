<?php

namespace App\Models;

use App\Models\Concerns\ReadOnlyModel;
use Illuminate\Database\Eloquent\Model;

class VPenerimaan extends Model
{
    use ReadOnlyModel;

    protected $table = 'view_penerimaan';
    protected $primaryKey = 'idpenerimaan';
}
