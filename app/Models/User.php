<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Penjualan;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'idrole'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Halaman tujuan setelah login, sesuai role
    public function homeRoute(): string
    {
        return match ((int) $this->idrole) {
            1       => 'dashboard',        // Super Admin
            2       => 'pengadaan.index',  // Admin Gudang
            3       => 'penjualan.index',  // Kasir
            default => 'barang.index',
        };
    }

    public function role(){
        return $this->belongsTo(Role::class, 'idrole', 'idrole');
    }

    public function marginPenjualan(){
        return $this->hasMany(MarginPenjualan::class, 'iduser', 'id');
    }

    public function penjualan(){
        return $this->hasMany(Penjualan::class, 'iduser', 'id');
    }
}
