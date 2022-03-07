<?php

use Illuminate\Http\Request;
use App\Models\App;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

/* |-------------------------------------------------------------------------- | API Routes |-------------------------------------------------------------------------- | | Here is where you can register API routes for your application. These | routes are loaded by the RouteServiceProvider within a group which | is assigned the "api" middleware group. Enjoy building your API! | */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('apps/datatables', function (Request $request) {
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
        $totalFiltered = App::where('package_name','LIKE', "%$search%")
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
    echo json_encode($json_data);

})->name('api.apps.datatables');