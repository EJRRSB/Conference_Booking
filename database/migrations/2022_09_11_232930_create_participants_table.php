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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('booking_id')->nullable();
            $table->integer('guest',11)->default('0')->comment('1 - guest - 0 member');
            $table->string('guest_email',100);
            $table->timestamps();
            $table->rememberToken();
            $table->softDeletes(); 

            $table->foreign("user_id")->references("id")->on("users")->cascadeOnDelete();
            $table->foreign("booking_id")->references("id")->on("bookings")->cascadeOnDelete(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participants');
    }
};
