<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAdminRequest;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{

    /**
     * Create new controller instance.
     *
     */
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
        return view('admin.admins.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateAdminRequest $request
     * @return RedirectResponse
     */
    public function store(CreateAdminRequest $request)
    {
        try {
            if (Admin::create([
                'registration_number' => $request['registration_number'],
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => bcrypt('123456'),
            ])) {
                return redirect()->route('admin.admin.index')->with('messages', ['Successfully create new admin account']);
            } else return back()->withErrors("Failed to create new admin");
        } catch (Exception $exception) {
            return back()->withErrors("Error " . $exception->getCode())->withInput();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('admin.admins.create');
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
        ];

        $totalData = Admin::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $users = Admin::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $users = Admin::where('registration_number', 'LIKE', "%$search%")
                ->orWhere('name', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Admin::where('registration_number', 'LIKE', "%$search%")
                ->orWhere('name', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%")
                ->count();
        }
        $data = array();
        if (!empty($users)) {
            foreach ($users as $user) {
                $show = route('admin.app.show', $user->id);
                $edit = route('admin.app.edit', $user->id);
                $nestedData['registration_number'] = $user->registration_number;
                $nestedData['name'] = $user->name;
                $nestedData['email'] = $user->email;
                $nestedData['options'] = "";
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
