<?php namespace Svetlana\DriverApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSvetlanaDriverappOrder3 extends Migration
{
    public function up()
    {
        Schema::table('svetlana_driverapp_order', function($table)
        {
            $table->decimal('weight', 10, 3)->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('svetlana_driverapp_order', function($table)
        {
            $table->integer('weight')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
}
