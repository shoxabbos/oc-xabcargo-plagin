<?php namespace Svetlana\DriverApp\Models;

use Model;

/**
 * Model
 */
class CarType extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
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
    public $table = 'svetlana_driverapp_car_type';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $attachOne = [
        'icon' => \System\Models\File::class,
    ];
}
