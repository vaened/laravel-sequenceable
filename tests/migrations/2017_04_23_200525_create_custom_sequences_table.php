<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomSequencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_sequences', function (Blueprint $table) {
            $table->increments('id');

            $table->char('key', 3)->index();

            $table->unsignedBigInteger('sequence')->default(0);

            $table->string('source', 50);
            $table->string('column_id', 60);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_sequences');
    }
}
