<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('full_name')->nullable();
            $table->string('contact_no')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('role')->default(1);
            $table->string('address')->nullable();
            $table->string('impressions')->nullable();
            $table->string('device_token')->nullable();
            $table->string('password');
            $table->integer('is_submit')->default(0);
            $table->rememberToken();
			$table->timestamps();
			$table->softDeletes()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
