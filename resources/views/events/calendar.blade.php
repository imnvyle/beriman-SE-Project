<x-app-layout>
    <div class="py-6 px-4 max-w-4xl mx-auto">

        <!-- Calendar header -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-black">{{ $monthName }}</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('calendar.index', ['year' => \Carbon\Carbon::create($year, $month)->subMonth()->year, 'month' => \Carbon\Carbon::create($year, $month)->subMonth()->month]) }}"
                   class="px-3 py-1 rounded-xl bg-gray-100 hover:bg-gray-200 text-sm">← Prev</a>
                <a href="{{ route('calendar.index', ['year' => \Carbon\Carbon::create($year, $month)->addMonth()->year, 'month' => \Carbon\Carbon::create($year, $month)->addMonth()->month]) }}"
                   class="px-3 py-1 rounded-xl bg-gray-100 hover:bg-gray-200 text-sm">Next →</a>
            </div>
        </div>

        <!-- Weekday header -->
        <div class="grid grid-cols-7 gap-2 text-center text-xs font-bold text-gray-500 mb-2">
            <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
        </div>

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
                        {{ $isBlank ? 'bg-gray-50 border-gray-100' : 'border-gray-200' }}
                        hover:bg-blue-50 hover:scale-[1.03] transition-transform duration-200 ease-out cursor-pointer">
                        
                        <div class="flex justify-between">
                            <span class="text-sm font-semibold text-gray-700">{{ $dayNum }}</span>
                            @if($hasFav)
                                <span class="text-xs text-blue-600">⭐</span>
                            @endif
                        </div>

                        @if(!$isBlank && count($evs))
                            <div class="mt-2 space-y-1">
                                @foreach($evs as $e)
                                    <span class="block text-[11px] px-2 py-1 rounded-lg bg-gray-100 text-gray-700 hover:bg-blue-200 hover:text-blue-900 transition">
                                        {{ Str::limit($e->title, 20) }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

<!-- Favourited Event section -->
@if(isset($nextFav))
    @php
        $eventDate = \Carbon\Carbon::parse($nextFav['date'])->format('Y-m-d H:i:s');
    @endphp

    <h2 class="mt-8 mb-2 text-xl font-bold text-gray-700">Favourited Event</h2>

    <div class="bg-black text-white p-6 rounded-2xl shadow-lg flex justify-between items-center w-full">
        
        <!-- Left side: event info -->
        <div class="flex flex-col">
            <h3 class="font-bold text-lg">{{ $nextFav['event']->title }}</h3>
            <a href="{{ route('events.show', $nextFav['event']->id) }}"
               class="text-xs text-gray-300 hover:text-white">View Details →</a>
        </div>
        
        <!-- Right side: live countdown -->
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
            <p class="text-sm text-gray-300">Countdown</p>
            <p class="text-2xl font-bold"
               x-text="days + 'd ' + hours + 'h ' + minutes + 'm ' + seconds + 's'"></p>
        </div>
    </div>
@endif


        <!-- Export button aligned right below -->
        <div class="flex justify-end mt-6">
            <a href="{{ route('calendar.export') }}"
               class="px-4 py-2 rounded-xl bg-green-600 text-white hover:bg-green-700">
               Export to Calendar
            </a>
        </div>
    </div>
</x-app-layout>
