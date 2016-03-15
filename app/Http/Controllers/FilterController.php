<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use Input;
use App\User;
use App\Filter;
use Illuminate\Http\Request;
use App\Http\Requests;

class FilterController extends Controller
{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
    */
    public function __construct()
	{
		$this->middleware('auth');
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $filters = Filter::where('user_id', '=', $user->id)->get();
        return response()->json($filters);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $this->validate($request, [
            'title' => 'required'
        ]);
        $filter = new Filter;
        $filter->title = $request->title;
        $filter->filterUI = $request->filterUI;
        $filter->filterOptions = '';
        $filter->distribution = $request->distribution;
        $filter->user_id = $user->id;
        $filter->save();
        return response()->json($filter);
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
        $filter = Filter::findOrFail($id);
        $user = Auth::user();
        $this->validate($request, [
            'title' => 'required'
        ]);
        $filter->title = $request->title;
        $filter->filterUI = $request->filterUI;
        $filter->filterOptions = '';
        $filter->distribution = $request->distribution;
        $filter->user_id = $user->id;
        $filter->save();
        return response()->json($filter);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $filter = Filter::findOrFail($id);
        $filter->delete();
        return response()->json(['success' => true]);
    }
}
