<?php namespace Svetlana\DriverApp\Controllers;

/**
 * 
 */

use Auth;
use Lang;
use Mail;
use Input;
use JWTAuth;
use Validator;
use Backend\Classes\Controller;
use RainLab\User\Models\Settings as UserSettings;
use RainLab\User\Models\User as UserModel;
use RainLab\User\Models\UserGroup as UserGroupModel;

class UserApi extends Controller
{
	private function auth()
    {
        return JWTAuth::parseToken()->authenticate();
    }


	public function register()
	{
		$data = Input::only('name', 'surname', 'email', 'phone', 'password', 'password_confirmation');

		$rules = [
			'name'     => 'required|min:3',
			'surname'  => 'required|min:4',
			'email'    => 'required|email|unique:users',
			'phone'    => 'required|unique:users,username',
			'password' => 'required:create|between:' . UserSettings::MIN_PASSWORD_LENGTH_DEFAULT . ',255|confirmed',
        	'password_confirmation' => 'required_with:password|between:' . UserSettings::MIN_PASSWORD_LENGTH_DEFAULT . ',255',

		];


		$validation = Validator::make($data, $rules);

		if ($validation->fails()){
			return response()->json(['errors' => $validation->errors()]);
		}

		$data['username'] = $data['phone'];

        try {
            $userModel = UserModel::create($data);

            if ($userModel->methodExists('getAuthApiSignupAttributes')) {
                $user = $userModel->getAuthApiSignupAttributes();
            } else {
                $user = [
                    'id' => $userModel->id,
                    'name' => $userModel->name,
                    'surname' => $userModel->surname,
                    'username' => $userModel->username,
                    'email' => $userModel->email,
                    'is_activated' => $userModel->is_activated,
                ];
            }
        } catch (Exception $e) {
            return Response::json(['error' => $e->getMessage()], 401);
        }

        $token = JWTAuth::fromUser($userModel);

        if ($group = UserGroupModel::where('code', 'clients')->first()){
            $userModel->groups = $group;
            $userModel->update();
        }


        return response()->json(compact('token', 'user'));
	}

	public function login()
	{
		$data = Input::only('login', 'password');

		$rules = [
			'login' => 'required|exists:users,username',
			'password' => 'required',
		];

		$validation = Validator::make($data, $rules);

		if ($validation->fails()) {
			return response()->json(['errors' => $validator->errors()]);
		}
		$data['username'] = $data['login'];
		unset($data['login']);
		try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($data)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $userModel = JWTAuth::authenticate($token);

        if ($userModel->methodExists('getAuthApiSigninAttributes')) {
            $user = $userModel->getAuthApiSigninAttributes();
        } else {
            $user = [
                'id' => $userModel->id,
                'name' => $userModel->name,
                'surname' => $userModel->surname,
                'username' => $userModel->username,
                'email' => $userModel->email,
                'is_activated' => $userModel->is_activated,
            ];
        }
        // if no errors are encountered we can return a JWT
        return response()->json(compact('token', 'user'));
	}

	public function getUser()
	{
		$user = $this->auth();
		$user = UserModel::with('groups', 'orders', 'tasks', 'car_type')->find($user->id);
		return response()->json(compact('user'));
	}

	public function updateUser()
	{
		$user = $this->auth();

		$user = UserModel::with('groups', 'orders', 'tasks', 'car_type')->find($user->id);
		$data = Input::only('name', 'surname', 'email', 'phone', 'password', 'password_confirmation');

		$rules = [
			'name'     => 'required|min:3',
			'surname'  => 'required|min:4',
			'email'    => 'required|email|unique:users',
			'phone'    => 'required|unique:users,username',
			'password' => 'required:create|between:' . UserSettings::MIN_PASSWORD_LENGTH_DEFAULT . ',255|confirmed',
        	'password_confirmation' => 'required_with:password|between:' . UserSettings::MIN_PASSWORD_LENGTH_DEFAULT . ',255',

		];

        if ($data['phone']) {
            $data['username'] = $data['phone'];
            unset($data['phone']);
        }

        if (Input::hasFile('avatar')) {
            $user->avatar = Input::file('avatar');
        }

        $user->fill($data);
        $user->save();
        /*
         * Password has changed, reauthenticate the user
         */
        

        return response()->json(compact('user'));
	}

    public function restorePassword()
    {
        $rules = [
            'email' => 'required|email|between:6,255'
        ];

        $validation = Validator::make(post(), $rules);
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()]);
        }

        $user = UserModel::findByEmail(post('email'));
        if (!$user || $user->is_guest) {
            throw new ApplicationException(Lang::get(/*A user was not found with the given credentials.*/'rainlab.user::lang.account.invalid_user'));
        }

        $code = implode('!', [$user->id, $user->getResetPasswordCode()]);

        $data = [
            'name' => $user->name,
            'code' => $code
        ];

        Mail::send('rainlab.user::mail.restore', $data, function($message) use ($user) {
            $message->to($user->email, $user->full_name);
        });

        return response()->json(['message' => 'code for password reset was sent to the specified email']);
    }

    public function resetPassword()
    {
        $rules = [
            'code'     => 'required',
            'password' => 'required|between:' . UserModel::getMinPasswordLength() . ',255'
        ];

        $validation = Validator::make(post(), $rules);
        if ($validation->fails()) {
            return response()->json([ 'errors' => $validation->errors() ], 500);
        }

        $errorFields = ['code' => Lang::get(/*Invalid activation code supplied.*/'rainlab.user::lang.account.invalid_activation_code')];

        /*
         * Break up the code parts
         */
        $parts = explode('!', post('code'));
        if (count($parts) != 2) {
            return response()->json([ 'errors' => ($errorFields) ], 500);
        }

        list($userId, $code) = $parts;

        if (!strlen(trim($userId)) || !strlen(trim($code)) || !$code) {
            return response()->json([ 'errors' => ($errorFields) ], 500);
        }

        if (!$user = Auth::findUserById($userId)) {
            return response()->json([ 'errors' => ($errorFields) ], 500);
        }

        if (!$user->attemptResetPassword($code, post('password'))) {
            return response()->json([ 'errors' => ($errorFields) ], 500);
        }

        return response()->json(['message' => 'password was successfully restored']);

    }

}