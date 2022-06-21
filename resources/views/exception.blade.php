<x-horizondashboard::layout title="Job Exception">
    <div class="space-y-4">
        <section>
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <div class="space-y-2">
                        <h2 class="text-lg leading-6 font-medium text-gray-900">
                            {{ $exception['exception'] }}
                        </h2>
                        <span class="text-sm text-gray-800">{{ $exception['jobInformation']['class'] }}</span>
                    </div>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div
                            class="bg-white odd:bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Occured At</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $exception['occured_at']->toDateTimeString() }}</dd>
                        </div>
                        <div
                            class="bg-white odd:bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Message</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $exception['message'] }}</dd>
                        </div>
                        <div
                            class="bg-white odd:bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Attempt</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $exception['attempt'] }}</dd>
                        </div>
                        <div
                            class="bg-white odd:bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Job Runtime</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $exception['runtime'] }}s
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

        </section>

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
                        @foreach ($exception['data'] as $name => $value)
                            <div class="bg-white odd:bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">{{ $name }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ json_encode($value) }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            </div>
        </section>

        <section>
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <div class="flex space-x-4">
                        <h2 class="text-lg leading-6 font-medium text-gray-900">
                            Stacktrace
                        </h2>
                    </div>
                </div>
                <div class="border-t border-gray-200">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">File</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Line</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Function</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white">
                        @foreach ($exception['trace'] as $line)
                            <tr @class([
                                    'bg-gray-100' => str_contains($line['file'], '/vendor/')
                            ])>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ str_replace(base_path(), '', $line['file']) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $line['line'] }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ implode('', [$line['class'], $line['type'], $line['function']]) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</x-horizondashboard::layout>
