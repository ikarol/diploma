<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Models\Professor;
use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|string|max:255|unique:users',
            'role' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        switch ($data['role']) {
            case 'student' :
                Student::create([
                    'user_id' => $user['id'],
                    'group_id' => $data['group_id'],
                    'name' => $data['name'],
                    'surname' => $data['surname'],
                    'middlename' => $data['middlename'],
                ]);
                break;
            case 'professor' :
                Professor::create([
                    'user_id' => $user['id'],
                    'name' => $data['name'],
                    'surname' => $data['surname'],
                    'middlename' => $data['middlename'],
                    'occupation' => $data['occupation'],
                    'degree' => $data['degree'],
                ]);
                break;
        }
        return $user;
    }
}
