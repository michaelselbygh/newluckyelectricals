<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockKeepingUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_keeping_units', function (Blueprint $table) {
            $table->string('id', '10')->primary();
            $table->string('product_id', '30');
            $table->string('description');
            $table->double('price')->nullable();
            $table->double('discount')->nullable();
            $table->integer('stock_left');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_keeping_units');
    }
}
