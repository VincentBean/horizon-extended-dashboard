<x-horizondashboard::layout title="Statistics">

    <div class="flex space-x-4">
        <div class="w-1/2">
            <h1 class="text-xl font-medium mb-2">Queues</h1>
            <div class="flex flex-col space-y-3">
                @foreach ($queues as $queue)
                    <a href="{{ route('horizon-dashboard.statistics-queue', ['queue' => $queue]) }}" class="bg-white shadow rounded-md px-6 py-4 hover:bg-gray-100">
                        {{ $queue }}
                    </a>
                @endforeach
            </div>
        </div>
        <div class="w-1/2">
            <h1 class="text-xl font-medium mb-2">Jobs</h1>

            <ul role="list" class="space-y-3">
                <div class="flex flex-col space-y-3">
                    @foreach ($jobs as $job)
                        <a href="{{ route('horizon-dashboard.statistics-job', ['id' => $job->id]) }}" class="bg-white shadow rounded-md px-6 py-4 hover:bg-gray-100">
                            {{ $job->class }}
                        </a>
                    @endforeach
                </div>

            </ul>
        </div>

    </div>

</x-horizondashboard::layout>
