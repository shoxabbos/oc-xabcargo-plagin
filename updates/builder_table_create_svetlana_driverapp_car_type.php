<?php namespace Svetlana\DriverApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSvetlanaDriverappCarType extends Migration
{
    public function up()
    {
        Schema::create('svetlana_driverapp_car_type', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 255)->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('svetlana_driverapp_car_type');
    }
}
