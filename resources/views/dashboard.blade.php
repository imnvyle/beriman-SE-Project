<x-app-layout>
    <div class="py-6 px-4 max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold">Hello, {{ Auth::user()->name }}</h1>

        <div class="mt-8">
            <div class="flex justify-between">
                <h2 class="font-bold text-lg">Your event this week</h2>
                <a href="{{ route('calendar.index') }}" class="text-xl">→</a>
            </div>
            <div class="mt-4 space-y-3">
                @forelse($favsThisWeek as $event)
                    <div class="flex bg-white p-3 rounded-xl shadow-sm items-center border">
                        <img src="{{ asset('storage/'.$event->poster) }}" class="w-20 h-20 rounded-lg object-cover">
                        <div class="ml-4">
                            <h4 class="font-bold">{{ $event->title }}</h4>
                            <p class="text-xs text-gray-500">{{ $event->event_date }} | {{ $event->venue }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 italic">No events in favourite</p>
                @endforelse
            </div>
        </div>

        <div class="mt-10">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-lg">Events around the corner</h2>
                <a href="{{ route('events.all') }}" class="text-blue-600 text-sm">View All →</a>
            </div>
            <div class="flex overflow-x-auto space-x-4 pb-4 scrollbar-hide">
                @foreach($upcoming as $event)
                    <a href="{{ route('events.show', $event->id) }}" class="min-w-[160px] bg-white rounded-xl shadow-sm p-2 border">
                        <img src="{{ asset('storage/'.$event->poster) }}" class="w-full h-32 rounded-lg object-cover">
                        <h4 class="font-bold text-sm mt-2 truncate">{{ $event->title }}</h4>
                        <p class="text-[10px] text-gray-400">{{ $event->event_date }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>