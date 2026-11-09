<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('iddetail_penjualan', true);
            $table->integer('harga_satuan');
            $table->integer('jumlah');
            $table->integer('subtotal');
            $table->integer('penjualan_idpenjualan');
            $table->integer('idbarang');

            $table->foreign('penjualan_idpenjualan', 'fk_detpenjualan_penjualan')->references('idpenjualan')->on('penjualan');
            $table->foreign('idbarang', 'fk_detpenjualan_barang')->references('idbarang')->on('barang');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan');
    }
};
