<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penerimaan', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('idpenerimaan', true);
            $table->timestamp('created_at')->useCurrent();
            $table->char('status', 1)->default('P');
            $table->bigInteger('idpengadaan');
            $table->integer('iduser');

            $table->foreign('idpengadaan', 'fk_penerimaan_pengadaan')->references('idpengadaan')->on('pengadaan');
            $table->foreign('iduser', 'fk_penerimaan_user')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penerimaan');
    }
};
