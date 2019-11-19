<?php namespace Svetlana\DriverApp\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Orders extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend.Behaviors.RelationController',
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';

    public $requiredPermissions = [
        'manage_orders' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Svetlana.DriverApp', 'driverapp', 'orders');
    }
}
