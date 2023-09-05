<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Nette\Schema\Helpers;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }


    public function myTestAddToLog()
    {
        \App\Helpers\LogActivity::addToLog('My Testing Add To Log.');
        dd('log insert successfully.');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function logActivity()
    {
        $logs = \App\Helpers\LogActivity::logActivityLists();
        return view('logActivity',compact('logs'));
    }
}
