<?php namespace Svetlana\DriverApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSvetlanaDriverappOrder4 extends Migration
{
    public function up()
    {
        Schema::table('svetlana_driverapp_order', function($table)
        {
            $table->integer('driver_id')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('svetlana_driverapp_order', function($table)
        {
            $table->integer('driver_id')->nullable(false)->change();
        });
    }
}
