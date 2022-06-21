<x-horizondashboard::layout title="{{ ucfirst($type) }} Jobs">
    <livewire:horizon-dashboard.job-list :type="$type" :queue="$queue"/>
</x-horizondashboard::layout>
