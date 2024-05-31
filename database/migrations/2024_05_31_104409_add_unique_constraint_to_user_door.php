<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_door', function (Blueprint $table) {
            $table->unique(['user_id', 'door_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_door', function (Blueprint $table) {
            // Menghapus unique constraint
            $table->dropUnique(['user_id', 'door_id']);
        });
    }
};
