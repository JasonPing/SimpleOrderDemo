<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->bigIncrements('admin_id');
            $table->string('name');
            $table->string('password');
            $table->string('api_key');
            $table->timestamps();
        });

        // Initial admin data
        DB::table('admin')->insert(
          array(
            'name' => 'admin1',
            'password' => '$2y$10$A1UN6HCWy/9Rob9Epsvf1.eKBpRXO/yQPDBFfmzb0DLKQ/JCEa0ya',
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
        Schema::dropIfExists('admin');
    }
}
