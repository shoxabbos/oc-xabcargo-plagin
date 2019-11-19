<?php namespace Svetlana\DriverApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSvetlanaDriverappOrder extends Migration
{
    public function up()
    {
        Schema::table('svetlana_driverapp_order', function($table)
        {
            $table->dateTime('delivered_at')->nullable();
            $table->integer('car_type_id')->nullable();
            $table->integer('weight')->nullable(false)->unsigned(false)->default(null)->change();
            $table->text('from')->nullable()->unsigned(false)->default(null)->change();
            $table->text('to')->nullable()->unsigned(false)->default(null)->change();
            $table->integer('cost')->nullable()->unsigned(false)->default(null)->change();
            $table->text('comment')->nullable()->unsigned(false)->default(null)->change();
            $table->integer('user_id')->unsigned()->change();
            $table->dropColumn('deliver_at');
            $table->dropColumn('car_type');
        });
    }
    
    public function down()
    {
        Schema::table('svetlana_driverapp_order', function($table)
        {
            $table->dropColumn('delivered_at');
            $table->dropColumn('car_type_id');
            $table->string('weight', 255)->nullable()->unsigned(false)->default(null)->change();
            $table->string('from', 255)->nullable()->unsigned(false)->default(null)->change();
            $table->string('to', 255)->nullable()->unsigned(false)->default(null)->change();
            $table->string('cost', 10)->nullable()->unsigned(false)->default(null)->change();
            $table->string('comment', 255)->nullable()->unsigned(false)->default(null)->change();
            $table->integer('user_id')->unsigned(false)->change();
            $table->string('deliver_at', 255)->nullable();
            $table->string('car_type', 255)->nullable();
        });
    }
}
