<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Report;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    // PAGE 1: HOMEPAGE / DASHBOARD
    public function index()
{
    $user = auth()->user();

    // Favourite events this week
    $favsThisWeek = $user->wishlistedEvents()
        ->whereBetween('event_date', [now()->startOfWeek(), now()->endOfWeek()])
        ->get();

    // Upcoming events
    $upcoming = Event::orderBy('event_date', 'asc')->get();

    // Events happening today
    $eventsToday = Event::whereDate('event_date', now()->toDateString())->get();

    return view('dashboard', compact('favsThisWeek', 'upcoming', 'eventsToday'));
}


    // PAGE 2: FAVOURITE EVENTS
    public function favourites()
    {
        $favs = auth()->user()->wishlistedEvents()->orderBy('event_date', 'asc')->get();
        return view('events.favourites', compact('favs'));
    }

    // PAGE 3: CALENDAR
    public function calendar()
    {
        $favs = auth()->user()->wishlistedEvents()->orderBy('event_date', 'asc')->get();
        $nextEvent = $favs->where('event_date', '>=', now()->toDateString())->first();
        $daysToNext = $nextEvent ? now()->diffInDays(Carbon::parse($nextEvent->event_date)) : null;

        return view('events.calendar', compact('favs', 'nextEvent', 'daysToNext'));
    }

    // PAGE 5: CREATE NEW EVENT
    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        // Save poster image
        $path = $request->file('poster')->store('posters', 'public');

        // Save event
        Event::create([
            'poster' => $path,
            'title' => $request->title,
            'event_date' => $request->event_date,
            'venue' => $request->venue,
            'description' => $request->description,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Event Published!');
    }

    // PAGE 6: MANAGE YOUR EVENTS
    public function myEvents()
    {
        $myEvents = Event::where('user_id', auth()->id())->get();
        return view('events.my-events', compact('myEvents'));
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title' => 'required',
            'event_date' => 'required|date',
            'venue' => 'required',
            'description' => 'required',
        ]);

        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $event->update($data);

       return redirect()->route('my-events')->with('success', 'Event updated!');

    }

    public function destroy(Event $event)
    {
        if ($event->user_id == auth()->id()) {
            $event->delete();
        }
        return back();
    }

    // PAGE 7: REPORT EVENT
    public function report(Request $request, Event $event)
    {
        Report::create([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
            'reason' => implode(', ', $request->reason),
            'description' => $request->description ?? '',
        ]);

        return back()->with('success', 'Report submitted.');
    }

    // PAGE 8: EVENT DETAIL
    public function show(Event $event)
    {
        $isFav = auth()->user()
            ->wishlistedEvents()
            ->where('event_id', $event->id)
            ->exists();

        return view('events.show', [
            'event' => $event,
            'isFav' => $isFav,
        ]);
    }

    // TOGGLE FAVOURITE
    public function toggleFavourite(Event $event)
    {
        auth()->user()->wishlistedEvents()->toggle($event->id);
        return back();
    }

    // LIST ALL EVENTS
    public function all(Request $request)
    {
        $query = Event::query();

        // Filter by month
        if ($request->has('month') && $request->month != '') {
            $query->whereMonth('event_date', $request->month);
        }

        // Sort by date
        $sort = $request->get('sort', 'asc');
        $upcoming = $query->orderBy('event_date', $sort)->get();

        return view('events.all', compact('upcoming'));
    }
}
