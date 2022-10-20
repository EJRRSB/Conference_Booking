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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name',100)->nullable();
            $table->string('description',255)->nullable();
            $table->string('room_color',50)->nullable();
            $table->integer('max_seats',11)->nullable();
            $table->integer('added_by')->nullable();
            $table->integer('modified_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
            $table->rememberToken();
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
        Schema::dropIfExists('rooms');
    }
};
