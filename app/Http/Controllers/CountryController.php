<?php

namespace App\Http\Controllers;

use App\Country;
use App\Customer;
use App\State;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;


class CountryController extends Controller
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        //Auth middleware
        $this->middleware('auth');

        //Apply input cleaning middleware before processing any request
        $this->middleware('clean-input');

        //Permission filters
        $this->middleware('permission:country_list|country_edit|country_delete', ['only' => ['index', 'edit', 'update', 'destroy']]);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data['countries'] = Country::orderBy('country_name', 'asc')->get();

            //Render the view to show country listing
            return view('country.index', $data);
        } catch (\Exception $ex) {
            return redirect()->route('country.index')->with('failure', $ex->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

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
            $country = Country::findOrFail($id);
            $data['country'] = $country;

             // Get validation messages
             $data['validationMessages'] = Country::$validationMessagesForUpdate;

            //Render the user edit form
            return view('country.add', $data);
        } catch(ModelNotFoundException $ex) {
            return redirect()->route('country.index')->with('failure', 'This country is not available.');
        } catch (\Exception $ex) {
            return redirect()->route('country.index')->with('failure',  $ex->getMessage());
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

            //Server side validations
            $validator = \Validator::make($data, Country::validationRulesForUpdate($id), Country::$validationMessagesForUpdate);

            if ($validator->fails()) {
                //Redirect user back with input if server side validation fails
                return redirect()->route('country.edit', $id)->withErrors($validator)->withInput();
            }

            $country               = Country::findOrFail($id);
            $country->country_name = $request->input('country_name');


            //Save country details
            if ($country->save()) {
                return redirect()->route('country.index')->with('success', 'Country updated successfully.');
            } else {
                return redirect()->route('country.index')->with('failure', config('app.messages.default_error'));
            }
        } catch(ModelNotFoundException $ex) {
            return redirect()->route('country.index')->with('failure', 'This country is not available');
        } catch (\Exception $ex) {
            return redirect()->route('country.edit', $id)->with('failure', $ex->getMessage());
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
            Country::findOrFail($id);

            //Find country id in user table
            $usersCountry       = User::where('country_id', $id)->get(['id']);

            //Find country id in customer table
            $customersCountry   = Customer::where('country_id', $id)->get(['id']);

            if (count($usersCountry) > 0 || count($customersCountry) > 0) {

                return redirect()->route('country.index')->with('failure', 'This country is associated with users or customers, so it can not be deleted.');
            } else {
                //Delete all states of given country id
                State::where('country_id', $id)->delete();

                //Delete country
                if(Country::destroy($id)) {
                    return redirect()->route('country.index')->with('success', 'Country has been deleted successfully.');
                }
            }
        } catch (ModelNotFoundException $ex) {
            return redirect()->route('country.index')->with('failure', 'Country not available.');
        } catch (\Exception $ex) {
            return redirect()->route('country.index')->with('failure', $ex->getMessage());
        }
    }

    /**
     * Check duplicate country or state
     * @param Request $request
     */
    public function checkDuplicateName(Request $request, $id)
    {
        if($request->ajax()) {
            try{
                $countryName = $request->country_name;
                $stateName   = $request->state_name;

                if (!empty($countryName)) {

                        if(isset($id) && $id > 0) {
                            $country = Country::where('country_name', $countryName)->where('id', '!=', $id)->count();
                        } else {
                            $country = Customer::where('country_name', $countryName)->count();
                        }

                    if ($country > 0) {
                        abort(404);
                    }
                }

                if (!empty($stateName)) {

                    if(isset($id) && $id > 0) {
                        $state = State::where('state_name', $stateName)->where('id', '!=', $id)->count();
                    } else {
                        $state = State::where('state_name', $stateName)->count();
                    }

                    if ($state > 0) {
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

}
