<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Report;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller {
    
    // PAGE 1: HOMEPAGE
    public function index() {
        $user = auth()->user();
        $favsThisWeek = $user->wishlistedEvents()->whereBetween('event_date', [now()->startOfWeek(), now()->endOfWeek()])->get();
        $upcoming = Event::orderBy('event_date', 'asc')->get();
        return view('dashboard', compact('favsThisWeek', 'upcoming'));
    }

    // PAGE 2: FAVOURITE
    public function favourites() {
        $favs = auth()->user()->wishlistedEvents()->orderBy('event_date', 'asc')->get();
        return view('events.favourites', compact('favs'));
    }

    // PAGE 3: CALENDAR
    public function calendar() {
        $favs = auth()->user()->wishlistedEvents()->orderBy('event_date', 'asc')->get();
        $nextEvent = $favs->where('event_date', '>=', now()->toDateString())->first();
        $daysToNext = $nextEvent ? now()->diffInDays(Carbon::parse($nextEvent->event_date)) : null;
        return view('events.calendar', compact('favs', 'nextEvent', 'daysToNext'));
    }

    // PAGE 5: POST NEW EVENT
    public function create() { return view('events.create'); }

public function store(Request $request) {
    // 1. Save the image to storage
    $path = $request->file('poster')->store('posters', 'public');

    // 2. Save the details to the database
    Event::create([
        'poster' => $path,
        'title' => $request->title,
        'event_date' => $request->event_date,
        'venue' => $request->venue,
        'description' => $request->description,
        'user_id' => auth()->id() // Link to the logged-in user
    ]);

    // 3. Go back to the homepage
    return redirect()->route('dashboard')->with('success', 'Event Published!');
}

    // PAGE 6: EDIT POSTED EVENT (YOUR EVENTS)
    public function myEvents() {
        $myEvents = Event::where('user_id', auth()->id())->get();
        return view('events.my-events', compact('myEvents'));
    }

    public function edit(Event $event) { return view('events.edit', compact('event')); }

   public function update(Request $request, Event $event) {
    $data = $request->validate([
        'title' => 'required',
        'event_date' => 'required|date',
        'venue' => 'required',
        'description' => 'required',
    ]);

    if($request->hasFile('poster')) {
        $data['poster'] = $request->file('poster')->store('posters', 'public');
    }

    $event->update($data);
    return redirect()->route('events.myEvents')->with('success', 'Event updated!');
}

public function destroy(Event $event) {
    if($event->user_id == auth()->id()) {
        $event->delete();
    }
    return back();
}

    // PAGE 7: REPORT
    public function report(Request $request, Event $event) {
        Report::create(['event_id' => $event->id, 'user_id' => auth()->id(), 'reason' => implode(', ', $request->reason), 'description' => $request->description ?? '']);
        return back()->with('success', 'Report submitted.');
    }

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


    
    public function toggleFavourite(Event $event) {
        auth()->user()->wishlistedEvents()->toggle($event->id);
        return back();
    }

    // Add this inside the EventController class
public function all(Request $request) {
    $query = Event::query();

    // Filter by Month (Your Vision)
    if ($request->has('month') && $request->month != '') {
        $query->whereMonth('event_date', $request->month);
    }

    // Sort by Date (Your Vision - Ascending or Descending)
    $sort = $request->get('sort', 'asc');
    $upcoming = $query->orderBy('event_date', $sort)->get();

    return view('events.all', compact('upcoming'));
}

    
}