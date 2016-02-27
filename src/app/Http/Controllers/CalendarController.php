<?php

namespace App\Http\Controllers;

use Auth;
use Askedio\Laravel5GoogleCalendar\Calendar;
use Illuminate\Http\Request;

class CalendarController extends Controller
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
        $_id = false;

        \App\GoogleCalendar\Calendar::setVar('calendar', '');
        $_calendars = \App\GoogleCalendar\Calendar::readCalendar();
        if ($_calendars->items) {
            $_found = false;
            foreach ($_calendars->items as $_cal) {
                if ($_cal->summary == $_calendar) {
                    $_id = $_cal->id;
                    break;
                }
            }
        }

        if (!$_id) {
            \App\GoogleCalendar\Calendar::setVar('calendar', 'create');
            $_results = \App\GoogleCalendar\Calendar::createCalendar([
          'summary'         => $request->input('calendar') ?: 'Bill Reminders',
          'description'     => 'Created by BillReminder',
        ]);
        // needs check if isset
        $_id = $_results->id;
        }

        if ($_id) {
            Auth::user()->calendar = $_id;
            Auth::user()->save();
        } else {
            die('shoot');
        }

        return redirect('home');
    }
}
