<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('idbarang', true);
            $table->char('jenis', 1);
            $table->string('nama', 45);
            $table->integer('idsatuan');
            $table->tinyInteger('status')->default(1);
            $table->integer('harga')->default(0);

            $table->foreign('idsatuan', 'fk_barang_satuan')->references('idsatuan')->on('satuan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
