<div class="pl-64 flex h-16 bg-white shadow" wire:poll>
        <div class="flex-1 space-x-4 ml-4 pt-4 text-gray-800 font-medium">

            @if ($data['status'] == 'running')
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Running</span>
            @elseif ($data['status'] == 'inactive')
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Stopped</span>
            @else
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Paused</span>
            @endif

            <span>Processes: {{ $data['processes'] }}</span>
            <span>Jobs/min: {{ $data['jobsPerMinute'] }}</span>
            <span>Recent: {{ $data['recentJobs'] }}</span>
            <span>Recent (7d): {{ $data['periods']['recentJobs'] }}</span>
            <span>Failed (7d): {{ $data['periods']['failedJobs'] }}</span>
            <span>Max runtime: {{ $data['queueWithMaxRuntime'] ?? '-' }}</span>
            <span>Max troughput: {{ $data['queueWithMaxThroughput'] ?? '-' }}</span>
            @foreach ($data['wait'] as $queue => $wait)
                <span>Wait {{ $queue }}: {{ $wait }}</span>
            @endforeach
        </div>
</div>
