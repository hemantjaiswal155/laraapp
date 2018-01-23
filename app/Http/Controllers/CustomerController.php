<?php

namespace App\Http\Controllers;

use App\Country;
use App\Customer;
use App\User;
use App\State;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Yajra\Datatables\Datatables;
use Zizaco\Entrust\Entrust;


class CustomerController extends Controller
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        //Auth middleware
        $this->middleware('auth')->except(['getStates']);

        //Apply input cleaning middleware before processing any request
        $this->middleware('clean-input');

        //Permission filters
        $this->middleware('permission:customer_list|customer_view', ['only' => ['index', 'show']]);
        $this->middleware('permission:customer_edit|customer_add', ['only' => ['create', 'store', 'edit', 'update']]);
        $this->middleware('permission:customer_delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $data['title'] = 'Customer list';
            //Render the view to show customer listing
            if($request->ajax()){
                $columns = ['id',
                  
                    'email', 'gender',
                    'mobile'
              
                ];

                //Get all customers
                $users = Customer::select($columns);
                return Datatables::of($users)->addColumn('action', function ($user) {
                    $data['user'] = $user;
                    $return = view('customer.partials.actions',$data)->render();
                    return $return;
                })->editColumn('gender', function ($user) {
                    if ($user->gender == '') {
                        return 'N/A';
                    }
                    return $user->gender;
                })->make(true);
            }
            return view('customer.index', $data);
        } catch (\Exception $ex) {
            return redirect()->route('customer.index')->with('failure', $ex->getMessage());
        }
    }

    /*
     * Show the form for creating a new resource
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $data['customer'] = [];

            // Get validation messages
            $data['validationMessages'] = Customer::$validationMessages;

            //Get all country
            $data['countries'] = Country::getAllCountry();

            $data['states'] = ['' => 'Select state'];

            //Render the customer add form
            return view('customer.add', $data);

        } catch (\Exception $ex) {
            return redirect()->route('customer.index')->with('failure', $ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();

            //Server side validations
            $validator = \Validator::make($data, Customer::validationRules(), Customer::$validationMessages);

            if ($validator->fails()) {
                //Redirect user back with input if server side validation fails
                return redirect()->route('customer.create')->withErrors($validator)->withInput();
            }

            $customer = new Customer();
            $customer->first_name   = $request->input('first_name');
            $customer->last_name    = $request->input('last_name');
            $customer->gender       = $request->input('gender');
            $customer->email        = $request->input('email');
            $customer->mobile       = $request->input('mobile');
            $customer->country_id   = $request->input('country_id');
            $customer->state_id     = $request->input('state_id');

            //Save customer details
            if ($customer->save()) {
                return redirect()->route('customer.index')->with('success', 'Customer created successfully.');
            } else {
                return redirect()->route('customer.index')->with('failure', config('app.messages.default_error'));
            }
        } catch (\Exception $ex) {
            return redirect()->route('customer.create')->with('failure', $ex->getMessage());
        }
    }

    /**
     * Display the specified resource
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data['customer'] = Customer::leftJoin('countries', 'countries.id', '=', 'customers.country_id')
                                            ->leftJoin('states', 'states.id', '=', 'customers.state_id')
                                            ->findOrFail($id);

            return view('customer.show', $data);
        } catch(ModelNotFoundException $ex) {
            return redirect()->route('customer.index')->with('failure', 'This customer is not available.');
        } catch (\Exception $ex) {
            return redirect()->route('customer.index')->with('failure', $ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            // Get validation messages
            $data['validationMessages'] = Customer::$validationMessages;
            $data['customer'] = Customer::findOrFail($id);

            //Get all country
            $data['countries'] = Country::getAllCountry();

            $data['states'] = State::getSateByCountry($data['customer']->country_id);

            //Render the customer edit form
            return view('customer.add', $data);
        } catch(ModelNotFoundException $ex) {
            return redirect()->route('customer.index')->with('failure', 'This customer is not available.');
        } catch (\Exception $ex) {
            return redirect()->route('customer.index')->with('failure',  $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();

            //Server side validations
            $validator = \Validator::make($data, Customer::validationRulesForUpdate($id), Customer::$validationMessages);

            if ($validator->fails()) {
                //Redirect user back with input if server side validation fails
                return redirect()->route('customer.edit', $id)->withErrors($validator)->withInput();
            }
            $customer = Customer::findOrFail($id);
            $customer->first_name   = $request->input('first_name');
            $customer->last_name    = $request->input('last_name');
            $customer->gender       = $request->input('gender');
            $customer->email        = $request->input('email');
            $customer->mobile       = $request->input('mobile');
            $customer->country_id   = $request->input('country_id');
            $customer->state_id     = $request->input('state_id');

            //Save customer details
            if ($customer->save()) {
                return redirect()->route('customer.index')->with('success', 'Customer updated successfully.');
            } else {
                return redirect()->route('customer.index')->with('failure', config('app.messages.default_error'));
            }
        } catch(ModelNotFoundException $ex) {
            return redirect()->route('customer.index')->with('failure', 'This customer is not available.');
        } catch (\Exception $ex) {
            return redirect()->route('customer.edit', $id)->with('failure', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            //Find details by id
            $customer = Customer::findOrFail($id);
            if($customer->delete()) {
                return redirect()->route('customer.index')->with('success', 'Customer deleted successfully.');
            } else {
                return redirect()->route('customer.index')->with('failure', config('app.messages.default_error'));
            }
        } catch (ModelNotFoundException $ex) {
            return redirect()->route('customer.index')->with('failure', 'Customer not available');
        } catch (\Exception $ex) {
            return redirect()->route('customer.index')->with('failure', $ex->getMessage());
        }
    }

    /**
     * Check duplicate email
     *
     * @param Request $request
     */
    public function checkEmail(Request $request, $id) {
        if($request->ajax()) {
            try{
                $email = $request->email;

                if (!empty($email)) {

                    if (isset($request->user) && $request->user == 'yes') {
                        if(isset($id) && $id > 0) {
                            $userEmail = User::where('email', $email)->where('id', '!=', $id)->count();

                        } else {
                            $userEmail = User::where('email', $email)->count();

                        }
                    } else {
                        if(isset($id) && $id > 0) {
                            $userEmail = Customer::where('email', $email)->where('id', '!=', $id)->count();
                        } else {
                            $userEmail = Customer::where('email', $email)->count();
                        }
                    }

                    if ($userEmail > 0) {
                        abort(404);
                    }
                }

                return response(\Helpers::sendSuccessAjaxResponse('success'), 200);
            } catch (\Exception $ex) {
                return response(\Helpers::sendFailureAjaxResponse(), 500);
            }
        } else {
            return view('errors.404');
        }
    }

    /**
     * Get states via ajax
     * @param Request $request
     */
    public function getStates(Request $request)
    {
        if($request->ajax()) {
            try {
                $countryId = $request->country_id;

                if(isset($countryId) && $countryId > 0) {

                    //Get states by country id
                    $states = State::where('country_id', $countryId)
                                    ->orderBy('state_name')
                                    ->pluck('state_name', 'id')
                                    ->all();

                    return response(\Helpers::sendSuccessAjaxResponse('success', $states), 200);
                } else {
                    //Send failure response
                    return response(\Helpers::sendFailureAjaxResponse(), 500);
                }

            } catch (\Exception $ex) {
                return response(\Helpers::sendFailureAjaxResponse(), 500);
            }

        } else {
            return view('errors.404');
        }
    }
}
