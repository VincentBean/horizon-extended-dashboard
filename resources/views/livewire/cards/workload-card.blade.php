<section wire:poll.1s>
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex space-x-4">
                <h2 class="text-lg leading-6 font-medium text-gray-900">
                    Workload
                </h2>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                        Queue
                    </th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Processes</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Jobs</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Wait</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"></th>
                </tr>
                </thead>
                <tbody class="bg-white">
                @foreach ($queues as $queue)
                    <tr>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $queue['name'] }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $queue['processes'] }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($queue['length']) }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $queue['wait'] }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                            <button wire:click="clearQueue('{{ $queue['name'] }}')" type="button" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Clear Queue
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
</section>
