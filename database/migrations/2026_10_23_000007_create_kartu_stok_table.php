<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kartu_stok', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('idkartu_stok', true);
            $table->char('jenis_transaksi', 1);
            $table->integer('masuk')->default(0);
            $table->integer('keluar')->default(0);
            $table->integer('stock')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->integer('idtransaksi');
            $table->integer('idbarang');

            $table->foreign('idbarang', 'fk_kartustok_barang')->references('idbarang')->on('barang');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kartu_stok');
    }
};
