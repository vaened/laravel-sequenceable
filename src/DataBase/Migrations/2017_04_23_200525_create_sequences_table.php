<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSequencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sequences', function (Blueprint $table) {
            $table->char('id', 8) ;
            $table->primary('id') ;

            $table->unsignedBigInteger('sequence')->default(0);

            $table->string('source', 35)->index();
            $table->string('column_key', 60);
            $table->string('description', 100)->nullable();

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
        Schema::dropIfExists('sequences');
    }
}
