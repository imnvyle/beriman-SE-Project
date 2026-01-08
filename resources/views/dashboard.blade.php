<x-app-layout>
    <div class="py-6 px-4 max-w-7xl mx-auto">

 

        <!-- This Week’s Favourite Events -->
        <div class="mt-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-lg">This Week’s Favourite Events</h2>
                <a href="{{ route('calendar.index') }}" class="text-blue-600 text-sm">View Calendar →</a>
            </div>

            <div class="flex flex-col space-y-3">
                @forelse($favsThisWeek as $event)
                    <a href="{{ route('events.show', $event->id) }}" 
                       class="flex bg-white p-3 rounded-xl shadow-sm items-center border hover:bg-blue-50 transition">
                        <img src="{{ asset('storage/'.$event->poster) }}" class="w-20 h-20 rounded-lg object-cover">
                        <div class="ml-4 flex-1">
                            <h4 class="font-bold text-sm md:text-base truncate">{{ $event->title }}</h4>
                            <p class="text-xs md:text-sm text-gray-500">{{ $event->event_date }} | {{ $event->venue }}</p>
                        </div>
                        <span class="text-blue-600 font-bold ml-2">→</span>
                    </a>
                @empty
                    <p class="text-gray-500 italic">No favourite events this week.</p>
                @endforelse
            </div>
        </div>

        <!-- Events Around the Corner -->
        <div class="mt-10">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-lg">Events Around the Corner</h2>
                <a href="{{ route('events.all') }}" class="text-blue-600 text-sm">View All →</a>
            </div>
            <div class="flex overflow-x-auto space-x-4 pb-4 scrollbar-hide">
                @foreach($upcoming as $event)
                    <a href="{{ route('events.show', $event->id) }}" class="min-w-[160px] bg-white rounded-xl shadow-sm p-2 border hover:shadow-md transition">
                        <img src="{{ asset('storage/'.$event->poster) }}" class="w-full h-32 rounded-lg object-cover">
                        <h4 class="font-bold text-sm mt-2 truncate">{{ $event->title }}</h4>
                        <p class="text-[10px] text-gray-400">{{ $event->event_date }}</p>
                    </a>
                @endforeach
            </div>
        </div>

    </div>
</x-app-layout>
