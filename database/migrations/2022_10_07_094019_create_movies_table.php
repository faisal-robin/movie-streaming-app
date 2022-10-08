<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('imdb_id')->unique();
            $table->string('title')->index();
            $table->string('poster')->nullable();
            $table->string('release_year');
            $table->string('tag')->nullable()->index();
            $table->text('directors');
            $table->text('casts');
            $table->string('plan_type')->nullable();
            $table->dateTime('rent_period_from')->nullable();
            $table->dateTime('rent_period_to')->nullable();
            $table->double('rent_price',10,2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
    }
}
