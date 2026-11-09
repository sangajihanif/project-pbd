<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('margin_penjualan', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('idmargin_penjualan', true);
            $table->timestamp('created_at')->useCurrent();
            $table->double('persen');
            $table->tinyInteger('status')->default(1);
            $table->integer('iduser');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('iduser', 'fk_margin_user')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('margin_penjualan');
    }
};
