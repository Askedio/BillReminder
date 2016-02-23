<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use GoogleCalendar;

use Calendar;

class HomeController extends Controller
{


private function sortByOrder($a, $b) {
    return $a['date']->timestamp - $b['date']->timestamp;
}

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

      $_results = ['total' => 0, 'paid' => 0, 'events' => []];
      if(Auth::user()->calendar){
        \App\GoogleCalendar\Calendar::setVar('calendar', Auth::user()->calendar);
        $_items = \App\GoogleCalendar\Events::readEvents();
        $errors = \App\GoogleCalendar\Calendar::$errors;

        /* TO-DO: need proper error checking, in this case notFound = reset calendar */
        if(is_array($errors)){
          if($errors[0]->reason == 'notFound'){
            Auth::user()->calendar = '';
            Auth::user()->save();
          }
        }

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

      if(isset($_items->items) && count($_items->items) > 0){
        $_data = [];
        foreach($_items->items as $_item){
          if(!isset($_item->description)) continue;

          $_values = explode('|', $_item->description);
          $_total  = isset($_values[0]) && is_numeric($_values[0]) ? $_values[0] : 0;
          $_paid   = isset($_values[2]) && $_values[2] == 'paid' ? true : false;

          $_data[] = [
            'id'            => $_item->id,
            'rec_id'        => isset($_item->recurringEventId) ? $_item->recurringEventId : false,
            'summary'       => $_item->summary,
            'description'   => $_item->description,
            'total'         => $_total,
            'payment_type'  => (isset($_values[1]) ? $_values[1] : 'n/a'),
            'date'          => \Carbon\Carbon::createFromTimestamp(strtotime($_item->start->dateTime)),
            'paid'          => $_paid,
          ];

          $_results['total'] = $_results['total'] + $_total;
          if($_paid) $_results['paid'] = $_total;
       }
       



usort($_data, ['App\Http\Controllers\HomeController','sortByOrder']);


        $_results['events'] = $_data;

        }
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




        return view('home')->with($_results);
    }
}
