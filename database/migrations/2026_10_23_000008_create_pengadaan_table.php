<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengadaan', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('idpengadaan', true);
            $table->timestamp('timestamp')->useCurrent();
            $table->integer('iduser');
            $table->char('status', 1)->default('P');
            $table->integer('vendor_idvendor');
            $table->integer('subtotal_nilai')->default(0);
            $table->integer('ppn')->default(0);
            $table->integer('total_nilai')->default(0);

            $table->foreign('iduser', 'fk_pengadaan_user')->references('id')->on('users');
            $table->foreign('vendor_idvendor', 'fk_pengadaan_vendor')->references('idvendor')->on('vendor');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengadaan');
    }
};
