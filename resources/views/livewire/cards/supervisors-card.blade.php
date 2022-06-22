<section class="space-y-6" wire:poll.30s>
@foreach ($data as $master => $data)
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex justify-between">
                <h2 class="text-lg leading-6 font-medium text-gray-900">
                    Supervisor {{ $master }}
                </h2>
                <div class="flex">
                    <span class="text-sm text-gray-800">PID: {{ $data->pid }} &middot; Status: {{ $data->status }}</span>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-200 overflow-x-scroll ">
            <table class="min-w-full divide-y divide-gray-300 overflow-y-scroll">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"></th>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">PID</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Name</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Processes</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Min Processes</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Max Processes</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Queue</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Backoff</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Force</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Max Tries</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Max Time</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Max Jobs</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Memory</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nice</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Workers Name</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Sleep</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Timeout</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Balance</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Balance Cooldown</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Balance Max Shift</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Parent PID</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Rest</th>
                </tr>
                </thead>
                <tbody class="bg-white">
                @foreach ($data->supervisors ?? [] as $supervisor)
                    <tr>
                        <td class="whitespace-nowrap px-3 py-4">
                            <livewire:horizon-dashboard.components.controls :key="$supervisor->pid" supervisor="{{ $supervisor->name }}"/>
                        </td>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $supervisor->pid }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $supervisor->name }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $supervisor->status }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                            <ul>
                                @foreach ($supervisor->processes as $process => $count)
                                    <li><b>{{ \Illuminate\Support\Str::afterLast($process, ':') }}</b>: {{ $count }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $supervisor->options['minProcesses'] }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $supervisor->options['maxProcesses'] }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $supervisor->options['queue'] }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $supervisor->options['backoff'] }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $supervisor->options['force'] ? 'Yes' : 'No' }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $supervisor->options['maxTries'] }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $supervisor->options['maxTime'] }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $supervisor->options['maxJobs'] }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $supervisor->options['memory'] }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $supervisor->options['nice'] }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $supervisor->options['workersName'] }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $supervisor->options['sleep'] }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $supervisor->options['timeout'] }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $supervisor->options['balance'] }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $supervisor->options['balanceCooldown'] }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $supervisor->options['balanceMaxShift'] }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $supervisor->options['parentId'] }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $supervisor->options['rest'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
    @endforeach
</section>
