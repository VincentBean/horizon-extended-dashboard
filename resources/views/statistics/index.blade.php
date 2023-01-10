<x-horizondashboard::layout title="Statistics">

    <div class="mb-8 rounded-lg bg-gray-200 overflow-hidden shadow divide-y divide-gray-200 sm:divide-y-0 sm:grid sm:grid-cols-2 sm:gap-px">
        <div class="rounded-tl-lg rounded-tr-lg sm:rounded-tr-none relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-500">
            <div>
              <span class="rounded-lg inline-flex p-3 bg-teal-50 text-teal-600 ring-4 ring-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
              </span>
            </div>
            <div class="mt-8">
                <h3 class="text-lg font-medium">
                    <a href="{{ route('horizon-dashboard.statistics-jobs') }}" class="focus:outline-none">
                        <!-- Extend touch target to entire panel -->
                        <span class="absolute inset-0" aria-hidden="true"></span>
                        Job Statistic Overview
                    </a>
                </h3>
                <p class="mt-2 text-sm text-gray-500">List average runtime, attempts and fail ratio for all jobs</p>
            </div>
            <span class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-gray-400"
                  aria-hidden="true">
              <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z"/>
              </svg>
            </span>
        </div>

        <div class="sm:rounded-tr-lg relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-500">
            <div>
              <span class="rounded-lg inline-flex p-3 bg-purple-50 text-purple-700 ring-4 ring-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
              </span>
            </div>
            <div class="mt-8">
                <h3 class="text-lg font-medium">
                    <a href="{{ route('horizon-dashboard.statistics-queues') }}" class="focus:outline-none">
                        <!-- Extend touch target to entire panel -->
                        <span class="absolute inset-0" aria-hidden="true"></span>
                        Queue Statistic Overview
                    </a>
                </h3>
                <p class="mt-2 text-sm text-gray-500">List totals and averages about each queue</p>
            </div>
            <span class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-gray-400"
                  aria-hidden="true">
              <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z"/>
              </svg>
            </span>
        </div>


    </div>


    <div class="flex space-x-4">
        <div class="w-1/2">
            <h1 class="text-xl font-medium mb-2">Statistics per Queue</h1>
            <div class="flex flex-col space-y-3">
                @foreach ($queues as $queue)
                    <a href="{{ route('horizon-dashboard.statistics-queue', ['queue' => $queue]) }}"
                       title="{{ $queue }}"
                       class="bg-white shadow rounded-md px-6 py-4 hover:bg-gray-100 truncate">
                        {{ $queue }}
                    </a>
                @endforeach
            </div>
        </div>
        <div class="w-1/2">
            <h1 class="text-xl font-medium mb-2">Statistics per Job</h1>

            <ul role="list" class="space-y-3">
                <div class="flex flex-col space-y-3">
                    @foreach ($jobs as $job)
                        <a href="{{ route('horizon-dashboard.statistics-job', ['id' => $job->id]) }}"
                           title="{{ $job->class }}"
                           class="bg-white shadow rounded-md px-6 py-4 hover:bg-gray-100 truncate">
                            {{ $job->class }}
                        </a>
                    @endforeach
                </div>

            </ul>
        </div>

    </div>

</x-horizondashboard::layout>
