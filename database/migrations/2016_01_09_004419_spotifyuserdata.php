<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Spotifyuserdata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('email');
        });

        Schema::create('playlists', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
