<?php

namespace App\Models;

use App\Models\Concerns\ReadOnlyModel;
use Illuminate\Database\Eloquent\Model;

class VSatuan extends Model
{
    use ReadOnlyModel;

    protected $table = 'satuan';
    protected $primaryKey = 'idsatuan';

    
}
