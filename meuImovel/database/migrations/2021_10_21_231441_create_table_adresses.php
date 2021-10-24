<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAdresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('state_id');//verbose mode
            $table->foreignId('city_id')->constrained('cities');//new mode

            $table->string('adress');
            $table->string('number');
            $table->string('neighborhood');
            $table->string('complement')->nullable();
            $table->integer('zip_code');

            $table->timestamps();

            $table->foreign('state_id')->references('id')->on('states');//verbose mode
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adresses');
    }
}
