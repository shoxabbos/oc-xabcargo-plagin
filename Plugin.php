<?php namespace Svetlana\DriverApp;

use Yaml;
use System\Classes\PluginBase;
use Rainlab\User\Models\User as UserModel;
use RainLab\User\Models\Settings as UserSettings;
use RainLab\User\Controllers\Users as UsersController;
use Svetlana\DriverApp\Models\Order as OrderModel;
use Svetlana\DriverApp\Controllers\Orders as OrdersController;
use Svetlana\DriverApp\Models\CarType as CarTypeModel;
use Svetlana\DriverApp\Controllers\CarTypes as CarTypesController;


class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
    	$this->extendingModels();
        $this->extendingControllers();
    }


    private function extendingModels()
    {
    	UserModel::extend(function ($model){
            $model->rules = [
                'email'    => 'between:6,255|email|unique:users',
                'avatar'   => 'nullable|image|max:4000',
                'username' => 'required|between:2,255|unique:users',
                'password' => 'required:create|between:' . UserSettings::MIN_PASSWORD_LENGTH_DEFAULT . ',255|confirmed',
                'password_confirmation' => 'required_with:password|between:' . UserSettings::MIN_PASSWORD_LENGTH_DEFAULT . ',255',
            ];

            $model->hasMany['orders'] = [ 'Svetlana\DriverApp\Models\Order', 'key' => 'user_id' ];
            $model->hasMany['tasks'] = [ 'Svetlana\DriverApp\Models\Order', 'key' => 'driver_id' ];
            $model->belongsTo['car_type'] = [ 'Svetlana\DriverApp\Models\CarType'];

            $model->addDynamicMethod('getCarTypeOptions', function() {
                return CarTypeModel::lists('name', 'id');
            });

    	});

    }

    public function extendingControllers()
    {
        UsersController::extend(function ($controller) {
            $controller->implement[] = 'Backend\Behaviors\RelationController';
            $controller->relationConfig = '$/svetlana/driverapp/controllers/orders/config_relation.yaml';
        });

        UsersController::extendFormFields(function($form, $model, $context) {
            if (!$model instanceof UserModel) {
                return;
            }
            
            $fields = [
                'username' => [
                    'label' => 'phone',
                    'type'  => 'text',
                    'tab' => 'rainlab.user::lang.user.account'
                ],
                'orders' => [
                    'type' => 'partial',
                    'span' => 'full',
                    'path' => '$/svetlana/driverapp/controllers/orders/_field_orders.htm',
                    'tab' => 'svetlana.driverapp::lang.order.orders'
                ],
            ];

            if ($model->groups()->where('code', 'drivers')->first()){
                $fields['tasks'] = [
                    'type' => 'partial',
                    'span' => 'full',
                    'path' => '$/svetlana/driverapp/controllers/orders/_field_tasks.htm',
                    'tab' => 'svetlana.driverapp::lang.order.tasks'
                ];

                $fields['car_type'] = [
                    'type' => 'dropdown',
                    'tab' => 'rainlab.user::lang.user.account',
                    'span' => 'full'
                ];
            }
            $form->addTabFields($fields);
        });

        OrdersController::extendFormFields(function($form, $model, $context) {
            if (!$model instanceof OrderModel) {
                return;
            }
            
            if ($form->isNested) {
                return false;
            }

            if (!$model->user) {
                $fields = Yaml::parseFile('./plugins/svetlana/driverapp/models/order/user_not_fields.yaml');
                $form->addTabFields($fields);
            } else {
                $fields = Yaml::parseFile('./plugins/svetlana/driverapp/models/order/user_fields.yaml');
                $form->addTabFields($fields);
            }

            if (!$model->driver) {
                $fields = Yaml::parseFile('./plugins/svetlana/driverapp/models/order/driver_not_fields.yaml');
                $form->addTabFields($fields);
            } else {
                $fields = Yaml::parseFile('./plugins/svetlana/driverapp/models/order/driver_fields.yaml');
                $form->addTabFields($fields);
            }

        });
    }
}
