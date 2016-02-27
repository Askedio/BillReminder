<?php

namespace App\Http\Controllers;

use Askedio\Laravel5GoogleCalendar\Events as GoogleEvents;
use App\Helpers\BillReminder;
use Auth;
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
        GoogleEvents::setVar('calendar', Auth::user()->calendar);
        GoogleEvents::createEvents(BillReminder::eventData($request));

        return redirect('home')->withSuccess(true);
    }

    public function delete(Request $request, $event)
    {
        GoogleEvents::setVar('calendar', Auth::user()->calendar);
        GoogleEvents::deleteEvents($event);

        return redirect('home')->withSuccess(true);
    }

    public function show(Request $request, $event)
    {
        GoogleEvents::setVar('calendar', Auth::user()->calendar);

        /* TO-DO: needs error checking */
        $_event = GoogleEvents::readEvents($event)->items[0];

        $_results = [
          'event'   => $_event,
          'options' => explode('|', $_event->description),
        ];

        return view('edit')->with($_results);
    }

    public function unpaid(Request $request, $event)
    {
        BillReminder::process($event, 'unpaid');

        return redirect('home')->withSuccess(true);
    }

    public function paid(Request $request, $event)
    {
        BillReminder::process($event, 'paid');

        return redirect('home')->withSuccess(true);
    }
}
