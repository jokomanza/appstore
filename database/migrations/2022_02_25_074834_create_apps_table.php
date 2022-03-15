<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 30)
                ->unique();
            $table->string('package_name', 30)
                ->unique();
            $table->string('description', 255);
            $table->string('type', 30);
            $table->string('icon_url', 120);
            $table->string('repository_url', 120)
            ->nullable();
            $table->string('user_documentation_url', 120)
            ->nullable()
            ->default(null);
            $table->string('developer_documentation_url', 120)
            ->nullable()
            ->default(null);
            $table->string('api_token');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apps');
    }
}
