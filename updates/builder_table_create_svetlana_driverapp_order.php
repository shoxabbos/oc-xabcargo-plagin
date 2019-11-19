<?php namespace Svetlana\DriverApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSvetlanaDriverappOrder extends Migration
{
    public function up()
    {
        Schema::create('svetlana_driverapp_order', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('weight', 255)->nullable();
            $table->string('from', 255)->nullable();
            $table->string('to', 255)->nullable();
            $table->string('deliver_at', 255)->nullable();
            $table->string('cost', 10)->nullable();
            $table->string('car_type', 255)->nullable();
            $table->string('comment', 255)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->integer('user_id');
            $table->integer('driver_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('svetlana_driverapp_order');
    }
}
