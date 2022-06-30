<div wire:poll.5s>
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul role="list" class="divide-y divide-gray-200">
            @foreach ($batches as $batch)
                <li>
                    <a href="{{ route('horizon-dashboard.batch', ['id' => $batch->id]) }}" class="block hover:bg-gray-50">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-indigo-600 truncate">{{ blank($batch->name) ? $batch->id : $batch->name }}</p>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <p
                                        @class([
                                            'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800',
                                            'bg-yellow-100 text-yellow-800' => $batch->cancelled,
                                            'bg-gray-100 text-gray-800' => $batch->pending_jobs > 0,
                                            'bg-red-100 text-red-800' => $batch->pending_jobs == 0 && $batch->failed_jobs > 0,
                                         ])
                                    >
                                        @if ($batch->cancelled)
                                            Cancelled
                                        @elseif ($batch->pending_jobs > 0)
                                            Processing ({{ round((1 - $batch->pending_jobs / $batch->total_jobs) * 100, 2) }}%)
                                        @elseif ($batch->pending_jobs == 0 && $batch->failed_jobs > 0)
                                            Failed
                                        @else
                                            Completed
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <div class="sm:flex">
                                    <p class="flex items-center text-sm text-gray-500">
                                        Total: {{ $batch->total_jobs ?? 0 }} &middot;
                                        Pending: {{ $batch->pending_jobs ?? 0 }} &middot;
                                        Failed: {{ $batch->failed_jobs ?? 0 }}
                                    </p>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                    Created At: {{ $batch->created_at->toDateTimeString() }}
                                    @if ($batch->finished_at !== null)
                                    &middot; Finished At: {{ $batch->finished_at->toDateTimeString() }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="mt-2">
        {{ $batches->links() }}
    </div>

</div>
