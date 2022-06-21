<x-horizondashboard::layout title="Job Statistics">
    <div class="space-y-4">
        <div class="bg-white overflow-hidden shadow rounded-lg divide-y divide-gray-200">
            <div class="px-4 py-5 sm:px-6">
                {{ $job->class }}
            </div>
            <div>
                <dl>
                    <div class="bg-white odd:bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Average Runtime</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $job->averageRuntime() }}s</dd>
                    </div>
                    <div class="bg-white odd:bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Average Attempts</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $job->averageAttempts() }}</dd>
                    </div>
                    <div class="bg-white odd:bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Runs on queues:</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $job->statistics()->select(['queue'])->distinct()->get()->pluck('queue')->implode(', ') }}
                            s
                        </dd>
                    </div>
                    <div class="bg-white odd:bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Fail Ratio</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $job->failRatio() * 100 }}%
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <livewire:horizon-dashboard.components.charts.job-runtime-chart :jobId="$job->id"/>
    </div>

</x-horizondashboard::layout>
