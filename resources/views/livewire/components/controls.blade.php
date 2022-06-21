<div class="flex space-x-2" @if ($poll) wire:poll.250ms @endif>
    @if ($status == 'running')

        <svg wire:click="pause" xmlns="http://www.w3.org/2000/svg"
             class="h-8 w-8 text-yellow-400 hover:text-yellow-500 cursor-pointer" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>

    @endif

    @if ($status == 'paused')

        <svg wire:click="continue" xmlns="http://www.w3.org/2000/svg"
             class="h-8 w-8 text-green-400 hover:text-green-500 cursor-pointer" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    @endif

    @if (blank($supervisor) && $status != 'inactive')

        <svg wire:click="terminate" xmlns="http://www.w3.org/2000/svg"
             class="h-8 w-8 text-red-400 hover:text-red-500 cursor-pointer" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
        </svg>
    @endif

    @if ($status == 'inactive')
            <span wire:poll.10s
                class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium text-red-600">Stopped</span>
    @endif
</div>
