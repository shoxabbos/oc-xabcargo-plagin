<?php namespace Svetlana\DriverApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSvetlanaDriverappOrder5 extends Migration
{
    public function up()
    {
        Schema::table('svetlana_driverapp_order', function($table)
        {
            $table->integer('status_id')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::table('svetlana_driverapp_order', function($table)
        {
            $table->dropColumn('status_id');
        });
    }
}