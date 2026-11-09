<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    // Halaman utama langsung diarahkan ke halaman login
    public function test_halaman_utama_diarahkan_ke_login(): void
    {
        $this->get('/')->assertRedirect('/login');
    }
}
