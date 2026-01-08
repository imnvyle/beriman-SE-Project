<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Eventify</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100"
      x-data="{
          sidebarOpen: JSON.parse(localStorage.getItem('sidebarOpen') || 'false'),
          toggleSidebar() {
              this.sidebarOpen = !this.sidebarOpen;
              localStorage.setItem('sidebarOpen', JSON.stringify(this.sidebarOpen));
          }
      }">

    <div class="flex h-screen">

        <!-- Sidebar -->
        <aside class="bg-white shadow-lg border-r transition-[width] duration-300 ease-out"
               :class="sidebarOpen ? 'w-64' : 'w-0'">
            <div class="p-6 flex flex-col h-full" x-show="sidebarOpen" x-cloak>
                <!-- Logo -->
                <div class="mb-8 flex justify-center">
                    <span class="text-2xl font-extrabold text-blue-600">Eventify</span>
                </div>

                <!-- Navigation -->
                <nav class="space-y-2 flex-grow">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mb-2">Main Menu</p>

                    <a href="{{ route('dashboard') }}"
                       class="flex items-center p-3 rounded-xl font-bold {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
                        <span class="mr-3">🏠</span> Home
                    </a>

                    <a href="{{ route('events.all') }}"
                       class="flex items-center p-3 rounded-xl font-bold {{ request()->routeIs('events.all') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
                        <span class="mr-3">🌐</span> All Events
                    </a>

                    <a href="{{ route('events.favourites') }}"
                       class="flex items-center p-3 rounded-xl font-bold {{ request()->routeIs('events.favourites') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
                        <span class="mr-3">⭐</span> Favourites
                    </a>

                    <a href="{{ route('calendar.index') }}"
                       class="flex items-center p-3 rounded-xl font-bold {{ request()->routeIs('calendar.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
                        <span class="mr-3">📅</span> Calendar
                    </a>

                    <hr class="my-6 border-gray-100">

                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mb-2">Promoter Tools</p>

                    <a href="{{ route('my-events') }}"
                       class="flex items-center p-3 rounded-xl font-bold {{ request()->routeIs('my-events') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
                        <span class="mr-3">🛠️</span> Your Events
                    </a>
                </nav>

                <!-- CTA -->
                <div class="mt-auto pt-6">
                    <a href="{{ route('events.create') }}"
                       class="flex items-center justify-center p-4 rounded-2xl font-black bg-blue-600 text-white shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-1 transition-all duration-200">
                        <span class="mr-2 text-xl">+</span> Post New Event
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col min-w-0 bg-gray-100">

            <!-- Top bar -->
            <div class="flex justify-between items-center p-4 bg-white border-b shadow-sm">

                <!-- Left side: Hamburger + Brand -->
                <div class="flex items-center gap-3">
                    <!-- Hamburger -->
                    <button @click="toggleSidebar()" 
                            class="p-2 rounded-lg hover:bg-gray-100"
                            aria-label="Toggle sidebar">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Plain text brand -->
                    <div class="flex flex-col">
                        <span class="font-extrabold text-lg text-blue-600 tracking-wide">
                            Eventify
                        </span>
                        <span class="text-xs text-gray-400 italic">
                            Discover • Share • Celebrate
                        </span>
                    </div>
                </div>

                <!-- Right side: Bell + Profile -->
                <div class="flex items-center space-x-4">
                    <!-- Bell -->
                    <div class="relative" x-data="{ showBellPopup: false }">
                        <button @click="showBellPopup = !showBellPopup" class="relative">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14V11c0-3.866-3.134-7-7-7S4 7.134 4 11v3c0 .386-.149.735-.395 1.005L2 17h5m5 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if(isset($eventsToday) && $eventsToday->count())
                                <span class="absolute -top-1 -right-1 text-xs bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center">
                                    {{ $eventsToday->count() }}
                                </span>
                            @endif
                        </button>

                        <!-- Bell popup -->
                        <div x-show="showBellPopup" @click.away="showBellPopup = false" x-cloak
                             class="absolute right-0 mt-2 w-64 bg-white border rounded-xl shadow-lg p-3 z-50">
                            <h4 class="font-bold text-sm mb-2">Events happening today</h4>
                            @forelse($eventsToday ?? [] as $event)
                                <a href="{{ route('events.show', $event->id) }}"
                                   class="block text-sm px-2 py-1 rounded-lg hover:bg-blue-50">
                                    {{ $event->title }} – {{ $event->venue }}
                                </a>
                            @empty
                                <p class="text-xs text-gray-500 italic">No events today.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Profile dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5.23 7.21a.75.75 0 011.06 0L10 10.92l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.23 8.27a.75.75 0 010-1.06z"/>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute right-0 mt-2 w-36 bg-white border rounded-xl shadow-lg p-2 z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 hover:bg-gray-100 rounded-lg">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-3 py-2 hover:bg-gray-100 rounded-lg">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main slot -->
            <main class="p-8 pb-20">
                {{ $slot }}
            </main>
        </div>
    </div>

</body>
</html>
