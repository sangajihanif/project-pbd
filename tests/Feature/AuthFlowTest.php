<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\Concerns\SiapkanData;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use DatabaseTransactions, SiapkanData;

    public function test_registrasi_otp_lalu_masuk_sebagai_tamu(): void
    {
        Mail::fake();

        $this->post('/register', [
            'name' => 'Pengguna Baru', 'email' => 'baru@example.test',
            'password' => 'rahasia123', 'password_confirmation' => 'rahasia123',
        ])->assertSessionHasNoErrors();

        $user = User::where('email', 'baru@example.test')->firstOrFail();
        $this->assertSame(4, (int) $user->idrole);          // otomatis Tamu
        $this->assertNull($user->email_verified_at);

        // OTP salah ditolak
        $this->post("/otp/{$user->id}", array_combine(
            ['first', 'second', 'third', 'fourth', 'fifth', 'sixth'], [0, 0, 0, 0, 0, 0]
        ))->assertSessionHas('error');
        $this->assertNull($user->fresh()->email_verified_at);

        // OTP benar (diambil dari tabel otps)
        $token = DB::table('otps')->where('identifier', 'baru@example.test')->latest('id')->value('token');
        $this->assertNotEmpty($token);

        $this->post("/otp/{$user->id}", array_combine(
            ['first', 'second', 'third', 'fourth', 'fifth', 'sixth'], str_split((string) $token)
        ))->assertRedirect(route('barang.index'));

        $this->assertNotNull($user->fresh()->email_verified_at);
        $this->assertAuthenticatedAs($user);
    }

    public function test_registrasi_validasi(): void
    {
        $this->post('/register', ['name' => '', 'email' => 'bukan-email', 'password' => '123', 'password_confirmation' => '456'])
            ->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_login_diarahkan_sesuai_role(): void
    {
        $this->post('/login', ['email' => $this->superAdmin()->email, 'password' => 'password123'])
            ->assertRedirect(route('dashboard'));
        $this->post('/logout');

        $this->post('/login', ['email' => $this->gudang()->email, 'password' => 'password123'])
            ->assertRedirect(route('pengadaan.index'));
        $this->post('/logout');

        $this->post('/login', ['email' => $this->kasir()->email, 'password' => 'password123'])
            ->assertRedirect(route('penjualan.index'));
        $this->post('/logout');

        $this->post('/login', ['email' => $this->tamu()->email, 'password' => 'password123'])
            ->assertRedirect(route('barang.index'));
    }

    public function test_login_gagal_dan_belum_verifikasi(): void
    {
        $user = $this->kasir();

        $this->post('/login', ['email' => $user->email, 'password' => 'salahsalah'])->assertSessionHas('error');
        $this->assertGuest();

        $user->forceFill(['email_verified_at' => null])->save();
        $this->post('/login', ['email' => $user->email, 'password' => 'password123'])
            ->assertRedirect(route('otp', ['id' => $user->id]));
        $this->assertGuest();
    }

    public function test_akun_belum_verifikasi_tidak_bisa_masuk_halaman_internal(): void
    {
        $user = $this->kasir();
        $user->forceFill(['email_verified_at' => null])->save();

        $this->actingAs($user)->get('/penjualan')->assertRedirect();
    }

    public function test_logout(): void
    {
        $this->actingAs($this->kasir())->post('/logout')->assertRedirect('login');
        $this->assertGuest();
    }
}
