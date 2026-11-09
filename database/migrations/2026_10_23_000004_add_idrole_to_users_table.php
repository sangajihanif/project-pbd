<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
           
            $table->integer('idrole')->after('id');

            $table->foreign('idrole', 'fk_user_role')
                  ->references('idrole')
                  ->on('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('fk_user_role');
            $table->dropColumn('idrole');
        });
    }
};
