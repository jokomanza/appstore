<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Permission;
use App\User;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|Application|View
     */
    public function index()
    {
        return view('admin.users.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateUserRequest $request
     * @return RedirectResponse
     */
    public function store(CreateUserRequest $request)
    {
        if (User::create([
            'registration_number' => $request['registration_number'],
            'name' => $request['name'],
            'email' => $request['email'],
            'access_level' => $request['access_level'],
            'password' => bcrypt('123456'),
        ])) return redirect()->route('admin.user.index')->with('messages', ['Successfully create new user account']);
        else return back()->withErrors("");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param $registrationNumber
     * @return Application|Factory|View
     */
    public function show(Request $request, $registrationNumber)
    {
        $user = User::find($registrationNumber);

        if ($user == null) return view('admin.errors.404');

        $apps = Permission::with('app')->where('user_registration_number', $user->registration_number)->get();

        return view('admin.users.show', compact('user', 'apps'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param $registrationNumber
     * @return Application|Factory|View
     */
    public function edit(Request $request, $registrationNumber)
    {
        $user = User::find($registrationNumber);

        if ($user == null) return view('admin.errors.404');

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $registrationNumber
     * @return Factory|Application|RedirectResponse|View
     */
    public function resetPassword(Request $request, $registrationNumber)
    {
        $user = User::find($registrationNumber);

        if ($user == null) return view('admin.errors.404');

        $user->password = bcrypt('123456');

        if ($user->update()) return back()->with('messages', ['Successfully reset user password']);
        else return back()->withErrors('Failed to reset user password');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param $registrationNumber
     * @return Factory|Application|RedirectResponse|View
     */
    public function update(UpdateUserRequest $request, $registrationNumber)
    {
        $user = User::find($registrationNumber);

        if ($user == null) return view('admin.errors.404');

        $user->fill($request->all());

        if ($user->update()) return redirect()->route('admin.user.show', $registrationNumber)->with('messages', ['Successfully update user data']);
        else return back()->withErrors('Failed to update user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $registrationNumber
     * @return Factory|Application|RedirectResponse|View
     * @throws Exception
     */
    public function destroy($registrationNumber)
    {
        $user = User::find($registrationNumber);

        if ($user == null) return view('admin.errors.404');

        if ($user->delete()) return redirect()->route('admin.user.index')->with('messages', ['Successfully delete user']);
        else return back()->withErrors("Failed to delete user account");
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getDataTables(Request $request)
    {
        $columns = [
            0 => 'registration_number',
            1 => 'name',
            2 => 'email',
            3 => 'registration_number',
        ];

        $totalData = User::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $users = User::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $users = User::where('registration_number', 'LIKE', "%$search%")
                ->orWhere('name', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = User::where('registration_number', 'LIKE', "%$search%")
                ->orWhere('name', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%")
                ->count();
        }

        $data = array();
        if (!empty($users)) {
            foreach ($users as $user) {
                $show = route('admin.user.show', $user->registration_number);
                $nestedData['registration_number'] = $user->registration_number;
                $nestedData['name'] = $user->name;
                $nestedData['email'] = $user->email;
                $nestedData['options'] = "<a href='$show' class='btn btn-success' >Show</a>";

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
