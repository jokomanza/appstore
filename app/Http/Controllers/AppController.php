<?php

namespace App\Http\Controllers;

use App\Interfaces\AppRepositoryInterface;
use App\Models\App;
use App\Repositories\AppRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Psy\Util\Json;

/**
 * Class App Controller
 * 
 * @property AppRepositoryInterface $appRepository
 * 
 * @package App\Http\Controllers
 */
class AppController extends Controller
{
    private $appRepository;

    // AppRepositoryInterface $appRepository
    public function __construct(AppRepositoryInterface $appRepository)
    {
        $this->appRepository = $appRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->appRepository->paginate(10);
        return view('apps.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('apps.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->file('user_documentation_file'));
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:30',
            'package_name' => 'required|string|regex:/com.quick.[a-z0-9]{3,30}$/',
            'description' => 'required|string|max:300',
            'repository_url' => 'required|url',
            'icon_file' => 'nullable|mimes:jpeg,jpg,png|max:10000',
            'user_documentation_file' => 'nullable|mimes:pdf|max:10000',
            'developer_documentation_file' => 'nullable|mimes:pdf|max:10000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->messages())->withInput();
        }

        $app = App::where('name', $request->name)
            ->orWhere('package_name', $request->package_name)
            ->first();
        if (!empty($app))
            return back()->withErrors(['name' => "App with that name already exists"]);

        DB::beginTransaction();

        $app = new App();
        $app->fill($request->all());

        if ($request->hasfile('user_documentation_file')) {
            $storedUserDoc = $app->package_name . '.user_documentation_file.' . time() . '.pdf';
            if (!$request->file('user_documentation_file')->move(public_path('/storage/'), $storedUserDoc)) {
                if (isset($storedUserDoc)) @unlink(public_path('/storage/') . $storedUserDoc);

                return back()->withErrors(['user_documentation_file' => 'failed to save user documentation pdf file']);
            }
        }

        if ($request->hasfile('developer_documentation_file')) {
            $storedDevDoc = $app->package_name . '.developer_documentation_file.' . time() . '.pdf';
            if (!$request->file('developer_documentation_file')->move(public_path('/storage/'), $storedDevDoc)) {
                if (isset($storedUserDoc)) @unlink(public_path('/storage/') . $storedUserDoc);
                if (isset($storedDevDoc)) @unlink(public_path('/storage/') . $storedDevDoc);

                return back()->withErrors(['developer_documentation_file' => 'failed to save developer documentation pdf file']);
            }
        }

        if ($request->hasfile('icon_file')) {
            $extension = $request->file('icon_file')->getClientOriginalExtension();

            $storedIconName = $app->package_name . '.default_icon.' . time() . ".$extension";
            if (!$request->file('icon_file')->move(public_path('/storage/'), $storedIconName)) {
                if (isset($storedUserDoc)) @unlink(public_path('/storage/') . $storedUserDoc);
                if (isset($storedDevDoc)) @unlink(public_path('/storage/') . $storedDevDoc);
                if (isset($storedIconName)) @unlink(public_path('/storage/') . $storedIconName);

                return back()->withErrors(['icon_file' => 'failed to save icon file']);
            }
        }

        if (isset($storedUserDoc)) {
            $app->user_documentation_url = $storedUserDoc;
        }
        if (isset($storedDevDoc)) {
            $app->developer_documentation_url = $storedDevDoc;
        }
        if (isset($storedIconName)) {
            $app->icon_url = $storedIconName;
        }

        if (!$app->save()) {
            DB::rollback();

            // Delete uploaded file if failed to saving app
            if (isset($storedUserDoc)) {
                @unlink(public_path('/storage/') . $storedUserDoc);
            }
            if (isset($storedDevDoc)) {
                @unlink(public_path('/storage/') . $storedDevDoc);
            }
            return back()->withErrors(['process' => "Failed to save the app"]);
        }

        DB::commit();
        return response()->json($app);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->appRepository->getAppById($id);

        return view('apps.show', ['data' => $data]);
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
