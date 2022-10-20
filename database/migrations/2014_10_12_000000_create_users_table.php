<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name',100)->nullable();
            $table->string('middle_name',100)->nullable();
            $table->string('last_name',100)->nullable();
            $table->string('role',20)->comment('1 - Admin - 2 User 3 - System');
            $table->integer('status',1)->default('1')->comment('1 - Approved - 2 Pending 3 - Declined');
            $table->integer('is_IT',1)->default('1')->comment('1 - No - 2 Yes');
            $table->string('phone_number',20)->nullable();
            $table->string('email',100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('approved_by',11)->nullable();
            $table->rememberToken();
            $table->timestamps(); 
            $table->softDeletes(); 
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
};
