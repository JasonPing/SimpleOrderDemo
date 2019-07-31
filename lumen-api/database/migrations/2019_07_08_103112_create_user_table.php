<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('name');
          $table->string('password');
          $table->string('api_key');
          $table->timestamps();
        });

        // Initial user data
        DB::table('user')->insert(
          array(
            'name' => 'user1',
            'password' => '$2y$10$Ye11wNNCsBvyDbxxG999G.Lyz7WI2pCjJITRiHDRjhyRmQcL7nj1u',
            'api_key' => ''
          )
        );
        DB::table('user')->insert(
          array(
            'name' => 'user2',
            'password' => '$2y$10$FlP5/kbFP6IgxoT./8y6veKt6jnz4xxR0.Eq0/d0jAC.D1NruOuH2',
            'api_key' => ''
          )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
