@extends('company.admin.main')

@section('title', 'Dashboard')

@php
    $rangeOptions = [
        'today' => 'Today',
        'yesterday' => 'Yesterday',
        '7d' => 'Last 7 days',
        '30d' => 'Last 30 days',
        'month' => 'This month',
        'custom' => 'Custom',
    ];
    $hour = now('Asia/Bangkok')->hour;
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
    $filterQuery = array_filter(['location_id' => $selectedLocation, 'date_from' => $dateFrom->toDateString(), 'date_to' => $dateTo->toDateString()]);
    $statusChartData = $statusBreakdown->where('total', '>', 0)->values()->map(fn ($r) => [
        'label' => $r['status']->label(),
        'total' => $r['total'],
        'color' => $r['status']->color(),
    ]);
    $card = 'bg-white dark:bg-[#181A1E] rounded-2xl border border-gray-200/70 dark:border-white/10 shadow-sm';
@endphp

@section('content')
<section>
<main class="!max-h-none">
<div class="max-w-[1400px] mx-auto space-y-6 text-gray-800 dark:text-gray-100">

    @if (session('success-message'))
        <div class="flex items-center gap-3 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-emerald-800">
            <i class='bx bxs-check-circle text-xl'></i>
            <span class="font-medium">{{ session('success-message') }}</span>
        </div>
    @endif

    {{-- Header + filters --}}
    <div class="flex flex-col xl:flex-row xl:items-end xl:justify-between gap-4">
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ now('Asia/Bangkok')->format('l, j F Y') }}</p>
            <h1 class="text-3xl font-bold tracking-tight mt-1">{{ $greeting }}, {{ Str::before(Auth::user()->name, ' ') }}</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Here's how
                <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $selectedLocation ? $locationList->firstWhere('location_id', $selectedLocation)?->location_name : 'all locations' }}</span>
                {{ $dateFrom->eq($dateTo) ? 'did on ' . $dateFrom->format('j M') : 'did from ' . $dateFrom->format('j M') . ' to ' . $dateTo->format('j M') }}.
            </p>
        </div>

        <form method="GET" action="{{ route('admin-dashboard') }}" id="dashFilter"
              class="{{ $card }} p-2 flex flex-wrap items-center gap-2">
            @if (Auth::user()->role == 1)
                <label class="flex items-center gap-2 pl-2 pr-1 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10">
                    <i class='bx bx-map text-gray-500'></i>
                    <select name="location_id" onchange="this.form.submit()"
                            class="bg-transparent py-2 pr-2 text-sm font-medium focus:outline-none">
                        <option value="">All locations</option>
                        @foreach ($locationList as $location)
                            <option value="{{ $location->location_id }}" @selected($selectedLocation == $location->location_id)>{{ $location->location_name }}</option>
                        @endforeach
                    </select>
                </label>
            @endif

            <input type="hidden" name="range" id="rangeInput" value="{{ $range }}">
            <div class="flex flex-wrap gap-1 bg-gray-50 dark:bg-white/5 rounded-xl p-1">
                @foreach ($rangeOptions as $value => $label)
                    <button type="button" data-range="{{ $value }}"
                            class="range-btn px-3 py-1.5 rounded-lg text-sm font-medium transition
                                   {{ $range === $value ? 'bg-violet-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-white/10' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <div id="customDates" class="{{ $range === 'custom' ? 'flex' : 'hidden' }} items-center gap-2">
                <input type="date" name="date_from" value="{{ $dateFrom->toDateString() }}"
                       class="px-2 py-1.5 text-sm rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-transparent">
                <span class="text-gray-400">→</span>
                <input type="date" name="date_to" value="{{ $dateTo->toDateString() }}"
                       class="px-2 py-1.5 text-sm rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-transparent">
                <button class="px-3 py-1.5 rounded-lg bg-violet-600 text-white text-sm font-medium hover:bg-violet-700">Apply</button>
            </div>

            @if ($selectedLocation || $range !== '7d')
                <a href="{{ route('admin-dashboard') }}" title="Reset filters"
                   class="px-2 py-1.5 rounded-lg text-gray-500 hover:text-gray-800 hover:bg-gray-100 dark:hover:bg-white/10">
                    <i class='bx bx-reset text-lg align-middle'></i>
                </a>
            @endif
        </form>
    </div>

    {{-- Needs attention --}}
    @if ($newOrdersNow > 0 || $unassignedNow > 0)
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-2xl bg-gradient-to-r from-rose-500 to-orange-500 text-white px-5 py-4 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
                </span>
                <p class="font-medium">
                    <span class="text-lg font-bold">{{ $newOrdersNow }}</span> new {{ Str::plural('order', $newOrdersNow) }} waiting
                    <span class="opacity-80">· {{ $unassignedNow }} not assigned to anyone yet</span>
                </p>
            </div>
            <a href="{{ route('delivery-management', array_filter(['status' => 'Ordered', 'location_id' => $selectedLocation])) }}"
               class="inline-flex items-center gap-1 rounded-xl bg-white/15 hover:bg-white/25 px-4 py-2 text-sm font-semibold">
                View new orders <i class='bx bx-right-arrow-alt text-lg'></i>
            </a>
        </div>
    @endif

    {{-- KPI cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <a href="{{ route('delivery-management', $filterQuery) }}" class="{{ $card }} p-5 hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total orders</p>
                <span class="h-10 w-10 rounded-xl bg-violet-50 text-violet-600 grid place-items-center"><i class='bx bx-receipt text-xl'></i></span>
            </div>
            <p class="text-3xl font-bold mt-3">{{ number_format($totalOrders) }}</p>
            <p class="text-xs text-gray-500 mt-1 group-hover:text-violet-600">View all orders →</p>
        </a>

        <div class="{{ $card }} p-5">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Revenue</p>
                <span class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 grid place-items-center"><i class='bx bx-wallet text-xl'></i></span>
            </div>
            @forelse ($revenue as $row)
                <div class="mt-3 {{ !$loop->first ? 'pt-2 border-t border-gray-100 dark:border-white/10' : '' }}">
                    <p class="text-2xl font-bold">{{ $row->currency }} {{ number_format($row->total, 2) }}</p>
                    <p class="text-xs text-gray-500">
                        Paid {{ number_format($row->paid, 2) }} · Avg {{ number_format($row->orders ? $row->total / $row->orders : 0, 2) }}/order
                    </p>
                </div>
            @empty
                <p class="text-3xl font-bold mt-3">0.00</p>
                <p class="text-xs text-gray-500 mt-1">No sales in this period</p>
            @endforelse
        </div>

        <a href="{{ route('delivery-management', $filterQuery + ['status' => 'preparing']) }}" class="{{ $card }} p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">In progress</p>
                <span class="h-10 w-10 rounded-xl bg-amber-50 text-amber-600 grid place-items-center"><i class='bx bx-bowl-hot text-xl'></i></span>
            </div>
            <p class="text-3xl font-bold mt-3">{{ number_format($inProgressCount) }}</p>
            <p class="text-xs text-gray-500 mt-1">Preparing, ready or on the way</p>
        </a>

        <div class="{{ $card }} p-5">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed</p>
                <span class="h-10 w-10 rounded-xl bg-sky-50 text-sky-600 grid place-items-center"><i class='bx bx-check-double text-xl'></i></span>
            </div>
            <p class="text-3xl font-bold mt-3">{{ number_format($doneCount) }}</p>
            <p class="text-xs text-gray-500 mt-1">
                {{ $totalOrders ? round($doneCount / $totalOrders * 100) : 0 }}% of orders · {{ $cancelledCount }} cancelled
            </p>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        <div class="{{ $card }} p-5 xl:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="font-semibold text-lg">Orders per day</h2>
                    <p class="text-sm text-gray-500">{{ $dateFrom->format('j M') }} – {{ $dateTo->format('j M Y') }}</p>
                </div>
            </div>
            <div class="h-72"><canvas id="ordersChart"></canvas></div>
        </div>

        <div class="{{ $card }} p-5">
            <h2 class="font-semibold text-lg">Order status</h2>
            <p class="text-sm text-gray-500 mb-4">Where this period's orders are now</p>
            @if ($totalOrders > 0)
                <div class="h-44"><canvas id="statusChart"></canvas></div>
                <ul class="mt-4 space-y-1.5 text-sm">
                    @foreach ($statusBreakdown->where('total', '>', 0) as $row)
                        <li>
                            <a href="{{ route('delivery-management', $filterQuery + ['status' => $row['status']->value]) }}"
                               class="flex items-center justify-between rounded-lg px-2 py-1 hover:bg-gray-50 dark:hover:bg-white/5">
                                <span class="flex items-center gap-2">
                                    <span class="h-2.5 w-2.5 rounded-full" style="background: {{ $row['status']->color() }}"></span>
                                    {{ $row['status']->label() }}
                                </span>
                                <span class="font-semibold">{{ $row['total'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="h-44 grid place-items-center text-gray-400 text-sm">No orders in this period</div>
            @endif
        </div>
    </div>

    {{-- Recent orders + side panels --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        <div class="{{ $card }} xl:col-span-2 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-white/10">
                <h2 class="font-semibold text-lg">Recent orders</h2>
                <a href="{{ route('delivery-management', $filterQuery) }}" class="text-sm font-medium text-violet-600 hover:text-violet-700">View all →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="ui-table w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs uppercase tracking-wide text-gray-500 bg-gray-50/70 dark:bg-white/5">
                            <th class="px-5 py-3 font-medium">Order</th>
                            <th class="px-5 py-3 font-medium">Customer</th>
                            <th class="px-5 py-3 font-medium">Location</th>
                            <th class="px-5 py-3 font-medium">Status</th>
                            <th class="px-5 py-3 font-medium text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                        @forelse ($recentOrders as $order)
                            <tr data-order-id="{{ $order->id }}" class="hover:bg-gray-50 dark:hover:bg-white/5 cursor-pointer"
                                onclick="window.location='{{ route('delivery-management', ['highlight' => $order->id]) }}'">
                                <td class="px-5 py-3">
                                    <p class="font-mono font-semibold">{{ $order->order_code }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->created_at->format('j M, g:i A') }}</p>
                                </td>
                                <td class="px-5 py-3">
                                    <p class="font-medium">{{ $order->customer->name ?? 'Guest' }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->customer_contact }}</p>
                                </td>
                                <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $order->location->location_name ?? '—' }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $order->order_status->badgeClasses() }}">
                                        {{ $order->order_status->label() }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right font-semibold whitespace-nowrap">
                                    {{ $order->location->currency ?? '' }} {{ number_format($order->order_total_price, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-gray-400">
                                    <i class='bx bx-receipt text-4xl block mb-2'></i>
                                    No orders in this period
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-4">
            <div class="{{ $card }} p-5">
                <h2 class="font-semibold text-lg mb-3">Top selling items</h2>
                @php $maxQty = max(1, (int) $topItems->max('qty')); @endphp
                <ul class="space-y-3">
                    @forelse ($topItems as $item)
                        <li>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium truncate pr-2">{{ $loop->iteration }}. {{ $item->name }}</span>
                                <span class="text-gray-500 whitespace-nowrap">{{ $item->qty }} sold</span>
                            </div>
                            <div class="h-2 rounded-full bg-gray-100 dark:bg-white/10">
                                <div class="h-2 rounded-full bg-gradient-to-r from-violet-500 to-fuchsia-500" style="width: {{ $item->qty / $maxQty * 100 }}%"></div>
                            </div>
                        </li>
                    @empty
                        <li class="text-sm text-gray-400">No items sold in this period</li>
                    @endforelse
                </ul>
            </div>

            <div class="{{ $card }} p-5">
                <h2 class="font-semibold text-lg mb-3">Locations</h2>
                <ul class="divide-y divide-gray-100 dark:divide-white/10">
                    @foreach ($locationStats as $stat)
                        <li class="py-2.5 flex items-center justify-between gap-3">
                            <a href="{{ route('admin-dashboard', ['location_id' => $stat['location']->location_id, 'range' => $range, 'date_from' => $dateFrom->toDateString(), 'date_to' => $dateTo->toDateString()]) }}"
                               class="min-w-0 hover:text-violet-600">
                                <p class="font-medium truncate">{{ $stat['location']->location_name }}</p>
                                <p class="text-xs text-gray-500">{{ $stat['orders'] }} {{ Str::plural('order', $stat['orders']) }}
                                    @if ($stat['new_orders'] > 0)
                                        · <span class="text-rose-600 font-semibold">{{ $stat['new_orders'] }} new</span>
                                    @endif
                                </p>
                            </a>
                            <span class="text-sm font-semibold whitespace-nowrap">{{ $stat['location']->currency }} {{ number_format($stat['revenue'], 2) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="{{ $card }} p-5 grid grid-cols-2 gap-3">
                <a href="{{ route('staff-account') }}" class="rounded-xl bg-gray-50 dark:bg-white/5 p-3 hover:bg-gray-100 dark:hover:bg-white/10">
                    <i class='bx bx-group text-xl text-violet-600'></i>
                    <p class="text-2xl font-bold mt-1">{{ $staffcount }}</p>
                    <p class="text-xs text-gray-500">Staff</p>
                </a>
                <a href="{{ route('staff-account') }}" class="rounded-xl bg-gray-50 dark:bg-white/5 p-3 hover:bg-gray-100 dark:hover:bg-white/10">
                    <i class='bx bx-cycling text-xl text-sky-600'></i>
                    <p class="text-2xl font-bold mt-1">{{ $deliveryGuyCount }}</p>
                    <p class="text-xs text-gray-500">Delivery riders</p>
                </a>
            </div>
        </div>
    </div>

</div>
</main>
</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.js"></script>
<script>
    // Date range buttons
    document.querySelectorAll('.range-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('rangeInput').value = btn.dataset.range;
            if (btn.dataset.range === 'custom') {
                document.getElementById('customDates').classList.replace('hidden', 'flex');
                return;
            }
            document.getElementById('dashFilter').submit();
        });
    });

    if (window.Chart) {
        const isDark = document.body.classList.contains('dark');
        Chart.defaults.color = isDark ? '#9ca3af' : '#6b7280';
        Chart.defaults.font.family = 'Inter, sans-serif';

        const daily = @json($dailyOrders);
        new Chart(document.getElementById('ordersChart'), {
            type: 'bar',
            data: {
                labels: daily.map(d => d.label),
                datasets: [{
                    label: 'Orders',
                    data: daily.map(d => d.total),
                    backgroundColor: '#7c3aed',
                    hoverBackgroundColor: '#6d28d9',
                    borderRadius: 6,
                    maxBarThickness: 36,
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: isDark ? 'rgba(255,255,255,.06)' : '#f3f4f6' } }
                }
            }
        });

        const statusCanvas = document.getElementById('statusChart');
        if (statusCanvas) {
            const statuses = @json($statusChartData);
            new Chart(statusCanvas, {
                type: 'doughnut',
                data: {
                    labels: statuses.map(s => s.label),
                    datasets: [{ data: statuses.map(s => s.total), backgroundColor: statuses.map(s => s.color), borderWidth: 0 }]
                },
                options: { maintainAspectRatio: false, cutout: '70%', plugins: { legend: { display: false } } }
            });
        }
    }
</script>
@endsection
