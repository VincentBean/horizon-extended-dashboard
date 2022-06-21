@props(['jobs'])

<ul role="list" class="divide-y divide-gray-200">
    @foreach ($jobs as $job)
        <li>
            <a href="{{ route('horizon-dashboard.job', ['id' => $job['id']]) }}" class="block hover:bg-gray-50">
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-indigo-600 truncate">
                            {{ $job['name'] }}
                        </p>
                        <div class="ml-2 flex-shrink-0 flex space-x-2">
                            @if ($job['viewData']['delayed'])
                                <p class="px-2 inline-flex text-xs leading-5 font-bold rounded-full bg-yellow-100 text-yellow-800">DELAYED</p>
                            @endif
                            <p class="px-2 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800">{{ $job['status'] }}</p>
                        </div>
                    </div>
                    <div class="mt-2 sm:flex sm:justify-between">
                        <div class="sm:flex">
                            <p class="flex items-center text-sm text-gray-500">
                                @php ($description = collect([$job['queue'], $job['viewData']['tries'], $job['viewData']['tags']])->reject(fn($item) => blank($item)))
                                {{ implode(' · ', $description->toArray()) }}
                            </p>
                        </div>
                        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                            Pushed: {{ $job['viewData']['pushed_at'] }}
                            @if ($job['viewData']['delayed'])
                            &middot; Delayed: {{ $job['viewData']['delayed_until'] }}
                            @endif
                            @if (!is_null($job['viewData']['runtime']))
                            &middot; Runtime: {{ $job['viewData']['runtime'] }}s
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        </li>
    @endforeach
</ul>
