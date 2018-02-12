<?php

namespace App\Http\Controllers\Auth;


use App\User;
use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Session;
use Mail;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
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
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users|regex:".guc.edu.eg$"',
            'password' => 'required|confirmed|min:6',
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

        $confirmation_code = str_random(32);
        Mail::send('auth.emails.verify', ['confirmation_code' => $confirmation_code, 'name' => $data['first_name']], function($message) use ($data) {
            $message->to($data['email'], $data['first_name'])
                ->subject('Verify your email address');
        });

        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'confirmation_code' => $confirmation_code,
            'confirmed' => false
        ]);
    }

    protected function getCredentials(Request $request)
    {
        return [
            'email'    => $request->input('email'),
            'password' => $request->input('password'),
            'confirmed' => true
        ];
    }


    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
     
        $this->create($request->all());
        Session::flash('register','Thank you for registering. Kindly go to your email and follow the link to verify your account.');
        return redirect($this->redirectPath());
    }


    public function verify($token)
    {
        $user = User::where('confirmation_code','=',$token)->first();
        $user->confirmed = true;
        $user->save();
        Session::flash('register','Your account has been verified!');
        Auth::guard($this->getGuard())->login($user);
        return redirect('/home');

    }

}
