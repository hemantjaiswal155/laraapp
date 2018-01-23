<?php

namespace App\Http\Controllers;

use App\Customer;
use App\DataTables\StatesDataTable;
use App\State;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class StateController extends Controller
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
        $this->middleware('permission:state_list|state_edit|state_delete', ['only' => ['index', 'edit', 'update', 'destroy']]);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StatesDataTable $dataTable)
    {
        try {
            return $dataTable->render('state.index');
            //return view('state.index', $data);
        } catch (\Exception $ex) {
            return redirect()->route('state.index')->with('failure', $ex->getMessage());
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
            $state = State::findOrFail($id);
            $data['state'] = $state;

            // Get validation messages
            $data['validationMessages'] = State::$validationMessagesForUpdate;

            //Render the user edit form
            return view('state.add', $data);
        } catch(ModelNotFoundException $ex) {
            return redirect()->route('state.index')->with('failure', 'This state is not available');
        } catch (\Exception $ex) {
            return redirect()->route('state.index')->with('failure',  $ex->getMessage());
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
            $validator = \Validator::make($data, State::validationRulesForUpdate($id), State::$validationMessagesForUpdate);

            if ($validator->fails()) {
                //Redirect user back with input if server side validation fails
                return redirect()->route('state.edit', $id)->withErrors($validator)->withInput();
            }

            $state               = State::findOrFail($id);
            $state->state_name   = $request->input('state_name');

            //Save state details
            if ($state->save()) {
                return redirect()->route('state.index')->with('success', 'State updated successfully');
            } else {
                return redirect()->route('state.index')->with('failure', config('app.messages.default_error'));
            }
        } catch(ModelNotFoundException $ex) {
            return redirect()->route('state.index')->with('failure', 'This state is not available');
        } catch (\Exception $ex) {
            return redirect()->route('state.edit', $id)->with('failure', $ex->getMessage());
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
            State::findOrFail($id);

            //Find state id in user table
            $usersState       = User::where('state_id', $id)->get(['id']);

            //Find state id in customer table
            $customersState   = Customer::where('state_id', $id)->get(['id']);

            if (count($usersState) > 0 || count($customersState) > 0) {
                return redirect()->route('state.index')->with('failure', 'This state is associated with users or customers, so it can not be deleted.');
            } else {
                //Delete state
                if(State::destroy($id)) {
                    return redirect()->route('state.index')->with('success', 'State has been deleted successfully.');
                }
            }
        } catch (ModelNotFoundException $ex) {
            return redirect()->route('state.index')->with('failure', 'State not available');
        } catch (\Exception $ex) {
            return redirect()->route('state.index')->with('failure', $ex->getMessage());
        }
    }
}
