<?php

namespace App\Http\Controllers;

use Auth;
use Calendar;
use Illuminate\Http\Request;

class EventController extends Controller
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
     * Create a calendar, redirect home.
     *
     * @return redirect
     */
    public function store(Request $request)
    {
        $_calendar = $request->input('calendar') ?: 'Bill Reminders';
        $_event = [
        'summary'     => $request->input('summary'),
        'description' => filter_var($request->input('total'), FILTER_SANITIZE_NUMBER_INT).'|'.str_replace('|', '', $request->input('type')).'|unpaid',
        'start'       => [
          'dateTime' => \App\GoogleCalendar\Events::get_time($request->input('date').' 12am'),
          'timeZone' => config('timezone', 'America/Los_Angeles'),
        ],
        'end' => [
          'dateTime' => \App\GoogleCalendar\Events::get_time($request->input('date').' 12pm'),
          'timeZone' => config('timezone', 'America/Los_Angeles'),
        ],
        'recurrence' => [
          'RRULE:FREQ=MONTHLY',
        ],
        'reminders' => [
          'useDefault' => false,
          'overrides'  => [
              ['method' => 'email', 'minutes' => 24 * 60],
              ['method' => 'popup', 'minutes' => 10],
          ],
        ],
      ];

        \App\GoogleCalendar\Events::setVar('calendar', Auth::user()->calendar);
        \App\GoogleCalendar\Events::createEvents($_event);
      //dd(\App\GoogleCalendar\Events::$errors);

      return redirect('home')->withSuccess(true);
    }

    public function delete(Request $request, $event)
    {
        \App\GoogleCalendar\Events::setVar('calendar', Auth::user()->calendar);
        \App\GoogleCalendar\Events::deleteEvents($event);

        return redirect('home')->withSuccess(true);
    }

    public function show(Request $request, $event)
    {
        \App\GoogleCalendar\Events::setVar('calendar', Auth::user()->calendar);
        $_me = \App\GoogleCalendar\Events::readEvents($event)->items[0];

        $_results = [
          'event'   => $_me,
          'options' => explode('|', $_me->description),
        ];

        return view('edit')->with($_results);
    }

    public function unpaid(Request $request, $event)
    {
        \App\GoogleCalendar\Events::setVar('calendar', Auth::user()->calendar);
        $_me = \App\GoogleCalendar\Events::readEvents($event);

        $_event = [
        'summary'     => $_me->summary,
        'description' => str_replace('paid', 'unpaid', $_me->description),
        'start'       => [
          'dateTime' => $_me->start->dateTime,
          'timeZone' => config('timezone', 'America/Los_Angeles'),
        ],
        'end' => [
          'dateTime' => $_me->end->dateTime,
          'timeZone' => config('timezone', 'America/Los_Angeles'),
        ],
      ];

        \App\GoogleCalendar\Events::updateEvents($event, $_event);
          // dd(\App\GoogleCalendar\Events::$errors);

      return redirect('home')->withSuccess(true);
    }

    public function paid(Request $request, $event)
    {
        \App\GoogleCalendar\Events::setVar('calendar', Auth::user()->calendar);
        $_me = \App\GoogleCalendar\Events::readEvents($event);

        $_event = [
          'summary'     => $_me->summary,
          'description' => str_replace('unpaid', 'paid', $_me->description),
        ];

        \App\GoogleCalendar\Events::updateEvents($event, $_event);
         //  \App\GoogleCalendar\Events::$errors;

      return redirect('home')->withSuccess(true);
    }
}
