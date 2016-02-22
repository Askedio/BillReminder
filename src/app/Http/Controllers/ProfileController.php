<?php

namespace App\Http\Controllers;

use Auth;

class ProfileController extends Controller
{


  public function show()
  {

  dd(Auth::user());


return "a";
  }

}
