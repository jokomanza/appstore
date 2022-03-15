<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('app_id');
            $table->string('user_registration_number');
            $table->enum('type', ['developer', 'owner']);
            $table->timestamps();

            $table->unique(['user_registration_number', 'app_id']);
            $table->foreign('app_id')
                ->references('id')
                ->on('apps')
                ->onDelete('cascade');
            $table->foreign('user_registration_number')
                ->references('registration_number')
                ->on('users')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
