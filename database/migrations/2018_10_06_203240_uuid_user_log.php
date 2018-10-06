<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UuidUserLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        \Schema::create('uuid_user_logs', function (Blueprint $table) {
            $table->uuid('uuid')->index();
            $table->uuid('user_uuid');
            $table->string('title')->index();
            $table->timestamps();

        });

        \Schema::table('uuid_user_logs', function (Blueprint $table) {
            $table->foreign('user_uuid')->references('uuid')->on('uuid_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::dropIfExists('uuid_user_logs');
    }
}
