<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->char( 'id', 8) ;
            $table->primary( 'id' ) ;

            $table->unsignedBigInteger('sequence')->default( 0 );
            $table->string( 'source' );

            $table->timestamps( );
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
