<?php namespace Svetlana\DriverApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSvetlanaDriverappStatuses extends Migration
{
    public function up()
    {
        Schema::create('svetlana_driverapp_statuses', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('code');
            $table->boolean('is_active');
            $table->integer('sort_order')->nullable(true);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('svetlana_driverapp_statuses');
    }
}