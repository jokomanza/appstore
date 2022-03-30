<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Base\BaseDocumentationController;

class DocumentationController extends BaseDocumentationController
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    function getUserType()
    {
        return 'user';
    }
}
