<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('retur', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('idretur', true);
            $table->timestamp('created_at')->useCurrent();
            $table->bigInteger('idpenerimaan');
            $table->integer('iduser');

            $table->foreign('idpenerimaan', 'fk_retur_penerimaan')->references('idpenerimaan')->on('penerimaan');
            $table->foreign('iduser', 'fk_retur_user')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retur');
    }
};
