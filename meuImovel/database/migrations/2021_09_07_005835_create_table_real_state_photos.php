<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableRealStatePhotos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('real_state_photos', function (Blueprint $table) {
            $table->id();
            $table->string('photo');
            $table->boolean('is_thumb');
            $table->timestamps();
            $table->foreignId('real_state_id')->constrained('real_state');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('real_state_photos');
    }
}
