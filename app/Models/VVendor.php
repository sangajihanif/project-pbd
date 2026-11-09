<?php

namespace App\Models;

use App\Models\Concerns\ReadOnlyModel;
use Illuminate\Database\Eloquent\Model;

class VVendor extends Model
{
    use ReadOnlyModel;
    protected $table = 'view_vendor';
    protected $primaryKey = 'idvendor';
}
