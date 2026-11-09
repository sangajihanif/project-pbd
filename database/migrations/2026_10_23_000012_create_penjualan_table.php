<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('idpenjualan', true);
            $table->timestamp('created_at')->useCurrent();
            $table->integer('subtotal_nilai')->default(0);
            $table->integer('ppn')->default(0);
            $table->integer('total_nilai')->default(0);
            $table->integer('iduser');
            $table->integer('idmargin_penjualan');

            $table->foreign('iduser', 'fk_penjualan_user')->references('id')->on('users');
            $table->foreign('idmargin_penjualan', 'fk_penjualan_margin')->references('idmargin_penjualan')->on('margin_penjualan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
