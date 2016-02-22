<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use GoogleCalendar;

use Calendar;

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

      $_results = ['total' => 0, 'paid' => 0];
      if(Auth::user()->calendar){
        \App\GoogleCalendar\Calendar::setVar('calendar', Auth::user()->calendar);
        $_items = \App\GoogleCalendar\Events::readEvents()->items;

/*

loop items
grab start time + tz
make to date str

+"start": {#187 
      +"dateTime": "2016-02-22T21:30:00Z"
      +"timeZone": "America/Los_Angeles"
    }



+"recurrence": array:1 [
      0 => "RRULE:FREQ=MONTHLY;BYDAY=4MO"
    ]


*/

        foreach($_items as $_item){
          if(!isset($_item->description)) continue;
          $_values = explode('|', $_item->description);
          $_total  = number_format((isset($_values[0]) && is_numeric($_values[0]) ? $_values[0] : 0), 2);
          $_paid   = isset($_values[2]) && $_values[2] = 'paid' ? $_values[2] : 'unpaid';

          $_data[] = [
            'summary'       => $_item->summary,
            'description'   => $_item->description,
            'total'         => $_total,
            'payment_type'  => (isset($_values[1]) ? $_values[1] : 'n/a'),
            'date'          => \Carbon\Carbon::createFromTimestamp(strtotime($_item->start->dateTime)),
            'paid'          => $_paid,
          ];

          $_results['total'] = number_format($_results['total'] + $_total, 2);
          if($_paid) $_results['paid'] = number_format($_total, 2);
        }

        $_results['events'] = $_data;
      }


// create/update event
$event = 
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


// crreate/update cal 
$cal = [
  'description' => '',
  'summary'     => '',
];




        return view('home')->with($_results);;
    }
}
