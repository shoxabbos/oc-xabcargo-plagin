<?php namespace Svetlana\DriverApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSvetlanaDriverappOrder2 extends Migration
{
    public function up()
    {
        Schema::table('svetlana_driverapp_order', function($table)
        {
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('svetlana_driverapp_order', function($table)
        {
            $table->dropColumn('deleted_at');
        });
    }
}
