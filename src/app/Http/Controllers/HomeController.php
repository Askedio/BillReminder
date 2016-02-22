<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
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
    public function index()
    {



$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Authorization: Bearer " . Auth::user()->token 
  )
);

$context = stream_context_create($opts);

// Open the file using the HTTP headers set above
//$file = json_decode(file_get_contents('https://www.googleapis.com/calendar/v3/calendars/primary/events?key=AIzaSyAz3-7FQ4bX_DnrLOKZhC4VWo99WHERay0&timeMin=2016-01-01T10%3A00%3A00-07%3A00', false, $context));



//$file = json_decode(file_get_contents('https://www.googleapis.com/calendar/v3/users/me/calendarList?key=AIzaSyAz3-7FQ4bX_DnrLOKZhC4VWo99WHERay0', false, $context));

$file = json_decode(file_get_contents('https://www.googleapis.com/calendar/v3/calendars/kd0npb1udll8eqi88kqd3qa4ks@group.calendar.google.com?key=AIzaSyAz3-7FQ4bX_DnrLOKZhC4VWo99WHERay0', false, $context));

dd($file);

$postdata = array( 'summary' => 'Bill Payment',);




$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"https://www.googleapis.com/calendar/v3/calendars?key=AIzaSyAz3-7FQ4bX_DnrLOKZhC4VWo99WHERay0");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$headers = array();
$headers[] = "Content-type: application/json";
$headers[] = "Authorization: Bearer " . Auth::user()->token;

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$server_output = curl_exec ($ch);

curl_close ($ch);

print  $server_output ;



/*


GET https://www.googleapis.com/calendar/v3/users/me/calendarList

POST https://www.googleapis.com/calendar/v3/users/me/calendarList



create



*/
        return view('home');
    }
}
