<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;

abstract class Controller
{
 
    protected function pesanDb(QueryException $e): string
    {
        $state = $e->errorInfo[0] ?? '';

        if ($state === '45000') {
            return $e->errorInfo[2];
        }

        if ($state === '23000') {
            return 'Data tidak dapat dihapus/diubah karena masih dipakai oleh data lain.';
        }

        report($e);

        return 'Terjadi kesalahan pada database. Silakan coba lagi.';
    }
}
