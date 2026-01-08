<x-app-layout>
<div class="py-6 px-4 max-w-5xl mx-auto">

    <!-- Calendar header -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-black">{{ $monthName }}</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('calendar.index', [
                'year' => \Carbon\Carbon::create($year, $month)->subMonth()->year,
                'month' => \Carbon\Carbon::create($year, $month)->subMonth()->month,
                'filter' => $filter
            ]) }}"
               class="px-3 py-1 rounded-xl bg-gray-100 hover:bg-gray-200 text-sm">← Prev</a>
            <a href="{{ route('calendar.index', [
                'year' => \Carbon\Carbon::create($year, $month)->addMonth()->year,
                'month' => \Carbon\Carbon::create($year, $month)->addMonth()->month,
                'filter' => $filter
            ]) }}"
               class="px-3 py-1 rounded-xl bg-gray-100 hover:bg-gray-200 text-sm">Next →</a>
        </div>
    </div>

    <!-- Filter -->
    <form method="GET" action="{{ route('calendar.index') }}" class="mb-4 flex gap-6 items-center">
        <input type="hidden" name="year" value="{{ $year }}">
        <input type="hidden" name="month" value="{{ $month }}">
        <label class="flex items-center gap-2">
            <input type="radio" name="filter" value="all" {{ $filter === 'all' ? 'checked' : '' }} onchange="this.form.submit()">
            <span>Show All Events</span>
        </label>
        <label class="flex items-center gap-2">
            <input type="radio" name="filter" value="fav" {{ $filter === 'fav' ? 'checked' : '' }} onchange="this.form.submit()">
            <span>Only Favourite Events</span>
        </label>
    </form>

    <!-- Calendar grid -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border">
        <div class="grid grid-cols-7 gap-2">
            @foreach($dates as $date)
                @php
                    $isBlank = is_null($date);
                    $dayNum  = $isBlank ? '' : \Carbon\Carbon::parse($date)->day;
                    $evs     = $isBlank ? [] : ($eventsByDate[$date] ?? []);
                    $hasFav  = !$isBlank && collect($evs)->contains(fn($e) => $favIds->contains($e->id));
                @endphp

                <div class="h-24 rounded-xl border flex flex-col p-2 relative
                    {{ $isBlank ? 'bg-gray-50 border-gray-100' : ($date === $today ? 'bg-blue-50 border-blue-300' : 'border-gray-200') }}">
                    <div class="flex justify-between">
                        <span class="text-sm font-semibold text-gray-700">{{ $dayNum }}</span>
                        @if($hasFav)<span class="text-xs text-blue-600">⭐</span>@endif
                    </div>

                    @if(!$isBlank && count($evs))
                        <div class="mt-2 space-y-1 overflow-y-auto max-h-16">
                            @foreach($evs as $e)
                                <a href="{{ route('events.show', $e->id) }}"
                                   class="block text-[11px] px-2 py-1 rounded-lg 
                                   {{ $favIds->contains($e->id) ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 'bg-gray-100 text-gray-700 hover:bg-blue-200 hover:text-blue-900' }} transition">
                                    {{ \Illuminate\Support\Str::limit($e->title, 20) }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Today Favourite Events -->
    @php
        $todayFavs = $allUpcomingFavs->filter(fn($e) => \Carbon\Carbon::parse($e->event_date)->toDateString() === $today);
    @endphp

    @if($todayFavs->count())
        <h2 class="mt-8 mb-4 text-xl font-bold text-gray-700">Today's Favourite Events</h2>
        <div class="space-y-4">
            @foreach($todayFavs as $event)
                <div class="bg-black text-white p-6 rounded-2xl shadow-lg flex justify-between items-center w-full">
                    <div class="flex flex-col">
                        <h3 class="font-bold text-lg">{{ $event->title }}</h3>
                        <a href="{{ route('events.show', $event->id) }}" class="text-xs text-gray-300 hover:text-white">View Details →</a>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-300">Countdown</p>
                        <p class="text-2xl font-bold">D‑DAY!</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- All Upcoming Favourite Events (exclude today) -->
    @php
        $futureFavs = $allUpcomingFavs->filter(fn($e) => \Carbon\Carbon::parse($e->event_date)->toDateString() !== $today);
    @endphp

    @if($futureFavs->count())
        <h2 class="mt-8 mb-4 text-xl font-bold text-gray-700">All Upcoming Favourite Events</h2>
        <div class="space-y-4">
            @foreach($futureFavs as $event)
                @php $eventDate = \Carbon\Carbon::parse($event->event_date)->format('Y-m-d H:i:s'); @endphp
                <div class="bg-white border p-4 rounded-xl shadow-sm flex justify-between items-center w-full">
                    <div class="flex flex-col">
                        <h3 class="font-bold text-lg text-gray-800">{{ $event->title }}</h3>
                        <a href="{{ route('events.show', $event->id) }}" class="text-xs text-blue-600 hover:text-blue-800">View Details →</a>
                    </div>
                    <div class="text-center"
                         x-data="{
                             target: new Date('{{ $eventDate }}').getTime(),
                             now: new Date().getTime(),
                             diff: 0,
                             days: 0, hours: 0, minutes: 0, seconds: 0,
                             tick() {
                                 this.now = new Date().getTime();
                                 this.diff = this.target - this.now;
                                 if (this.diff > 0) {
                                     this.days = Math.floor(this.diff / (1000*60*60*24));
                                     this.hours = Math.floor((this.diff % (1000*60*60*24)) / (1000*60*60));
                                     this.minutes = Math.floor((this.diff % (1000*60*60)) / (1000*60));
                                     this.seconds = Math.floor((this.diff % (1000*60)) / 1000);
                                 }
                             }
                         }"
                         x-init="setInterval(() => tick(), 1000)">
                        <p class="text-sm text-gray-500">Countdown</p>
                        <p class="text-lg font-bold text-gray-800" x-text="days + 'd ' + hours + 'h ' + minutes + 'm ' + seconds + 's'"></p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Export button -->
    <div class="flex justify-end mt-6">
        <a href="{{ route('calendar.export') }}" class="px-4 py-2 rounded-xl bg-green-600 text-white hover:bg-green-700">
            Export to Calendar
        </a>
    </div>

</div>
</x-app-layout>
