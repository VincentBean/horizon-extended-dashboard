<div>
    <div class="bg-white overflow-hidden shadow rounded-lg divide-y divide-gray-200">
        <div class="px-4 py-5 sm:px-6">
            <h1 class="text-xl">CPU %</h1>
        </div>
        <div class="px-4 py-5 sm:p-6 h-96">
            <livewire:livewire-line-chart
                :line-chart-model="$model"
            />
        </div>
    </div>

</div>
