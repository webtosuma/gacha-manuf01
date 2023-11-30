<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use App\Http\Requests\UserRegisterRequest;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| 会員登録 Controller
|--------------------------------------------------------------------------
*/
class RegisterController extends Controller
{

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;



    /**
     * ユーザー登録 API
     *
     * @param \Illuminate\Http\UserRegisterRequest $request
     * @return JSON
    */
    public function register(UserRegisterRequest $request)
    {
        // return response()->json([ 'message' => 'register ok!', ]);


        # 求職者情報の保存
        $user = new \App\Models\User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make( $request->password ),
        ]);
        $user->save();


        # 求職者・管理者へメール送信
        // SendMailController::WorkerAuthRegister02( $worker );


        # ログイン
        Auth::attempt( $request->only('email','password'), true );
        $request->session()->regenerate();


        # 成功レスポンス
        return response()->json([
            'message' => 'register ok!',
            'user'    => $user,
            'Auth check'=> Auth::check()
        ]);
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
