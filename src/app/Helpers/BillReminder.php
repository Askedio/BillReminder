<?php

namespace App\Helpers;

use App\GoogleCalendar\Calendar;
use App\GoogleCalendar\Events as GoogleEvents;
use Auth;
use Carbon\Carbon;


class BillReminder
{
    public static function home()
    {
        $_results = ['total' => 0, 'paid' => 0, 'events' => []];
        if (Auth::user()->calendar) {
            Calendar::setVar('calendar', Auth::user()->calendar);
            $_items = GoogleEvents::readEvents();
            $errors = Calendar::$errors;

            /* TO-DO: need proper error checking, in this case notFound = reset calendar */
            if (is_array($errors)) {
                if ($errors[0]->reason == 'notFound') {
                    Auth::user()->calendar = '';
                    Auth::user()->save();
                }
            }

            if (isset($_items->items) && count($_items->items) > 0) {
                $_data = [];
                foreach ($_items->items as $_item) {
                    if (!isset($_item->description)) {
                        continue;
                    }

                    $_values = explode('|', $_item->description);
                    $_total = isset($_values[0]) && is_numeric($_values[0]) ? $_values[0] : 0;
                    $_paid = isset($_values[2]) && $_values[2] == 'paid' ? true : false;

                    $_data[] = [
                    'id'            => $_item->id,
                    'rec_id'        => isset($_item->recurringEventId) ? $_item->recurringEventId : false,
                    'summary'       => $_item->summary,
                    'description'   => $_item->description,
                    'total'         => $_total,
                    'payment_type'  => (isset($_values[1]) ? $_values[1] : 'n/a'),
                    'date'          => Carbon::createFromTimestamp(strtotime($_item->start->dateTime)),
                    'paid'          => $_paid,
                  ];

                    $_results['total'] = $_results['total'] + $_total;
                    if ($_paid) {
                        $_results['paid'] = $_results['paid'] + $_total;
                    }
                }

                usort($_data, ['App\Helpers\BillReminder', 'sortByOrder']);

                $_results['events'] = $_data;
            }
        }

        return view('home')->with($_results);
    }

    private static function sortByOrder($a, $b)
    {
        return $a['date']->timestamp - $b['date']->timestamp;
    }

    public static function process($event, $status)
    {
        GoogleEvents::setVar('calendar', Auth::user()->calendar);
        $_me = GoogleEvents::readEvents($event);

        $_status = ($status == 'paid'
        ? str_replace('unpaid', 'paid', $_me->description)
        : str_replace('paid', 'unpaid', $_me->description));

        $_event = [
        'summary'     => $_me->summary,
        'description' => $_status,
      ];

        GoogleEvents::updateEvents($event, $_event);
    }

    public static function eventData($request)
    {
        $_calendar = $request->input('calendar') ?: 'Bill Reminders';

        return [
        'summary'     => $request->input('summary'),
        'description' => filter_var($request->input('total'), FILTER_SANITIZE_NUMBER_INT).'|'.str_replace('|', '', $request->input('type')).'|unpaid',
        'start'       => [
          'dateTime' => GoogleEvents::get_time($request->input('date').' 12am'),
          'timeZone' => config('timezone', 'America/Los_Angeles'),
        ],
        'end' => [
          'dateTime' => GoogleEvents::get_time($request->input('date').' 12pm'),
          'timeZone' => config('timezone', 'America/Los_Angeles'),
        ],
        'recurrence' => [
          'RRULE:FREQ='.str_replace(' ', '', strtoupper($request->input('repeat'))),
        ],
        'reminders' => [
          'useDefault' => false,
          'overrides'  => [
              ['method' => 'email', 'minutes' => $request->input('reminder_email')],
              ['method' => 'popup', 'minutes' => $request->input('reminder_popup')],
          ],
        ],
      ];
    }
}
