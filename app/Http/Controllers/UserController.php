<?php

namespace App\Http\Controllers;

use App\Country;
use App\Mail\Welcome;
use App\State;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use League\Flysystem\Exception;
use Yajra\Datatables\Datatables;

class UserController extends Controller
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        //Auth middleware
        $this->middleware('auth')->except(['redirectToProvider', 'handleProviderCallback']);

        //Apply input cleaning middleware before processing any request
        $this->middleware('clean-input');

        //Permission filters
        $this->middleware('permission:user_list|user_view|user_edit|user_add', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update']]);
        $this->middleware('permission:user_delete', ['only' => ['destroy']]);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if(\Entrust::hasRole(config('app.roles.user.name'))) {
                return view('errors.403');
            }
            $data['title'] = 'Users list';
            if($request->ajax()){
                $columns = [
                    'users.id',
                    'name',
                    'email',
                    'is_verify',
                    'users.created_at as created_date'
                ];

                //Get all users
                $users = User::select($columns)->leftJoin('countries', 'countries.id', '=', 'users.country_id')
                    ->leftJoin('states', 'states.id', '=', 'users.state_id')
                    ->orderBy('created_date', 'DESC');

                return Datatables::of($users)->addColumn('action', function ($user) {
                    $data['user'] = $user;
                    $return = view('user.partials.actions',$data)->render();
                    return $return;
                })->editColumn('is_verify', function ($user) {
                    if ($user->is_verify == 1) {
                        return 'Yes';
                    }
                    return 'No';
                })->editColumn('created_date', function ($user) {
                    return \Helpers::convertDate($user->created_date);
                })->make(true);
            }

            //Render the view to show user listing
            return view('user.index', $data);
        } catch (\Exception $ex) {
            return redirect()->route('user.index')->with('failure', $ex->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $data['user'] = [];

            // Get validation messages
            $data['validationMessages'] = User::$validationMessages;

            //Get all country
            $data['countries'] = Country::getAllCountry();

            $data['states'] = ['' => 'Select state'];

            //Render the user add form
            return view('user.add', $data);

        } catch (\Exception $ex) {
            return redirect()->route('user.index')->with('failure', $ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();

            //Server side validations
            $validator = \Validator::make($data, User::validationRulesForAddUser(), User::$validationMessages);

            if ($validator->fails()) {
                //Redirect user back with input if server side validation fails
                return redirect()->route('user.create')->withErrors($validator)->withInput();
            }

            $user = new User();
            $user->name         = $request->input('name');
            $user->email        = $request->input('email');
            $user->password     = bcrypt($request->input('password'));
            $user->country_id   = $request->input('country_id');
            $user->state_id     = $request->input('state_id');
            $user->is_verify    = 1;

            //Save user details
            if ($user->save()) {
                //Assign role to registered user
                $user->attachRole(config('app.roles.user.id'));

                //Set user password in session
                \Session::put('user_password', $request->input('password'));

                //Send welcome email to user
                Mail::to($user)->send(new Welcome($user));

                //Unset user_password session
                \Session::forget('user_password');

                return redirect()->route('user.index')->with('success', 'User created successfully.');
            } else {
                return redirect()->route('user.index')->with('failure', config('app.messages.default_error'));
            }
        } catch (\Exception $ex) {
            return redirect()->route('user.create')->with('failure', $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            //Get user details from user and other related table.
            $data['user'] = User::leftJoin('countries', 'countries.id', '=', 'users.country_id')
                                    ->leftJoin('states', 'states.id', '=', 'users.state_id')
                                    ->findOrFail($id);

            return view('user.show', $data);
        } catch(ModelNotFoundException $ex) {
            return redirect()->route('user.index')->with('failure', 'This user is not available.');
        } catch (\Exception $ex) {
            return redirect()->route('user.index')->with('failure', $ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            //Find details by id
            $user = User::findOrFail($id);
            $data['user'] = $user;

            if(\Auth::user()->hasRole(config('app.roles.admin.name')) && ($user->hasRole(config('app.roles.super_admin.name')) || ($user->hasRole(config('app.roles.admin.name')) && \Auth::user()->id != $user->id))) {
                return view('errors.403');
            } elseif (\Auth::user()->hasRole(config('app.roles.user.name')) && $user->id != \Auth::user()->id) {
                return view('errors.403');
            } else {
                // Get validation messages
                $data['validationMessages'] = User::$validationMessages;

                //Get all country
                $data['countries'] = Country::getAllCountry();

                $data['states'] = State::getSateByCountry($data['user']->country_id);
            }
            //Render the user edit form
            return view('user.add', $data);
        } catch(ModelNotFoundException $ex) {
            return redirect()->route('user.index')->with('failure', 'This user is not available.');
        } catch (\Exception $ex) {
            return redirect()->route('user.index')->with('failure',  $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();

            $validator = \Validator::make($data, User::validationRulesForUpdate($id), User::$validationMessages);

            if ($validator->fails()) {
                //Show error message
                return redirect()->route('user.edit', $id)->withErrors($validator)->withInput();
            }

            $user = User::findOrFail($id);
            $user->name         = $request->input('name');
            $user->country_id   = $request->input('country_id');
            $user->state_id     = $request->input('state_id');

            //Change user password if requested from view
            if($request->password != '') {
                $user->password = bcrypt($request->password);
            }

            //Save user details
            if ($user->save()) {
                if(\Auth::user()->hasRole(config('app.roles.user.name'))) {
                    return redirect()->route('user.edit', $id)->with('success', 'Profile updated successfully.');
                }
                return redirect()->route('user.index')->with('success', 'Profile updated successfully.');
            } else {
                if(\Auth::user()->hasRole(config('app.roles.user.name'))) {
                    return redirect()->route('user.edit', $id)->with('failure', config('app.messages.default_error'));
                }
                return redirect()->route('user.index')->with('failure', config('app.messages.default_error'));
            }
        } catch(ModelNotFoundException $ex) {
            if(\Auth::user()->hasRole(config('app.roles.user.name'))) {
                return redirect()->route('user.edit', $id)->with('failure', 'This user is not available.');
            }
            return redirect()->route('user.index')->with('failure', 'This user is not available.');
        } catch (\Exception $ex) {
            return redirect()->route('user.edit', $id)->with('failure', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            //Find details by id
            $user = User::findOrFail($id);
            if($user->delete()) {
                return redirect()->route('user.index')->with('success', 'User deleted successfully.');
            } else {
                return redirect()->route('user.index')->with('failure', config('app.messages.default_error'));
            }
        } catch (ModelNotFoundException $ex) {
            return redirect()->route('user.index')->with('failure', 'User not available.');
        } catch (\Exception $ex) {
            return redirect()->route('user.index')->with('failure', $ex->getMessage());
        }
    }

    /**
     * Redirect to facebook or twitter or linkedin page based on provider value
     * @param $provider
     * @return mixed
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * User authentication based on facebook, twitter and linkedin
     * @param Request $request
     * @param $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback(Request $request, $provider)
    {
        try {
            $twitterId  = null;
            $linkedinId = null;
            $facebookId = null;
            $googleId   = null;

            //Redirect to login page if user cancel the login
            if ($request->has('error')) {
                return redirect()->to('login');
            }

            //Get user details from facebook
            if ($provider == 'twitter') {
                $socialUser = Socialite::driver($provider)->user();
                $twitterId = $socialUser->id;


            } else if($provider == 'linkedin') {
                $socialUser = Socialite::driver($provider)->stateless()->user();
                $linkedinId = $socialUser->id;

            }  else if($provider == 'facebook') {
                $socialUser = Socialite::driver($provider)->stateless()->user();
                $facebookId = $socialUser->id;

            }  else if($provider == 'google') {
                $socialUser = Socialite::driver($provider)->stateless()->user();
                $googleId = $socialUser->id;

            }

            $email = $socialUser->email;

            //Find email id in user table
            $findUser = User::where('email', $email)->first();
            //dd($findUser);
            //Authenticate and update twitter_id or linkedin_id or facebook_id of user if email id found
            if (count($findUser) == 1) {

                if ($provider == 'twitter') {
                    $findUser->is_verify    = 1;
                    $findUser->twitter_id   = $twitterId;
                    $findUser->save();

                } else if ($provider == 'linkedin') {
                    $findUser->is_verify    = 1;
                    $findUser->linkedin_id  = $linkedinId;
                    $findUser->save();

                } else if ($provider == 'facebook') {
                    $findUser->is_verify    = 1;
                    $findUser->facebook_id  = $facebookId;
                    $findUser->save();
                }

                Auth::login($findUser);
                return redirect()->route('customer.index');

            } else {
                //Create new user if email id not found
                $user = new User();
                $user->name         = $socialUser->name;
                $user->email        = $socialUser->email;
                $user->is_verify    = 1;
                $user->twitter_id   = $twitterId;
                $user->linkedin_id  = $linkedinId;
                $user->facebook_id  = $facebookId;
                $user->google_id    = $googleId;

                if($user->save()) {
                    $user->attachRole(config('app.roles.user.id'));
                    Auth::login($user);
                    return redirect()->route('customer.index');
                }
            }

        } catch (Exception $ex) {
            return redirect()->to('login')->with('failure', $ex->getMessage());
        }
    }
}
