<?php

namespace App\Models\Concerns;

use LogicException;

/**
 * Untuk model yang membaca dari VIEW MySQL: hanya boleh dibaca, tidak boleh ditulis.
 *
 * Catatan: properti $timestamps / $incrementing TIDAK boleh dideklarasikan di trait,
 * karena bentrok dengan trait bawaan Eloquent (fatal error di PHP 8.4).
 * Laravel otomatis memanggil initializeReadOnlyModel() saat model dibuat.
 */
trait ReadOnlyModel
{
    public function initializeReadOnlyModel(): void
    {
        $this->timestamps = false;
        $this->incrementing = false;
    }

    public function save(array $options = [])
    {
        throw new LogicException(static::class . ' read-only.');
    }

    public function delete()
    {
        throw new LogicException(static::class . ' read-only.');
    }
}
