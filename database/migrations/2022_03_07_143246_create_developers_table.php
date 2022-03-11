<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevelopersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('developers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('app_id');
            $table->string('user_registration_number');
            $table->timestamps();

            $table->unique(['user_registration_number', 'app_id']);
            $table->foreign('app_id')
                ->references('id')
                ->on('apps');
            $table->foreign('user_registration_number')
                ->references('registration_number')
                ->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('developers');
    }
}