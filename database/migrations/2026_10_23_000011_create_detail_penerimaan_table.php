<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penerimaan', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('iddetail_penerimaan', true);
            $table->bigInteger('idpenerimaan');
            $table->integer('barang_idbarang');
            $table->integer('jumlah_terima');
            $table->integer('harga_satuan_terima');
            $table->integer('sub_total_terima');

            $table->foreign('idpenerimaan', 'fk_detpenerimaan_penerimaan')->references('idpenerimaan')->on('penerimaan');
            $table->foreign('barang_idbarang', 'fk_detpenerimaan_barang')->references('idbarang')->on('barang');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penerimaan');
    }
};
