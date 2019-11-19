<?php namespace Svetlana\DriverApp\Models;

use Model;
use Rainlab\User\Models\User as UserModel;
/**
 * Model
 */
class Order extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'svetlana_driverapp_order';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $jsonable = [
        'from', 'to'
    ];

    public $belongsTo = [
        'user' => UserModel::class,
        'driver' => UserModel::class,
        'car_type' => CarType::class,
        'status' => Status::class,
    ];

    public $guarded = [
        'id'
    ];

    public $attachMany = [
        'images' => \System\Models\File::class
    ];


    public function getCarTypeOptions()
    {
        return CarType::lists('name', 'id');
    }

    public function getStatusOptions()
    {
        return Status::where('is_active', true)->orderBy('sort_order', 'asc')->lists('name', 'id');
    }

    
}
