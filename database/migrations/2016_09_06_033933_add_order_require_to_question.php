<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderRequireToQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_question', function ($table) {                    
            $table->integer('order')->nullable();        
            $table->boolean('require')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_question', function ($table) {                    
            $table->dropColumn('order');            
            $table->dropColumn('require');
        });
    }
}
