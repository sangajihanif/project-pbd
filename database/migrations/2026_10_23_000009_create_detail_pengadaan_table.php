<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pengadaan', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('iddetail_pengadaan', true);
            $table->integer('harga_satuan');
            $table->integer('jumlah');
            $table->integer('sub_total');
            $table->integer('idbarang');
            $table->bigInteger('idpengadaan');

            $table->foreign('idbarang', 'fk_detpengadaan_barang')->references('idbarang')->on('barang');
            $table->foreign('idpengadaan', 'fk_detpengadaan_pengadaan')->references('idpengadaan')->on('pengadaan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pengadaan');
    }
};
