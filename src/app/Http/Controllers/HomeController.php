<?php

namespace App\Http\Controllers;

use App\Helpers\BillReminder;
use Auth;

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
     * @return \Illuminate\Http\Response
     */
    public function index($display = false)
    {
        return BillReminder::home($display);
    }
}
