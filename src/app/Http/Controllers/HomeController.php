<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use GoogleCalendar;

use GoogleCalendarHelper;

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



$post = 
    array(
  'summary' => 'Google I/O 2015',
  'location' => '800 Howard St., San Francisco, CA 94103',
  'description'=> 'A chance to hear more about Google\'s developer products.',
  'start' => [
    'dateTime'=> '2016-02-23T09:00:00-07:00',
    'timeZone'=>'America/Los_Angeles'
  ],
  'end' => [
    'dateTime' => '2016-02-24T17:00:00-07:00',
    'timeZone'=> 'America/Los_Angeles'
  ],
  'recurrence'=> [
    'RRULE:FREQ=DAILY;COUNT=2'
  ],
  'attendees'=> [
    ['email'=> 'lpage@example.com'],
    ['email'=> 'sbrin@example.com']
  ],
  'reminders'=> [
    'useDefault'=> false,
    'overrides'=> [
      ['method'=> 'email', 'minutes'=> 24 * 60],
      ['method'=> 'popup', 'minutes'=> 10]
    ]
  ]

    );

GoogleCalendar::deleteCalendar('kd0npb1udll8eqi88kqd3qa4ks@group.calendar.google.com');
dd(GoogleCalendar::$errors);


$cal=GoogleCalendar::postEvents('primary', $post);
if($cal == false) dd(GoogleCalendar::$errors);

        return view('home');
    }
}
