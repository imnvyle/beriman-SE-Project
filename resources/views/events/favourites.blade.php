<x-app-layout>
    <div class="py-6 px-4 max-w-7xl mx-auto">
        <h2 class="text-2xl font-bold mb-6">Your Favourites</h2>

        <div class="space-y-4">
            @forelse($favs as $event)
                <a href="{{ route('events.show', $event->id) }}" class="flex bg-white p-4 rounded-2xl shadow-sm items-center border">
                    <img src="{{ asset('storage/'.$event->poster) }}" class="w-24 h-24 rounded-xl object-cover">
                    <div class="ml-4">
                        <h4 class="font-bold text-lg">{{ $event->title }}</h4>
                        <p class="text-sm text-gray-500">{{ $event->event_date }} | {{ $event->venue }}</p>
                    </div>
                </a>
            @empty
                <div class="text-center py-20 text-gray-500 italic">
                    No events in favourite yet.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>