<div wire:poll.5s>
    @if ($job == [])
        <h1>Job not found</h1>
    @else
        <div class="space-y-6 lg:col-start-1 lg:col-span-2">
            <section>
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="flex justify-between px-4 py-5 sm:px-6">
                        <div>
                            <div class="flex space-x-4">
                                <h2 class="text-lg leading-6 font-medium text-gray-900">
                                    {{ $job['name'] }}
                                </h2>
                                <span
                                        class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800"> {{ $job['status'] }} </span>
                                @if ($this->isUnique())
                                    <span
                                            class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-bold bg-yellow-100 text-yellow-800">UNIQUE</span>
                                @endif
                                @if (isset($job['retried_by']) && !blank($job['retried_by']))
                                    <span
                                            class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-bold bg-blue-100 text-blue-800">RETRIED</span>
                                @endif
                            </div>

                            @php ($description = collect([$job['queue'], $job['viewData']['tries']])->reject(fn($item) => blank($item)))
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ implode(' · ', $description->toArray()) }}</p>
                        </div>
                        <div>
                            @php($jobInfo = \VincentBean\HorizonDashboard\Models\JobInformation::findByClass($job['name']))
                            @if ($jobInfo !== null)
                                <a href="{{ route('horizon-dashboard.statistics-job', ['id' => $jobInfo->id]) }}"
                                   type="button"
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Job Statistics
                                </a>
                            @endif
                            <a href="{{ route('horizon-dashboard.statistics-queue', ['queue' => $job['queue']]) }}"
                               type="button"
                               class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Queue Statistics
                            </a>
                            <button wire:click="enQueue" type="button"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Re-queue
                            </button>
                        </div>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            @if(isset($job['payload']['retry_of']))
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Retry Of</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        <a href="{{ route('horizon-dashboard.job', ['id' => $job['payload']['retry_of']]) }}">{{ $job['payload']['retry_of'] }}</a>
                                    </dd>
                                </div>
                            @endif
                            @foreach ($this->getDetails() as $detail)
                                <div class="bg-white odd:bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">{{ $detail['name'] }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $detail['value'] }}</dd>
                                </div>
                            @endforeach
                            @if ($this->isUnique())
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Unique ID</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $this->getUniqueId() }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </section>

            @php($data = $this->getData($this->getCommand()))

            @if (count($data))
                <section>
                    <div class="bg-white shadow sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <div class="flex space-x-4">
                                <h2 class="text-lg leading-6 font-medium text-gray-900">
                                    Data
                                </h2>
                            </div>
                        </div>
                        <div class="border-t border-gray-200">
                            <dl>
                                @foreach ($data as $detail)
                                    <div class="bg-white odd:bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">{{ $detail['name'] }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            @if(is_array($detail['value']))
                                                <pre>{{ json_encode($detail['value'], JSON_PRETTY_PRINT) }}</pre>
                                            @else
                                                {{ $detail['value'] }}
                                            @endif
                                        </dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>
                    </div>
                </section>
            @endif

            @if (count(data_get($this->job, 'payload.tags', [])))
                <section>
                    <div class="bg-white shadow sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <div class="flex space-x-4">
                                <h2 class="text-lg leading-6 font-medium text-gray-900">
                                    Tags
                                </h2>
                            </div>
                        </div>
                        <div class="border-t border-gray-200">
                            <dl>
                                @foreach (data_get($this->job, 'payload.tags') as $tag)
                                    <div
                                            class="bg-white odd:bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">{{ $tag }}</dt>
                                    </div>
                                @endforeach
                            </dl>
                        </div>
                    </div>
                </section>
            @endif

            @if ($this->hasChain())
                <section>
                    <div class="bg-white shadow sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <div class="flex space-x-4">
                                <h2 class="text-lg leading-6 font-medium text-gray-900">
                                    Chained Jobs
                                </h2>
                            </div>
                        </div>
                        <div class="border-t border-gray-200">
                            <dl>
                                @foreach ($this->getChain() as $job)
                                    <div
                                            class="bg-white odd:bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">{{ $job['name'] }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $job['value'] }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>
                    </div>
                </section>
            @endif

            @if (isset($job['exception']) && !blank($job['exception']))
                <section>
                    <div class="bg-white shadow sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <div class="flex space-x-4">
                                <h2 class="text-lg leading-6 font-medium text-gray-900">
                                    Exception
                                </h2>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 p-4 leading-loose text-md text-gray-800">
                            {!! nl2br($job['exception']) !!}
                        </div>
                    </div>
                </section>
            @endif

            @if ($exceptions = $this->getExceptions())
                @if ($exceptions->isNotEmpty())
                    <section>
                        <div class="bg-white shadow sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6">
                                <div class="flex space-x-4">
                                    <h2 class="text-lg leading-6 font-medium text-gray-900">
                                        Exceptions
                                    </h2>
                                </div>
                            </div>
                            <div class="border-t border-gray-200 overflow-y-scroll">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                            Exception
                                        </th>
                                        <th scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Message
                                        </th>
                                        <th scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Attempt
                                        </th>
                                        <th scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Runtime
                                        </th>
                                        <th scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Occurred
                                            at
                                        </th>
                                        <th scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"></th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                    @foreach ($exceptions as $exception)
                                        <tr>
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $exception['exception'] }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $exception['message'] }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $exception['attempt'] }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $exception['runtime'] }}
                                                s
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $exception['occured_at']->toDateTimeString() }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                <a href="{{ route('horizon-dashboard.exception', ['id' => $exception->id]) }}"
                                                   class="text-md text-indigo-600 hover:text-indigo-800">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                @endif

                @if($this->hasRetries())

                    <section>
                        <div class="bg-white shadow sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6">
                                <div class="flex space-x-4">
                                    <h2 class="text-lg leading-6 font-medium text-gray-900">
                                        Retries
                                    </h2>
                                </div>
                            </div>
                            <div class="border-t border-gray-200">
                                <table class="min-w-full divide-y divide-gray-300 overflow-y-scroll">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                            Attempt
                                        </th>
                                        <th scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status
                                        </th>
                                        <th scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Retried at
                                        </th>
                                        <th scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"></th>

                                    </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                    @foreach ($this->getRetries() as $attempt => $retry)
                                        <tr>
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $attempt }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $retry['status'] }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::createFromTimestamp($retry['retried_at'])->toDateTimeString() }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                <a href="{{ route('horizon-dashboard.job', ['id' => $retry['id']]) }}"
                                                   class="text-blue-800 hover:text-blue-600">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </section>
                @endif
            @endif

        </div>
    @endif
</div>