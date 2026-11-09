<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_retur', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('iddetail_retur', true);
            $table->integer('jumlah');
            $table->string('alasan', 200);
            $table->bigInteger('idretur');
            $table->bigInteger('iddetail_penerimaan');

            $table->foreign('idretur', 'fk_detretur_retur')->references('idretur')->on('retur');
            $table->foreign('iddetail_penerimaan', 'fk_detretur_detpenerimaan')->references('iddetail_penerimaan')->on('detail_penerimaan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_retur');
    }
};
