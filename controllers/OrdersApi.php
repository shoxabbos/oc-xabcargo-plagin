<?php namespace Svetlana\DriverApp\Controllers;

/**
 * 
 */
use Input;
use JWTAuth;
use Validator;
use Backend\Classes\Controller;
use Svetlana\DriverApp\Models\Order;
use Svetlana\DriverApp\Models\Status;
use Svetlana\DriverApp\Models\CarType;
use RainLab\User\Models\User as UserModel;
use RainLab\User\Models\Settings as UserSettings;

class OrdersApi extends Controller
{
	private function auth() 
    {
        return JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        $statuses = Status::where('is_active', true)->get();
        $car_types = CarType::with('icon')->get();

        return response()->json(compact('statuses', 'car_types'));
    }

    public function createOrder()
    {
        $user = $this->auth();

        $data = Input::only('weight', 'from', 'to', 'cost', 'comment', 'delivered_at', 'car_type_id', 'images');

        $rules = [
            'weight' => 'required|numeric',
            'from' => 'required|array',
            'to' => 'required|array',
            'cost' => 'required|integer',
            'comment' => 'min:5',
            'delivered_at' => 'required|date',
            'car_type_id' => 'required|integer',
            'images[]' => 'image',
        ];

        $validation = Validator::make($data, $rules);

        if ($validation->fails()){
            return response()->json(['errors' => $validation->errors()]);
        }

        $data['status_id'] = Status::where('code', 'active')->first();

        $user->orders()->create($data);
        $user->update();

        $orders = $user->orders()->with('user', 'driver', 'images', 'car_type', 'status')->get();
        
        $status = 'ok';

        return response()->json(compact('status', 'orders'));
    }

    public function getMyOrders()
    {
        $user = $this->auth();

        $orders = $user->orders()->with('images', 'driver', 'car_type', 'user', 'status')->get();

        return response()->json(compact('orders'));
    }
    
    public function getOrder($id = null)
    {
        $user = $this->auth();

        if (!$id){
            return response()->json(['errors' => 
                ['id' => 'id was not passed']
            ], 500);
        }

        $order = Order::where('id', $id)->where('user_id', $user->id)
                ->with('status', 'user', 'driver', 'images', 'car_type')->first();

        if (!$order){
            return response()->json(['order' => 'order not found'], 404);
        }

        return response()->json(compact('order'));
        
    }

}