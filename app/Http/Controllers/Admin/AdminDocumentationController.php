<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminDocumentationController extends Controller
{
    public function __construct() {
        $this->middleware('auth:admin');
    }

    public function index() {
        return view('admin.documentation.index');
    }
}
