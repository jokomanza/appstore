<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Auth\Access\AuthorizationException;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->access_level != 3)
            throw new AuthorizationException();

        if (Auth::user()->access_level != 3) return view('errors.400');
        return view('developers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if (Auth::user()->access_level != 3)
            return view('errors.400');

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

        if (Auth::user()->access_level != 3) throw new AuthorizationException();

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


    public function getDataTables(Request $request)
    {

        $access_level = Auth::user()->access_level;
        $current_reg_num = Auth::user()->registration_number;

        $columns = [
            0 => 'registration_number',
            1 => 'name',
            2 => 'email',
            3 => 'access_level',
        ];

        $totalData = User::where('access_level', '<=', $access_level)->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $users = User::where('access_level', '<=', $access_level)->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $users = User::where('access_level', '<=', $access_level)->where('registration_number', 'LIKE', "%$search%")
                ->orWhere('name', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = User::where('access_level', '<=', $access_level)->where('registration_number', 'LIKE', "%$search%")
                ->orWhere('name', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%")
                ->count();
        }
        $data = array();
        if (!empty($users)) {
            foreach ($users as $user) {
                $show = route('app.show', $user->id);
                $edit = route('app.edit', $user->id);
                $nestedData['registration_number'] = $user->registration_number;
                $nestedData['name'] = $user->name;
                $nestedData['email'] = $user->email;
                $nestedData['access_level'] = $user->access_level;
                if ($user->access_level <= $access_level) {
                    $nestedData['options'] = ($current_reg_num != $user->registration_number ? "&emsp;<a href='$show' class='btn btn-danger'>Delete</a>" : '')
                        . "&emsp;<a href='$edit' class='btn btn-success' >Edit</a>";
                }
                else {
                    $nestedData['options'] = "";
                }
                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        return response()->json($json_data);
    }
}
