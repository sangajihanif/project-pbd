<?php

namespace App\Models;

use App\Models\Concerns\ReadOnlyModel;
use Illuminate\Database\Eloquent\Model;

class VUser extends Model
{
    use ReadOnlyModel;
    protected $table = 'view_user';
    protected $primaryKey = 'iduser';
}
