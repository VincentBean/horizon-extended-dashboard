@props(['title'])
<html class="h-full bg-gray-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="{{ asset('vendor/extended-horizon-dashboard/css/app.css') }}">

    @livewireStyles

</head>

<body class="h-full">

<main>
    <livewire:horizon-dashboard.components.top-bar/>

    <div class="flex w-64 flex-col fixed inset-y-0">
        <div class="flex-1 flex flex-col min-h-0 bg-gray-800">
            <div class="flex items-center h-16 flex-shrink-0 px-4 bg-gray-900">
                <h1 class="text-xl text-white font-bold">{{ config('app.name') }} - Horizon</h1>
            </div>
            <div class="flex-1 flex flex-col overflow-y-auto">
                <nav class="flex-1 px-2 py-4 space-y-1">
                    <a href="{{ route('horizon-dashboard') }}"
                        @php($active = Route::currentRouteNamed('horizon-dashboard'))
                        @class([
                            'group flex items-center px-2 py-2 text-sm font-medium rounded-md',
                            'bg-gray-900 text-white' => $active,
                            'text-gray-300 hover:bg-gray-700 hover:text-white' => !$active,
                        ])
                    >
                        <svg
                            @class(['group-hover:text-blue-800 mr-3 flex-shrink-0 h-6 w-6', 'text-gray-300' => !$active, 'text-blue-800' => $active]) xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('horizon-dashboard.batches') }}"
                        @php($active = Route::currentRouteNamed('horizon-dashboard.batches'))
                        @class([
                            'group flex items-center px-2 py-2 text-sm font-medium rounded-md',
                            'bg-gray-900 text-white' => $active,
                            'text-gray-300 hover:bg-gray-700 hover:text-white' => !$active,
                        ])
                    >
                        <svg
                            @class(['group-hover:text-purple-800 mr-3 flex-shrink-0 h-6 w-6', 'text-gray-300' => !$active, 'text-purple-800' => $active])
                            xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Batches
                    </a>

                    <a href="{{ route('horizon-dashboard.job-list', ['type' => 'recent']) }}"
                        @php($active = Route::getCurrentRoute()->parameter('type') == 'recent')
                        @class([
                            'group flex items-center px-2 py-2 text-sm font-medium rounded-md',
                           'bg-gray-900 text-white' => $active,
                            'text-gray-300 hover:bg-gray-700 hover:text-white' => !$active,
                        ])
                    >
                        <svg
                            @class(['group-hover:text-indigo-800 mr-3 flex-shrink-0 h-6 w-6', 'text-gray-300' => !$active, 'text-indigo-800' => $active])
                            xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        Recent Jobs
                    </a>

                    <a href="{{ route('horizon-dashboard.job-list', ['type' => 'pending']) }}"
                        @php($active = Route::getCurrentRoute()->parameter('type') == 'pending')
                        @class([
                            'group flex items-center px-2 py-2 text-sm font-medium rounded-md',
                           'bg-gray-900 text-white' => $active,
                            'text-gray-300 hover:bg-gray-700 hover:text-white' => !$active,
                        ])
                    >
                        <svg
                            @class(['group-hover:text-yellow-600 mr-3 flex-shrink-0 h-6 w-6', 'text-gray-300' => !$active, 'text-yellow-600' => $active])
                            xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pending Jobs
                    </a>

                    <a href="{{ route('horizon-dashboard.job-list', ['type' => 'completed']) }}"
                        @php($active = Route::getCurrentRoute()->parameter('type') == 'completed')
                        @class([
                            'group flex items-center px-2 py-2 text-sm font-medium rounded-md',
                           'bg-gray-900 text-white' => $active,
                            'text-gray-300 hover:bg-gray-700 hover:text-white' => !$active,
                        ])
                    >
                        <svg
                            @class(['group-hover:text-green-600 mr-3 flex-shrink-0 h-6 w-6', 'text-gray-300' => !$active, 'text-green-600' => $active])
                            xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Completed Jobs
                    </a>

                    <a href="{{ route('horizon-dashboard.job-list', ['type' => 'failed']) }}"
                        @php($active = Route::getCurrentRoute()->parameter('type') == 'failed')
                        @class([
                            'group flex items-center px-2 py-2 text-sm font-medium rounded-md',
                           'bg-gray-900 text-white' => $active,
                            'text-gray-300 hover:bg-gray-700 hover:text-white' => !$active,
                        ])
                    >

                        <svg
                            @class(['group-hover:text-red-800 mr-3 flex-shrink-0 h-6 w-6', 'text-gray-300' => !$active, 'text-red-800' => $active])
                            xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Failed Jobs
                    </a>

                    <a href="{{ route('horizon-dashboard.exception-list') }}"
                        @php($active = Route::currentRouteNamed('horizon-dashboard.exception-list'))
                        @class([
                            'group flex items-center px-2 py-2 text-sm font-medium rounded-md',
                           'bg-gray-900 text-white' => $active,
                            'text-gray-300 hover:bg-gray-700 hover:text-white' => !$active,
                        ])
                    >
                        <svg @class(['group-hover:text-red-600 mr-3 flex-shrink-0 h-6 w-6', 'text-gray-300' => !$active, 'text-red-600' => $active])
                             fill="currentColor"
                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path
                                d="M435,73.6a76.48,76.48,0,0,0-54.85,23l-28.9-28.9a12.8,12.8,0,0,0-18.1,0l-39.2,39.2A203.57,203.57,0,0,0,204.8,86.4C91.87,86.4,0,178.27,0,291.2S91.87,496,204.8,496s204.8-91.87,204.8-204.8a203.56,203.56,0,0,0-20.55-89.35l39.2-39.2a12.8,12.8,0,0,0,0-18.1l-29.79-29.79A51,51,0,0,1,435.2,99.2a12.8,12.8,0,1,0,0-25.6ZM204.8,470.4C106,470.4,25.6,390,25.6,291.2S106,112,204.8,112,384,192.39,384,291.2,303.61,470.4,204.8,470.4ZM376,178.73a204.75,204.75,0,0,0-58.7-58.7L342.4,94.9l30,30,0,0L401.1,153.6Z"/>
                            <path d="M499,73.6H473.6a12.8,12.8,0,1,0,0,25.6h25.6a12.8,12.8,0,1,0,0-25.6Z"/>
                            <path
                                d="M460,60.8a12.75,12.75,0,0,0,9-3.75L489,37.85A12.8,12.8,0,0,0,471,19.75l-19.2,19.2a12.8,12.8,0,0,0,9,21.85Z"/>
                            <path d="M469,115.75a12.8,12.8,0,0,0-18.1,18.1l19.2,19.2a12.8,12.8,0,1,0,18.1-18.1Z"/>
                        </svg>
                        Jobs Exceptions
                    </a>

                    <a href="{{ route('horizon-dashboard.statistics') }}"
                        @php($active = Route::current()->getPrefix() === 'horizon-dashboard/statistics')
                        @class([
                            'group flex items-center px-2 py-2 text-sm font-medium rounded-md',
                           'bg-gray-900 text-white' => $active,
                            'text-gray-300 hover:bg-gray-700 hover:text-white' => !$active,
                        ])
                    >
                        <svg @class(['group-hover:text-teal-600 mr-3 flex-shrink-0 h-6 w-6', 'text-gray-300' => !$active, 'text-teal-600' => $active])
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Statistics
                    </a>

                    <a href="{{ route('horizon.index') }}" target="_blank"
                       class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <svg class="text-gray-400 group-hover:text-gray-300 mr-3 flex-shrink-0 h-6 w-6 border-0"
                             stroke="none" fill="#7746ec"
                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30">
                            <path
                                d="M5.26176342 26.4094389C2.04147988 23.6582233 0 19.5675182 0 15c0-4.1421356 1.67893219-7.89213562 4.39339828-10.60660172C7.10786438 1.67893219 10.8578644 0 15 0c8.2842712 0 15 6.71572875 15 15 0 8.2842712-6.7157288 15-15 15-3.716753 0-7.11777662-1.3517984-9.73823658-3.5905611zM4.03811305 15.9222506C5.70084247 14.4569342 6.87195416 12.5 10 12.5c5 0 5 5 10 5 3.1280454 0 4.2991572-1.9569336 5.961887-3.4222502C25.4934253 8.43417206 20.7645408 4 15 4 8.92486775 4 4 8.92486775 4 15c0 .3105915.01287248.6181765.03811305.9222506z"></path>
                        </svg>
                        Horizon
                    </a>

                </nav>
            </div>
        </div>
    </div>
    <div class="md:pl-64 flex flex-col">

        <div class="flex-1 px-4 flex justify-between">
            <main class="flex-1">
                <div class="py-6">
                    <div class="flex justify-between max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                        <h1 class="text-2xl font-semibold text-gray-900">{{ $title }}</h1>
                        <livewire:horizon-dashboard.components.controls/>
                    </div>
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mt-4">
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>

    </div>
</main>
@livewireScripts
@livewireChartsScripts
<script src="{{ asset('vendor/extended-horizon-dashboard/app.js') }}" defer></script>
</body>
</html>
