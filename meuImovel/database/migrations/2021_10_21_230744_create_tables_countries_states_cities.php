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
        Schema::create('tables_countries_states_cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('initials');
            $table->timestamps();
        });

        Scheme::create('states',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('country_id');
            $table->string('name');
            $table->string('slug');
            $table->string('initials');
            $table->timestamps();
            $table->foreign('country_id')->refernces('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tables_countries_states_cities');
    }
}
