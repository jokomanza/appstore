<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\BaseDocumentationController;

class DocumentationController extends BaseDocumentationController
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    function getUserType()
    {
        return 'admin';
    }
}
