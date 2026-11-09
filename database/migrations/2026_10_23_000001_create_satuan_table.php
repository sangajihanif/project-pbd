<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('satuan', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('idsatuan', true); // INT AUTO_INCREMENT (signed)
            $table->string('nama_satuan', 45);
            $table->tinyInteger('status')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('satuan');
    }
};
