<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateTableReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('report_id');
            $table->bigInteger('app_id')->unsigned();
            $table->string('app_version_code');
            $table->string('app_version_name');
            $table->string('package_name');
            $table->string('file_path');
            $table->string('phone_model');
            $table->string('brand');
            $table->string('product');
            $table->string('android_version');
            $table->longText('build'); // json
            $table->string('total_mem_size');
            $table->string('available_mem_size');
            $table->string('build_config'); // json
            $table->string('custom_data')->nullable(); // json
            $table->string('is_silent')->nullable();
            $table->longText('stack_trace');
            $table->string('exception');
            $table->longText('initial_configuration'); // json
            $table->longText('crash_configuration'); // json
            $table->longText('display'); // json
            $table->string('user_comment')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_app_start_date');
            $table->string('user_crash_date');
            $table->string('dumpsys_meminfo')->nullable();
            $table->longText('logcat');
            $table->string('installation_id');
            $table->longText('device_features'); // json
            $table->longText('environment'); // json
            $table->longText('shared_preferences'); // json
            $table->timestamps();

            $table->foreign('app_id')
            ->references('id')
                ->on('apps');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
