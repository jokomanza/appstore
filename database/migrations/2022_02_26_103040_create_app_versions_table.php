<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_versions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('app_id');
            $table->integer('version_code');
            $table->string('version_name');
            $table->integer('min_sdk_level')
                ->default(19);
            $table->integer('target_sdk_level')
                ->default(31);
            $table->string('apk_file_url', 120);
            $table->string('apk_file_size', 60);
            $table->string('icon_url', 120);
            $table->longText('description');
            $table->integer('downloads')
                ->default(0);
            $table->integer('installs')
                ->default(0);
            $table->timestamps();

            $table->unique(['version_code', 'app_id']);
            $table->unique(['version_name', 'app_id']);
            $table->foreign('app_id')
                ->references('id')
                ->on('apps')
                ->onDelete('cascade');
            $table->index('version_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_versions');
    }
}
