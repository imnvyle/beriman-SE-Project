<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 flex">

        <aside class="w-64 min-h-screen bg-white shadow-lg border-r flex-shrink-0 sticky top-0">
    <div class="p-6 flex flex-col h-full">
        <div class="mb-8 flex justify-center">
            <a href="{{ route('dashboard') }}">
                <x-application-logo class="w-16 h-16 fill-current text-blue-600" />
            </a>
        </div>

        <nav class="space-y-2 flex-grow">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mb-2">Main Menu</p>
            
            <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-xl font-bold {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
                <span class="mr-3">🏠</span> Home
            </a>
            
            <a href="{{ route('events.all') }}" class="flex items-center p-3 rounded-xl font-bold {{ request()->routeIs('events.all') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
                <span class="mr-3">🌐</span> All Events
            </a>
            
            <a href="{{ route('events.favourites') }}" class="flex items-center p-3 rounded-xl font-bold {{ request()->routeIs('events.favourites') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
                <span class="mr-3">⭐</span> Favourites
            </a>
            
            <a href="{{ route('calendar.index') }}" class="flex items-center p-3 rounded-xl font-bold {{ request()->routeIs('events.calendar') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
                <span class="mr-3">📅</span> Calendar
            </a>
            
            <hr class="my-6 border-gray-100">
            
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mb-2">Promoter Tools</p>
            
            <a href="{{ route('events.myEvents') }}" class="flex items-center p-3 rounded-xl font-bold {{ request()->routeIs('events.myEvents') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
                <span class="mr-3">🛠️</span> Your Events
            </a>
        </nav>

        <div class="mt-auto pt-6">
            <a href="{{ route('events.create') }}" class="flex items-center justify-center p-4 rounded-2xl font-black bg-blue-600 text-white shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-1 transition-all duration-200">
                <span class="mr-2 text-xl">+</span> Post New Event
            </a>
        </div>
    </div>
</aside>

        <div class="flex-1 h-screen overflow-y-auto flex flex-col min-w-0 bg-gray-100">
            @include('layouts.navigation')

            <main class="p-8 pb-20">
                {{ $slot }}
            </main>
        </div>

    </body>
</html>