<x-app-layout>
    <div class="py-6 px-4 max-w-3xl mx-auto">
        <h2 class="text-2xl font-bold mb-6">Edit Event</h2>

        <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-semibold mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title', $event->title) }}"
                       class="w-full border rounded-xl px-3 py-2">
            </div>

            <div>
                <label class="block font-semibold mb-1">Date</label>
                <input type="date" name="event_date" value="{{ old('event_date', $event->event_date) }}"
                       class="w-full border rounded-xl px-3 py-2">
            </div>

            <div>
                <label class="block font-semibold mb-1">Venue</label>
                <input type="text" name="venue" value="{{ old('venue', $event->venue) }}"
                       class="w-full border rounded-xl px-3 py-2">
            </div>

            <div>
                <label class="block font-semibold mb-1">Description</label>
                <textarea name="description" rows="5"
                          class="w-full border rounded-xl px-3 py-2">{{ old('description', $event->description) }}</textarea>
            </div>

            <div>
                <label class="block font-semibold mb-1">Poster</label>
                <input type="file" name="poster" class="w-full border rounded-xl px-3 py-2">
                @if($event->poster)
                    <img src="{{ asset('storage/'.$event->poster) }}" class="mt-2 w-40 rounded-lg shadow">
                @endif
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('my-events') }}" class="px-4 py-2 rounded-xl bg-gray-200 hover:bg-gray-300">Cancel</a>
                <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 text-white font-bold">Update Event</button>
            </div>
        </form>
    </div>
</x-app-layout>
