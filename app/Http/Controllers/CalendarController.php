<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Event;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $year  = (int)($request->input('year', now()->year));
        $month = (int)($request->input('month', now()->month));

        $firstDay    = Carbon::create($year, $month, 1);
        $daysInMonth = $firstDay->daysInMonth;
        $startDay    = $firstDay->dayOfWeek;
        $today       = Carbon::today()->toDateString();

        // Build dates array
        $dates = [];
        for ($i = 0; $i < $startDay; $i++) {
            $dates[] = null;
        }
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dates[] = Carbon::create($year, $month, $d)->toDateString();
        }

        // Events for this month
        $events = Event::whereBetween(
                'event_date',
                [$firstDay->toDateString(), $firstDay->copy()->endOfMonth()->toDateString()]
            )
            ->orderBy('event_date')
            ->get();

        $eventsByDate = $events->groupBy(fn($e) => Carbon::parse($e->event_date)->toDateString());

        // Favourites from pivot table
        $favIds = DB::table('event_user')
            ->where('user_id', auth()->id())
            ->pluck('event_id');

        // Find next favourite event
        $nextFav = collect($eventsByDate)
            ->flatMap(fn($evs, $date) => collect($evs)->map(fn($e) => [
                'date'  => $date,
                'event' => $e,
                'isFav' => $favIds->contains($e->id),
            ]))
            ->filter(fn($x) => $x['isFav'] && $x['date'] >= $today)
            ->sortBy('date')
            ->first();

        $monthName = $firstDay->format('F Y');

        return view('events.calendar', [
            'year'         => $year,
            'month'        => $month,
            'dates'        => $dates,
            'today'        => $today,
            'eventsByDate' => $eventsByDate,
            'favIds'       => $favIds,
            'nextFav'      => $nextFav,
            'monthName'    => $monthName,
        ]);
    }

    // Export events as ICS file
    public function export()
    {
        $events = Event::where('user_id', auth()->id())->get();

        $ics = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//YourApp//EN\r\n";

        foreach ($events as $event) {
            $start = Carbon::parse($event->event_date)->format('Ymd\THis');
            $end   = Carbon::parse($event->event_date)->addHours(2)->format('Ymd\THis');

            $ics .= "BEGIN:VEVENT\r\n";
            $ics .= "SUMMARY:{$event->title}\r\n";
            $ics .= "DTSTART:$start\r\n";
            $ics .= "DTEND:$end\r\n";
            $ics .= "DESCRIPTION:{$event->description}\r\n";
            $ics .= "LOCATION:{$event->venue}\r\n";
            $ics .= "END:VEVENT\r\n";
        }

        $ics .= "END:VCALENDAR\r\n";

        return response($ics)
            ->header('Content-Type', 'text/calendar')
            ->header('Content-Disposition', 'attachment; filename="events.ics"');
    }
}
