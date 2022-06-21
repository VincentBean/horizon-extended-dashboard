<div>

    <div class="flex space-x-4 mb-4">
        @foreach($this->getQueues() as $q)
            <a href="{{ route('horizon-dashboard.job-list', ['type' => $type, 'queue' => $queue == $q ? '' : $q]) }}"
                @class([
                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                    'bg-indigo-600 text-white' => $queue == $q,
                    'bg-gray-300 text-gray-800' => $queue != $q,
                ])>
                {{ $q }}
            </a>
        @endforeach
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <x-horizondashboard::job-list :jobs="$jobs"/>
        @if(count($jobs) && $hasMore)
            <button wire:click="loadMore"
                    class="w-full px-4 py-4 sm:px-6 text-center text-indigo-700 hover:bg-gray-50">
                Load more
            </button>
        @endif
    </div>

    @if(count($jobs) == 0)
        No Jobs
    @endif

</div>
