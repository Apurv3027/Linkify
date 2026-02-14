<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function privacy()
    {
        return view('pages.privacy');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function support()
    {
        return view('pages.support');
    }

    public function error404()
    {
        return view('errors.404');
    }
}
