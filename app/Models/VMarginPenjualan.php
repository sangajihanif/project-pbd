<?php

namespace App\Models;

use App\Models\Concerns\ReadOnlyModel;
use Illuminate\Database\Eloquent\Model;

class VMarginPenjualan extends Model
{
    use ReadOnlyModel;

    protected $table = 'view_margin_penjualan';
    protected $primaryKey = 'idmargin_penjualan';

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'id');
    }
}
