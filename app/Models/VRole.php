<?php

namespace App\Models;

use App\Models\Concerns\ReadOnlyModel;
use Illuminate\Database\Eloquent\Model;

class VRole extends Model
{
    use ReadOnlyModel;

    protected $table = 'view_role';
    protected $primaryKey = 'idrole';
}
