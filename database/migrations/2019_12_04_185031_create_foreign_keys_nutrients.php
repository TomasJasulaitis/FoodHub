<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeignKeysNutrients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nutrients', function (Blueprint $table) {
            $table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nutrients', function (Blueprint $table) {
            $table->dropForeign('recipe_id');
        });
    }
}
