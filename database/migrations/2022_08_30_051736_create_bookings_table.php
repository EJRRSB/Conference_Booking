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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('room_id')->nullable();
            $table->string('booking_number',50);
            $table->string('purpose',255)->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->integer('status',1)->nullable()->comment('1 - Approved - 2 Pending 3 - Declined 4 - Canceled');
            $table->integer('is_IT',1)->nullable()->comment('1 - No - 2 Yes');
            $table->string('mode',50);
            $table->string('type',50);
            $table->string('internal_option',100);
            $table->string('internal_option_others',150);
            $table->string('client_name',150);
            $table->string('client_type',150);
            $table->string('client_type_others',150);
            $table->string('client_number',100);
            $table->string('agenda',255);
            $table->string('it_requirements',150);
            $table->string('it_requirements_others',150);

            $table->integer('modified_by',11)->nullable();
            $table->integer('deleted_by',11)->nullable();
            $table->integer('declined_by',11)->nullable();
            $table->integer('canceled_by',11)->nullable();
            $table->integer('approved_by',11)->nullable();
            $table->timestamps();
            $table->rememberToken();
            $table->softDeletes(); 
            
            $table->foreign("user_id")->references("id")->on("users")->cascadeOnDelete();
            $table->foreign("room_id")->references("id")->on("rooms")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
