<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\App;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    

    /**
     * 
     * 
     */
    public function getAppsDataTable(Request $request) {

        $columns = [
            0 => 'id',
            1 => 'name',
            2 => 'package_name',
            3 => 'updated_at',
        ];

        $totalData = App::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = App::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts = App::where('package_name', 'LIKE', "%$search%")
                ->orWhere('name', 'LIKE', "%$search%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = App::where('package_name', 'LIKE', "%$search%")
                ->orWhere('name', 'LIKE', "%$search%")
                ->count();
        }
        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $show = route('app.show', $post->id);
                $edit = route('app.edit', $post->id);
                $nestedData['id'] = $post->id;
                $nestedData['name'] = $post->name;
                $nestedData['package_name'] = $post->package_name;
                $nestedData['updated_at'] = (new Carbon($post->updated_at))->diffForHumans();
                $nestedData['options'] = "&emsp;<a href='$show' class='btn btn-secondary'>Show</a>
                      &emsp;<a href='$edit' class='btn btn-success' >Edit</a>";
                $data[] = $nestedData;
            }
        }
        $json_data = array("draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        
        return response()->json($json_data);
    }


    public function getDevelopersDataTable(Request $request) {

        $access_level = Auth::user()->access_level;

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
            $posts = User::where('access_level', '<=', $access_level)->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts = User::where('access_level', '<=', $access_level)->where('registration_number', 'LIKE', "%$search%")
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
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $show = route('app.show', $post->id);
                $edit = route('app.edit', $post->id);
                $nestedData['registration_number'] = $post->registration_number;
                $nestedData['name'] = $post->name;
                $nestedData['email'] = $post->email;
                $nestedData['access_level'] = $post->access_level;
                $nestedData['options'] = "&emsp;<a href='$show' class='btn btn-secondary'>Show</a>
                      &emsp;<a href='$edit' class='btn btn-success' >Edit</a>";
                $data[] = $nestedData;
            }
        }
        $json_data = array("draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        
        return response()->json($json_data);
    }
}
