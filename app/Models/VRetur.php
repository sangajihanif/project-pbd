<?php

namespace App\Models;

use App\Models\Concerns\ReadOnlyModel;
use Illuminate\Database\Eloquent\Model;

class VRetur extends Model
{
    use ReadOnlyModel;

    protected $table = 'view_retur';
    protected $primaryKey = 'idretur';
}
