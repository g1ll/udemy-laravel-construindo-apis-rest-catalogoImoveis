<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablesCountriesStatesCities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('initials');
            $table->timestamps();
        });

        Schema::create('states',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('countries_id');
            $table->string('name');
            $table->string('slug');
            $table->string('initials');
            $table->timestamps();
            $table->foreign('countries_id')->refernces('id')->on('countries');
        });

        Schema::create('cities',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('states_id');
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
            $table->foreign('states_id')->refernces('id')->on('states');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::dropIfExists('tables_countries_states_cities');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('states');
        Schema::dropIfExists('countries');
    }
}
