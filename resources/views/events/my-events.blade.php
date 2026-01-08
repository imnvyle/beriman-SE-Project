<x-app-layout>
    <div class="py-6 px-4 max-w-7xl mx-auto">
        <h2 class="text-2xl font-bold mb-6">Manage Your Events</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($myEvents as $event)
                <div class="bg-white border rounded-2xl shadow-sm overflow-hidden p-4">
                    <img src="{{ asset('storage/'.$event->poster) }}" class="w-full h-40 object-cover rounded-xl mb-4">
                    <h3 class="font-bold text-lg">{{ $event->title }}</h3>
                    <p class="text-xs text-gray-500 mb-4">{{ $event->event_date }}</p>
                    
                    <div class="flex space-x-2" x-data="{ confirmDelete: false }">
                        <!-- Edit button -->
                        <a href="{{ route('events.edit', $event->id) }}" 
                           class="flex-1 bg-blue-100 text-blue-700 text-center py-2 rounded-lg font-bold">
                            Edit
                        </a>

                        <!-- Delete trigger -->
                        <button @click="confirmDelete = true" 
                                class="flex-1 bg-red-100 text-red-700 py-2 rounded-lg font-bold">
                            Delete
                        </button>

                        <!-- Confirm modal -->
                        <div x-show="confirmDelete" x-cloak
                             class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
                            <div class="bg-white p-6 rounded-2xl shadow-lg w-full max-w-sm">
                                <h3 class="text-lg font-bold mb-4">Are you sure?</h3>
                                <p class="text-gray-600 mb-6">This action cannot be undone.</p>

                                <div class="flex justify-end space-x-3">
                                    <button @click="confirmDelete = false"
                                            class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">
                                        Cancel
                                    </button>
                                    <form action="{{ route('events.destroy', $event->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-4 py-2 rounded-lg bg-red-600 text-white font-bold">
                                            Yes, Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">You haven't posted any events yet.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
