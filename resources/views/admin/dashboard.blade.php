@extends('layouts.app')

@php
$colorPalette = [
    ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'hover' => 'hover:bg-purple-200'],
    ['bg' => 'bg-pink-100', 'text' => 'text-pink-700', 'hover' => 'hover:bg-pink-200'],
    ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700', 'hover' => 'hover:bg-indigo-200'],
    ['bg' => 'bg-cyan-100', 'text' => 'text-cyan-700', 'hover' => 'hover:bg-cyan-200'],
    ['bg' => 'bg-teal-100', 'text' => 'text-teal-700', 'hover' => 'hover:bg-teal-200'],
    ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'hover' => 'hover:bg-orange-200'],
    ['bg' => 'bg-rose-100', 'text' => 'text-rose-700', 'hover' => 'hover:bg-rose-200'],
    ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'hover' => 'hover:bg-amber-200'],
    ['bg' => 'bg-lime-100', 'text' => 'text-lime-700', 'hover' => 'hover:bg-lime-200'],
    ['bg' => 'bg-sky-100', 'text' => 'text-sky-700', 'hover' => 'hover:bg-sky-200'],
];
$getGroupColor = fn($groupId) => $colorPalette[$groupId % count($colorPalette)];
@endphp

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
        <a href="{{ route('admin.logout') }}" class="text-sm text-gray-600 hover:underline">Logout</a>
    </div>

    <div class="mb-8 grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-6">
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="text-3xl font-bold text-blue-600">{{ $groups->count() }}</div>
            <div class="text-gray-600">Gruppen</div>
        </div>
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="text-3xl font-bold text-green-600">{{ $users->count() }}</div>
            <div class="text-gray-600">User</div>
        </div>
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="text-3xl font-bold text-purple-600">{{ $groups->where('status', 'started')->count() }}</div>
            <div class="text-gray-600">Aktive Gruppen</div>
        </div>
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="text-3xl font-bold text-cyan-600">{{ $totalCreated }}</div>
            <div class="text-gray-600">Erstellt (Total)</div>
        </div>
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="text-3xl font-bold text-emerald-600">{{ $totalStarted }}</div>
            <div class="text-gray-600">Gestartet (Total)</div>
        </div>
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="text-3xl font-bold text-orange-600">{{ $totalAccounts }}</div>
            <div class="text-gray-600">Accounts (Total)</div>
        </div>
    </div>

    @if(count($chartData) > 0)
    <div class="mb-8">
        <h2 class="mb-4 text-2xl font-semibold text-gray-800">Statistiken</h2>
        <div class="rounded-lg bg-white p-6 shadow">
            <canvas id="statisticsChart" height="100"></canvas>
        </div>
    </div>
    @endif

    <div class="mb-8">
        <h2 class="mb-4 text-2xl font-semibold text-gray-800">Wichtelgruppen</h2>
        <div class="overflow-x-auto rounded-lg bg-white shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Datum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Teilnehmer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Erstellt</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Link</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($groups as $group)
                    <tr>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $group->id }}</td>
                        @php $groupColor = $getGroupColor($group->id); @endphp
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                            <span class="inline-flex items-center rounded px-2 py-0.5 {{ $groupColor['bg'] }} {{ $groupColor['text'] }}">
                                {{ $group->name }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $group->status === 'started' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $group->status }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $group->date }}</td>
                        @php
                            $total = $group->users->count();
                            $invited = $group->users->where('pivot.status', 'invited')->count();
                            $approved = $group->users->where('pivot.status', 'approved')->count();
                            $declined = $group->users->where('pivot.status', 'declined')->count();
                        @endphp
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-2">
                                <div class="flex h-5 w-36 overflow-hidden rounded bg-gray-200 text-xs font-medium">
                                    @if($total > 0)
                                        @if($approved > 0)
                                            <div class="flex items-center justify-center bg-green-500 text-white" style="width: {{ ($approved / $total) * 100 }}%" title="Zugesagt">{{ $approved }}</div>
                                        @endif
                                        @if($invited > 0)
                                            <div class="flex items-center justify-center bg-yellow-500 text-white" style="width: {{ ($invited / $total) * 100 }}%" title="Angefragt">{{ $invited }}</div>
                                        @endif
                                        @if($declined > 0)
                                            <div class="flex items-center justify-center bg-red-500 text-white" style="width: {{ ($declined / $total) * 100 }}%" title="Abgesagt">{{ $declined }}</div>
                                        @endif
                                    @endif
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ $total }}</span>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $group->created_at->format('d.m.Y H:i') }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            @php $admin = $group->users->first(fn($u) => $u->pivot->is_admin); @endphp
                            @if($admin)
                                <a href="{{ route('wichtelgroup', ['group' => $group->id, 'token' => $admin->api_token]) }}"
                                   class="text-blue-600 hover:underline"
                                   target="_blank">
                                    Öffnen (als Admin)
                                </a>
                            @else
                                <span class="text-gray-400">Kein Admin</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <h2 class="mb-4 text-2xl font-semibold text-gray-800">User</h2>
        <div class="overflow-x-auto rounded-lg bg-white shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Gruppen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($users as $user)
                    <tr>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $user->id }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @if($user->groups->isEmpty())
                                <span class="text-gray-400">Keine Gruppen</span>
                            @else
                                @foreach($user->groups as $group)
                                    @php $groupColor = $getGroupColor($group->id); @endphp
                                    <a href="{{ route('wichtelgroup', ['group' => $group->id, 'token' => $user->api_token]) }}"
                                       class="inline-flex items-center rounded px-2 py-0.5 text-xs {{ $groupColor['bg'] }} {{ $groupColor['text'] }} {{ $groupColor['hover'] }}"
                                       target="_blank"
                                       title="Als {{ $user->name }} in {{ $group->name }}">
                                        {{ $group->name }}
                                        @if($user->isAdminInGroup($group))
                                            <span class="ml-1" title="Admin">★</span>
                                        @endif
                                    </a>
                                @endforeach
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@if(count($chartData) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('statisticsChart').getContext('2d');
    const chartData = @json($chartData);

    const labels = Object.keys(chartData);
    const createdData = labels.map(month => chartData[month].created);
    const startedData = labels.map(month => chartData[month].started);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Erstellte Gruppen',
                    data: createdData,
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                },
                {
                    label: 'Gestartete Gruppen',
                    data: startedData,
                    backgroundColor: 'rgba(34, 197, 94, 0.7)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });
});
</script>
@endif
@endsection
