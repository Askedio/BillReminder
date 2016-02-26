<?php

namespace App\Helpers;

use App\GoogleCalendar\Calendar;
use App\GoogleCalendar\Events as GoogleEvents;
use Auth;
use Carbon\Carbon;

class BillReminder
{
    private static function errors()
    {
        $errors = Calendar::$errors;
            /* TO-DO: need proper error checking, in this case notFound = reset calendar */
            if (is_array($errors)) {
                if ($errors[0]->reason == 'notFound') {
                    Auth::user()->calendar = '';
                    Auth::user()->save();
                }

                return $errors;
            }

        return false;
    }

    private static function display($display)
    {
        switch ($display) {
             case '2month':
               Calendar::setVar('end', 'last day of next month');
             break;
             case '2week':
               Calendar::setVar('start', 'yesterday');
               Calendar::setVar('end', '+2 weeks');
             break;
            }
    }

    private static function hasItems($_items)
    {
        return !self::errors() && is_object($_items) && isset($_items->items) && count($_items->items) > 0;
    }

    public static function home($display)
    {
        $_results = ['total' => 0, 'paid' => 0, 'events' => []];

        if (Auth::user()->calendar) {
            self::display($display);

            Calendar::setVar('calendar', Auth::user()->calendar);

            $_items = GoogleEvents::readEvents();

            if (self::hasItems($_items)) {
                $_data = [];
                $_results['breakdown'] = [];

                foreach ($_items->items as $_item) {
                    if (!isset($_item->description) || !preg_match('/|/s', $_item->description)) {
                        continue;
                    }

                    $_values = explode('|', $_item->description);
                    $_total = isset($_values[0]) && is_numeric($_values[0]) ? $_values[0] : 0;
                    $_type = isset($_values[1]) ? $_values[1] : 'n/a';
                    $_paid = isset($_values[2]) && $_values[2] == 'paid' ? $_total : 0;

                    $_data[] = [
                    'id'            => $_item->id,
                    'rec_id'        => isset($_item->recurringEventId) ? $_item->recurringEventId : false,
                    'summary'       => $_item->summary,
                    'description'   => $_item->description,
                    'total'         => $_total,
                    'paid'          => $_paid,
                    'payment_type'  => $_type,
                    'date'          => Carbon::createFromTimestamp(strtotime($_item->start->dateTime)),
                  ];

                    $_results['breakdowns'][$_type]['total'] = (isset($_results['breakdowns'][$_type]['total']) ? $_results['breakdowns'][$_type]['total'] : 0) + $_total;
                    $_results['breakdowns'][$_type]['paid'] = (isset($_results['breakdowns'][$_type]['paid']) ? $_results['breakdowns'][$_type]['paid'] : 0) + $_paid;
                }

                usort($_data, ['App\Helpers\BillReminder', 'sortByOrder']);

                $_results['events'] = $_data;
                $_results['total'] = array_sum(array_column($_data, 'total'));
                $_results['paid'] = array_sum(array_column($_data, 'paid'));
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
          'dateTime' => GoogleEvents::get_time($request->input('date').' 10am'),
          'timeZone' => config('timezone', 'America/Los_Angeles'),
        ],
        'end' => [
          'dateTime' => GoogleEvents::get_time($request->input('date').' 10pm'),
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
