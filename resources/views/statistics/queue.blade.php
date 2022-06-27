<x-horizondashboard::layout title="Queue Statistics {{ $queue }}">
    <div class="space-y-4">

        <div class="bg-white overflow-hidden shadow rounded-lg divide-y divide-gray-200">
            <div class="px-4 py-5 sm:px-6">
                {{ $queue }}
            </div>
            <div>
                <dl>
                    <div class="bg-white odd:bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Average Jobs per minute</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $averageJobsPerMinute }}s</dd>
                    </div>
                </dl>
                <dl>
                    <div class="bg-white odd:bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Average Runtime</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $averageRuntime }}s</dd>
                    </div>
                </dl>
                <dl>
                    <div class="bg-white odd:bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Average Throughput</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $averageThroughput }}s</dd>
                    </div>
                </dl>
                <dl>
                    <div class="bg-white odd:bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Average Wait</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $averageWait }}s</dd>
                    </div>
                </dl>

            </div>
        </div>

        <livewire:horizon-dashboard.components.charts.queue-jobcounts-chart :queue="$queue"/>
        <livewire:horizon-dashboard.components.charts.queue-jobsperminute-chart :queue="$queue"/>
        <livewire:horizon-dashboard.components.charts.queue-jobtypes-chart :queue="$queue"/>
        <livewire:horizon-dashboard.components.charts.queue-cpu-chart :queue="$queue"/>
        <livewire:horizon-dashboard.components.charts.queue-memory-chart :queue="$queue"/>

    </div>
</x-horizondashboard::layout>
