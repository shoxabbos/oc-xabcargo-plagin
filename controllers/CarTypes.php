<?php namespace Svetlana\DriverApp\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class CarTypes extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'manage_car_types' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Svetlana.DriverApp', 'driverapp', 'car-types');
    }
}
