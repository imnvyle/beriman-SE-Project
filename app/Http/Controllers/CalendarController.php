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
        $year   = (int)($request->input('year', now()->year));
        $month  = (int)($request->input('month', now()->month));
        $filter = $request->input('filter', 'all');

        $firstDay    = Carbon::create($year, $month, 1);
        $endOfMonth  = $firstDay->copy()->endOfMonth();
        $today       = Carbon::today()->toDateString();

        // Favourite IDs
        $favIds = DB::table('event_user')
            ->where('user_id', auth()->id())
            ->pluck('event_id');

        // Events for calendar grid (month only)
        $eventsQuery = Event::whereBetween('event_date', [$firstDay, $endOfMonth])
            ->orderBy('event_date');

        if ($filter === 'fav') {
            $eventsQuery->whereIn('id', $favIds);
        }

        $events = $eventsQuery->get();
        $eventsByDate = $events->groupBy(fn($e) => Carbon::parse($e->event_date)->toDateString());

        // All upcoming favourites (from today onwards, not limited to month)
        $allUpcomingFavs = Event::whereIn('id', $favIds)
            ->whereDate('event_date', '>=', $today)
            ->orderBy('event_date')
            ->get();

        return view('events.calendar', [
            'year'            => $year,
            'month'           => $month,
            'dates'           => $this->buildDates($year, $month),
            'today'           => $today,
            'eventsByDate'    => $eventsByDate,
            'favIds'          => $favIds,
            'allUpcomingFavs' => $allUpcomingFavs,
            'monthName'       => $firstDay->format('F Y'),
            'filter'          => $filter,
        ]);
    }

    private function buildDates($year, $month)
    {
        $firstDay    = Carbon::create($year, $month, 1);
        $daysInMonth = $firstDay->daysInMonth;
        $startDay    = $firstDay->dayOfWeek;

        $dates = [];
        for ($i = 0; $i < $startDay; $i++) $dates[] = null;
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dates[] = Carbon::create($year, $month, $d)->toDateString();
        }
        return $dates;
    }

    public function export()
    {
        $events = Event::orderBy('event_date')->get();

        $ics  = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nCALSCALE:GREGORIAN\r\n";
        foreach ($events as $event) {
            $date = Carbon::parse($event->event_date)->format('Ymd');

            $ics .= "BEGIN:VEVENT\r\n";
            $ics .= "SUMMARY:{$event->title}\r\n";
            $ics .= "DTSTART:{$date}\r\n";
            $ics .= "END:VEVENT\r\n";
        }
        $ics .= "END:VCALENDAR";

        return response($ics)
            ->header('Content-Type', 'text/calendar')
            ->header('Content-Disposition', 'attachment; filename="events.ics"');
    }
}
