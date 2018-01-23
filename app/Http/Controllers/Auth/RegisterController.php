<?php

namespace App\Http\Controllers\Auth;

use App\Country;
use App\Mail\EmailVerification;
use App\Mail\Welcome;
use App\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Overrid method to show registration form
     */
    public function showRegistrationForm() {
        try {
            //Get all country
            $data['countries'] = Country::getAllCountry();
            $data['states'] = ['' => 'Select state'];

            //Get user validation messages
            $data['validationMessages'] = User::$validationMessages;
            return view('auth.register', $data);
        } catch (\Exception $ex) {
            return view('errors.500');
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, User::validationRulesForAddUser(), User::$validationMessages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        //Keep user's password into session to use to send in email
        \Session::put('user_password', $data['password']);

        return User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'password'          => bcrypt($data['password']),
            'country_id'        => $data['country_id'],
            'state_id'          => $data['state_id'],
            'verify_token'      => str_random(60),
            'verify_send_on'    => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        return $this->registered($request, $user)
            ?: redirect('login')->with('success', 'Your registration has been successful, a verification link has been send to your registered email.');
    }

    /**
     * Assign role to user after successful registration
     * @param Request $request
     * @param $user
     */
    public function registered(Request $request, $user)
    {
        //Assign user role to registered user
        $user->attachRole(config('app.roles.user.id'));

        //Send verification email to user
        Mail::to($user)->send(new EmailVerification($user));
    }

    /**
     * Verify registered user's email
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyEmail(Request $request)
    {
        try {
            //Get user details by verification token
            $user = User::where('verify_token', $request->token)->firstOrFail();

            $verificationSendOn = Carbon::parse($user->verify_send_on);
            $now = Carbon::now();
            $dayDiff = $verificationSendOn->diffInDays($now);

            //Check if 1 day has been passed after sending the verification link
            if ($dayDiff > 0) {
                return redirect('login')->with('failure', 'The verification link has been expired.');
            }

            $user->verify_token = null;
            $user->is_verify = 1;

            if ($user->save()) {
                //Send welcome email to user
                Mail::to($user)->send(new Welcome($user));

                //Unset user_password session
                \Session::forget('user_password');
                return redirect('login')->with('success', 'Your verification has been successful, you may login with your email and password.');
            } else {
                return redirect('login')->with('failure', config('message.default_error'));
            }

        } catch(ModelNotFoundException $ex) {
            return redirect('login')->with('failure', 'Invalid verification code.');
        } catch(\Exception $ex) {
            return redirect('login')->with('failure', config('message.default_error'));
        }
    }
}
