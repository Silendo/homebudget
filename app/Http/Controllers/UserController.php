<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller {

	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(){
		$this->middleware('auth');
	}

	/**
	* Display the specified resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function show(){
		$user = Auth::user();
		return view('user.profile',['user' => $user]);
	}

	/**
	* Update a resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @return \Illuminate\Http\JsonResponse
	*/
	public function update(Request $request){
		$user = Auth::user();
		$value = '';
		if($request->has('name')) {
			$this->validate($request, [
				'name' => 'required',
        	]);
			$user->name = $request->name;
			$value = $request->name;
		}
		if($request->has('email')){
			$this->validate($request, [
				'email' => 'required|email|unique:users',
        	]);
			$user->email = $request->email;
			$value = $request->email;
		}
		if($request->has('month_report')){
			$user->month_report = $request->month_report;
			$value = $request->month_report;
		}
		$user -> save();
		return response()->json(['value' => $value]);
	}
}
