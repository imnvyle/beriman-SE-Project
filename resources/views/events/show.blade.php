<x-app-layout>
    <div class="py-6 px-4 max-w-5xl mx-auto"
         x-data="{ openMenu: false, openReport: false, showNotif: false, notifMessage: '' }"
         x-init="
             @if(session('success'))
                 notifMessage = '{{ session('success') }}';
                 showNotif = true;
                 setTimeout(() => showNotif = false, 3000);
             @endif
         ">

        <!-- Notification bar -->
        <div x-show="showNotif" x-cloak
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="fixed top-4 left-1/2 transform -translate-x-1/2 w-1/2 bg-green-400/70 px-6 py-4 rounded-lg shadow z-50 text-left">
            <div class="font-bold text-white text-base" x-text="notifMessage"></div>
        </div>

        <!-- Top bar with Back + Menu -->
        <div class="flex justify-between items-center mb-6">
            <!-- Back to dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-2 px-3 py-2 rounded-full bg-gray-100 hover:bg-gray-200 text-sm font-semibold">
                ← Back to Dashboard
            </a>

            <!-- Menu button + dropdown -->
            <div class="relative" x-data="{ openMenu: false }">
                <button @click="openMenu = !openMenu" class="text-gray-600 hover:text-black p-2 rounded-full">
                    ⋮
                </button>
                <div x-show="openMenu" @click.away="openMenu = false"
                     class="absolute right-0 mt-2 w-40 bg-white border rounded-xl shadow-xl p-2">
                    <form action="{{ route('events.toggle', $event->id) }}" method="POST" x-data
                          @submit.prevent="
                              $el.submit();
                              notifMessage = '{{ $isFav ? 'Removed from favourites' : 'Added to favourites' }}';
                              showNotif = true;
                              setTimeout(() => showNotif = false, 3000);
                          ">
                        @csrf
                        <button class="w-full text-left px-4 py-2 hover:bg-gray-100 rounded-lg flex items-center gap-2">
                            @if($isFav)
                                <span class="text-yellow-500">★</span> <span>Unfavourite</span>
                            @else
                                <span class="text-gray-400">☆</span> <span>Favourite</span>
                            @endif
                        </button>
                    </form>

                    <button @click="openReport = true; openMenu = false"
                            class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg">
                        🚩 Report
                    </button>
                </div>
            </div>
        </div>

        <!-- Event card -->
        <div class="bg-white rounded-3xl shadow-lg overflow-hidden border">
            <!-- Poster -->
            <img src="{{ $event->poster ? asset('storage/'.$event->poster) : asset('storage/default.jpg') }}" 
                 class="w-full h-auto object-cover" alt="Event Poster">

            <!-- Event details -->
            <div class="p-6">
                <h2 class="text-2xl font-black">{{ $event->title }}</h2>
                <div class="flex gap-4 text-sm text-gray-500 mt-2">
                    <span>📅 {{ $event->event_date }}</span>
                    <span>📍 {{ $event->venue }}</span>
                </div>
                <div class="mt-6 text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ $event->description }}
                </div>
            </div>
        </div>

        <!-- Report Modal -->
        <div x-show="openReport" x-cloak
             class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
            <div class="bg-white p-6 rounded-3xl shadow-lg w-full max-w-md">
                <h3 class="font-bold text-lg mb-4">Report this event</h3>
                <form action="{{ route('events.report', $event->id) }}" method="POST" @submit="openReport = false"> 
                    @csrf
                    <div class="space-y-3">
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="reason[]" value="Inappropriate" class="rounded text-blue-600">
                            <span>Inappropriate Content</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="reason[]" value="Spam" class="rounded text-blue-600">
                            <span>Spam / Scam</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="reason[]" value="Incorrect" class="rounded text-blue-600">
                            <span>Incorrect Information</span>
                        </label>
                    </div>

                    <textarea name="description" placeholder="Optional: Tell us more..."
                              class="w-full mt-4 rounded-xl border-gray-300 h-24"></textarea>

                    <div class="flex justify-between mt-4">
                        <button type="button" @click="openReport = false"
                                class="px-4 py-2 rounded-xl bg-gray-200 hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 rounded-xl bg-red-600 text-white font-bold">
                            Submit Report
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
