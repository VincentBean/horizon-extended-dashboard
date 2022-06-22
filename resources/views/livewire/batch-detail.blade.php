<div wire:poll.5s>
    <div class="space-y-6 lg:col-start-1 lg:col-span-2">
        <section>
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <div class="flex space-x-4">
                        <h2 class="text-lg leading-6 font-medium text-gray-900">
                            Batch Information
                        </h2>
                    </div>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        @foreach ($this->getDetails() as $detail)
                            <div
                                    class="bg-white odd:bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">{{ $detail['name'] }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $detail['value'] }}</dd>
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
                            Failed Jobs
                        </h2>
                    </div>
                </div>
                <div class="border-t border-gray-200">
                    <x-horizondashboard::job-list :jobs="$this->getFailedJobs()"/>
                </div>
            </div>
        </section>
    </div>
</div>
