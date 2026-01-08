<x-app-layout>
    <div class="py-12 px-6 max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <h2 class="text-2xl font-black text-gray-800 italic">All Events Around the Corner</h2>
            
            <form action="{{ route('events.all') }}" method="GET" class="flex flex-wrap gap-3">
                <select name="month" class="rounded-xl border-gray-300 text-sm focus:ring-blue-500">
                    <option value="">All Months</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endforeach
                </select>

                <select name="sort" class="rounded-xl border-gray-300 text-sm focus:ring-blue-500">
                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Soonest First</option>
                    <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Latest First</option>
                </select>

                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-xl text-sm font-bold hover:bg-blue-700 transition">
                    Apply
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($upcoming as $event)
                <a href="{{ route('events.show', $event->id) }}" class="bg-white border rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition group">
                    <div class="relative">
                        <img src="{{ asset('storage/'.$event->poster) }}" class="w-full h-48 object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute top-2 right-2 bg-white/90 px-2 py-1 rounded-lg text-[10px] font-bold shadow-sm">
                            {{ \Carbon\Carbon::parse($event->event_date)->format('M d') }}
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-gray-800 truncate">{{ $event->title }}</h3>
                        <p class="text-xs text-gray-500 mt-1">📍 {{ $event->venue }}</p>
                        <div class="mt-4 text-blue-600 text-xs font-bold">View Details →</div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-20 text-gray-500 italic">
                    No events found for this filter.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>