<?php namespace Svetlana\DriverApp\Models;

use Model;

/**
 * Model
 */
class Status extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    

    const SORT_ORDER = 'sort_order';
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    public $translatable = ['name'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'svetlana_driverapp_statuses';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];


    public $belongsToMany = [
        'orders' => [ Order::class, 'table' => 'svetlana_driverapp_order_status', 'count' => true],
    ];

}
