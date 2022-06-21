<x-horizondashboard::layout title="Job Exceptions">
    <section>
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex space-x-4">
                    <h2 class="text-lg leading-6 font-medium text-gray-900">
                        Exceptions
                    </h2>
                </div>
            </div>
            <div class="border-t border-gray-200">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Job</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Exception</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Message</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Attempt</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Runtime</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Occurred at</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"></th>
                    </tr>
                    </thead>
                    <tbody class="bg-white">
                    @foreach ($exceptions as $exception)
                    <tr>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $exception->jobInformation->class }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $exception['exception'] }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $exception['message'] }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $exception['attempt'] }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $exception['runtime'] }}s</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $exception['occured_at']->toDateTimeString() }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                            <a href="{{ route('horizon-dashboard.exception', ['id' => $exception->id]) }}" class="text-md text-indigo-600 hover:text-indigo-800">View</a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $exceptions->links() }}
        </div>
    </section>
</x-horizondashboard::layout>
