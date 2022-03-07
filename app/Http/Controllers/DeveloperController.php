<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;

class DeveloperController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('developers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        for($level = 1; $level <= Auth::user()->access_level; $level++) {
            $allowedAccessLevel[$level] = $level;
        }

        return view('developers.create')->with('allowedAccessLevel', $allowedAccessLevel);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $maxAccessLevel = Auth::user()->access_level;

        $validator = Validator::make($request->all(), [
            'registration_number' => 'required|string|max:255|unique:users|regex:/[A-Z]{1}[0-9]{4}$/',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'access_level' => "required|numeric|max:$maxAccessLevel",
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->messages());
        }

        // dd($request->all());

        if (User::create([
            'registration_number' => $request['registration_number'],
            'name' => $request['name'],
            'email' => $request['email'],
            'access_level' => $request['access_level'],
            'password' => bcrypt($request['password']),
        ])) {
            return redirect()->route('developer.index');
        } else {
            return back()->withErrors("");
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
