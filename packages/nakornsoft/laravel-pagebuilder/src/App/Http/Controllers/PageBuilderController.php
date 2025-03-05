<?php

namespace Nakornsoft\PageBuilder\App\Http\Controllers;

use App\Http\Controllers\Controller;


class PageBuilderController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('pagebuilder::builder');
    }

}
