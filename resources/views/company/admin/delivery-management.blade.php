@extends('company.admin.main')
@section('title', 'Orders')

@php
    $card = 'bg-white dark:bg-[#181A1E] rounded-2xl border border-gray-200/70 dark:border-white/10 shadow-sm';
    $input = 'w-full rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-transparent px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500/40 focus:border-violet-500';
    $query = request()->except(['page', 'highlight']);
    $allCount = $statusCounts->sum();
    $today = now('Asia/Bangkok');

    // Chips for every active filter, each with a link that removes just that filter
    $chips = [];
    if ($searchQuery) $chips['search'] = 'Search: “' . $searchQuery . '”';
    if ($selectedLocation) $chips['location_id'] = 'Location: ' . ($locationList->firstWhere('location_id', $selectedLocation)->location_name ?? $selectedLocation);
    if ($selectedStaff) $chips['staff_id'] = 'Staff: ' . ($staffList->firstWhere('id', $selectedStaff)->name ?? $selectedStaff);
    if ($selectedDeliveryType) $chips['delivery_type'] = 'Type: ' . ucfirst($selectedDeliveryType);
    if ($selectedPaymentStatus !== null && $selectedPaymentStatus !== '') $chips['payment_status'] = $selectedPaymentStatus ? 'Paid' : 'Payment pending';
    if ($dateFrom) $chips['date_from'] = 'From ' . \Carbon\Carbon::parse($dateFrom)->format('j M Y');
    if ($dateTo) $chips['date_to'] = 'To ' . \Carbon\Carbon::parse($dateTo)->format('j M Y');
@endphp

@section('content')
<section>
<main class="!max-h-none">
<div class="max-w-[1400px] mx-auto space-y-5 text-gray-800 dark:text-gray-100">

    @if (session('success-message'))
        <div class="flex items-center gap-3 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-emerald-800">
            <i class='bx bxs-check-circle text-xl'></i>
            <span class="font-medium">{{ session('success-message') }}</span>
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Orders</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Track every order across all locations</p>
        </div>
        <a href="{{ route('admin-dashboard') }}" class="inline-flex items-center gap-1 text-sm font-medium text-violet-600 hover:text-violet-700">
            <i class='bx bx-bar-chart-alt-2 text-lg'></i> Back to dashboard
        </a>
    </div>

    {{-- Status tabs --}}
    <div class="flex gap-2 overflow-x-auto pb-1">
        <a href="{{ route('delivery-management', \Illuminate\Support\Arr::except($query, 'status')) }}"
           class="shrink-0 inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold border transition
                  {{ !$selectedStatus ? 'bg-gray-900 text-white border-gray-900 dark:bg-white dark:text-gray-900' : 'bg-white dark:bg-[#181A1E] border-gray-200 dark:border-white/10 hover:border-gray-300' }}">
            All <span class="rounded-full px-2 text-xs {{ !$selectedStatus ? 'bg-white/20' : 'bg-gray-100 dark:bg-white/10' }}">{{ $allCount }}</span>
        </a>
        @foreach ($statusList as $status)
            @php $active = $selectedStatus === $status->value; $count = $statusCounts[$status->value] ?? 0; @endphp
            <a href="{{ route('delivery-management', ['status' => $status->value] + $query) }}"
               class="shrink-0 inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold border transition
                      {{ $active ? 'text-white border-transparent' : 'bg-white dark:bg-[#181A1E] border-gray-200 dark:border-white/10 hover:border-gray-300' }}"
               style="{{ $active ? 'background:' . $status->color() : '' }}">
                <span class="h-2 w-2 rounded-full" style="background: {{ $active ? '#fff' : $status->color() }}"></span>
                {{ $status->label() }}
                <span class="rounded-full px-2 text-xs {{ $active ? 'bg-white/20' : 'bg-gray-100 dark:bg-white/10' }}">{{ $count }}</span>
            </a>
        @endforeach
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('delivery-management') }}" id="filterForm" class="{{ $card }} p-4 space-y-4">
        @if ($selectedStatus)
            <input type="hidden" name="status" value="{{ $selectedStatus }}">
        @endif

        <div class="flex flex-col lg:flex-row gap-3">
            <label class="relative flex-1">
                <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg'></i>
                <input type="text" name="search" value="{{ $searchQuery }}" placeholder="Search order code, customer name or phone…"
                       class="{{ $input }} pl-10 py-2.5" autocomplete="off">
            </label>
            <div class="flex flex-wrap gap-2">
                @foreach (['today' => 'Today', 'yesterday' => 'Yesterday', '7d' => 'Last 7 days', '30d' => 'Last 30 days'] as $key => $label)
                    @php
                        $from = match ($key) { 'today' => $today->toDateString(), 'yesterday' => $today->copy()->subDay()->toDateString(), '7d' => $today->copy()->subDays(6)->toDateString(), '30d' => $today->copy()->subDays(29)->toDateString() };
                        $to = $key === 'yesterday' ? $from : $today->toDateString();
                        $isActive = $dateFrom === $from && $dateTo === $to;
                    @endphp
                    <button type="button" data-from="{{ $from }}" data-to="{{ $to }}"
                            class="quick-date rounded-xl px-3 py-2 text-sm font-medium border transition
                                   {{ $isActive ? 'bg-violet-600 text-white border-violet-600' : 'border-gray-200 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/5' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Location</label>
                <select name="location_id" id="locationSelect" class="{{ $input }}">
                    <option value="">All locations</option>
                    @foreach ($locationList as $location)
                        <option value="{{ $location->location_id }}" @selected($selectedLocation == $location->location_id)>{{ $location->location_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Assigned to</label>
                <select name="staff_id" id="staffSelect" class="{{ $input }}">
                    <option value="">Anyone</option>
                    @foreach ($staffList as $staff)
                        <option value="{{ $staff->id }}" data-location="{{ $staff->location_id }}" @selected($selectedStaff == $staff->id)>
                            {{ $staff->name }}{{ $staff->role == 3 ? ' (Delivery)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Order type</label>
                <select name="delivery_type" class="{{ $input }}">
                    <option value="">All types</option>
                    @foreach ($deliveryTypes as $type)
                        <option value="{{ $type }}" @selected($selectedDeliveryType == $type)>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Payment</label>
                <select name="payment_status" class="{{ $input }}">
                    <option value="">All payments</option>
                    <option value="1" @selected($selectedPaymentStatus === '1')>Paid</option>
                    <option value="0" @selected($selectedPaymentStatus === '0')>Pending</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">From</label>
                <input type="date" name="date_from" id="dateFrom" value="{{ $dateFrom }}" class="{{ $input }}">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">To</label>
                <input type="date" name="date_to" id="dateTo" value="{{ $dateTo }}" class="{{ $input }}">
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap gap-2">
                @foreach ($chips as $key => $label)
                    <a href="{{ route('delivery-management', \Illuminate\Support\Arr::except($query, $key)) }}"
                       class="inline-flex items-center gap-1 rounded-full bg-violet-50 dark:bg-violet-500/15 text-violet-700 dark:text-violet-300 px-3 py-1 text-xs font-semibold hover:bg-violet-100">
                        {{ $label }} <i class='bx bx-x text-base'></i>
                    </a>
                @endforeach
            </div>
            <div class="flex gap-2">
                @if (count($chips) || $selectedStatus)
                    <a href="{{ route('delivery-management') }}" class="rounded-xl px-4 py-2 text-sm font-medium border border-gray-200 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/5">Clear all</a>
                @endif
                <button class="rounded-xl px-5 py-2 text-sm font-semibold bg-violet-600 text-white hover:bg-violet-700 shadow-sm">Apply filters</button>
            </div>
        </div>
    </form>

    {{-- Orders table --}}
    <div class="{{ $card }} overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-white/10">
            <h2 class="font-semibold text-lg">
                {{ $selectedStatus ? \App\Enums\OrderStatusEnum::from($selectedStatus)->label() . ' orders' : 'All orders' }}
                <span class="ml-1 text-sm font-medium text-gray-500">{{ $orders->total() }}</span>
            </h2>
            <button type="button" onclick="location.reload()" class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 dark:hover:bg-white/10">
                <i class='bx bx-refresh text-lg'></i> Refresh
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="ui-table w-full text-sm" id="ordersTable">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wide text-gray-500 bg-gray-50/70 dark:bg-white/5">
                        <th class="px-5 py-3 font-medium">Order</th>
                        <th class="px-5 py-3 font-medium">Customer</th>
                        <th class="px-5 py-3 font-medium">Items</th>
                        <th class="px-5 py-3 font-medium">Location</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium">Assigned to</th>
                        <th class="px-5 py-3 font-medium text-right">Amount</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                    @forelse ($orders as $order)
                        @php $items = $order->customerOrderDetail; @endphp
                        <tr data-order-id="{{ $order->id }}" class="align-top hover:bg-gray-50/80 dark:hover:bg-white/5 transition">
                            <td class="px-5 py-4 whitespace-nowrap">
                                <p class="font-mono font-semibold">{{ $order->order_code }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $order->created_at->format('j M Y · g:i A') }}</p>
                                <span class="inline-flex items-center gap-1 mt-1.5 text-xs text-gray-600 dark:text-gray-300">
                                    <i class='bx {{ str_contains(strtolower($order->delivery_type), 'pick') ? 'bx-shopping-bag' : (str_contains(strtolower($order->delivery_type), 'dine') ? 'bx-restaurant' : 'bx-cycling') }}'></i>
                                    {{ ucfirst($order->delivery_type) }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-medium">{{ $order->customer->name ?? 'Guest' }}</p>
                                <a href="tel:{{ $order->customer_contact }}" class="text-xs text-gray-500 hover:text-violet-600">{{ $order->customer_contact }}</a>
                                @if ($order->order_notes)
                                    <p class="mt-1 text-[13px] text-slate-700 dark:text-gray-300 border border-dashed border-slate-300 dark:border-white/20 rounded-lg px-2.5 py-1 max-w-[220px]"><i class='bx bx-note'></i> {{ $order->order_notes }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-1 max-w-[240px]">
                                    @foreach ($items->take(3) as $item)
                                        <span class="inline-flex items-center gap-1 rounded-lg bg-slate-100 border border-slate-200 text-slate-800 dark:bg-white/10 dark:border-white/10 dark:text-gray-100 px-2.5 py-1 text-[13px] font-medium">
                                            <span class="text-emerald-700 dark:text-emerald-400 font-bold">{{ $item->quantity }}×</span> {{ $item->foodMenu->name ?? 'Item' }}
                                        </span>
                                    @endforeach
                                    @if ($items->count() > 3)
                                        <span class="rounded-md px-2 py-0.5 text-xs text-gray-500">+{{ $items->count() - 3 }} more</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300 whitespace-nowrap">{{ $order->location->location_name ?? '—' }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset whitespace-nowrap {{ $order->order_status->badgeClasses() }}">
                                    {{ $order->order_status->label() }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if ($order->assignedStaff)
                                    <span class="inline-flex items-center gap-2">
                                        <span class="h-7 w-7 rounded-full bg-violet-100 text-violet-700 grid place-items-center text-xs font-bold">
                                            {{ strtoupper(substr($order->assignedStaff->name, 0, 1)) }}
                                        </span>
                                        {{ Str::limit($order->assignedStaff->name, 18) }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">Unassigned</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <p class="font-semibold">{{ $order->location->currency ?? '' }} {{ number_format($order->order_total_price, 2) }}</p>
                                @if ((float) $order->delivery_charge > 0)
                                    <p class="text-xs text-gray-500">+ {{ number_format($order->delivery_charge, 2) }} delivery</p>
                                @endif
                                <p class="text-xs mt-1">
                                    <span class="text-gray-500">{{ ucfirst($order->payment_type) }}</span> ·
                                    <span class="{{ $order->isPaid ? 'text-emerald-600' : 'text-amber-600' }} font-medium">{{ $order->isPaid ? 'Paid' : 'Unpaid' }}</span>
                                </p>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <button type="button" onclick="openHistory('{{ $order->id }}')" title="Order history"
                                        class="rounded-lg p-2 text-gray-500 hover:text-violet-600 hover:bg-violet-50 dark:hover:bg-white/10">
                                    <i class='bx bx-history text-xl'></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-16 text-center">
                                <i class='bx bx-search-alt text-5xl text-gray-300'></i>
                                <p class="mt-2 font-semibold">No orders found</p>
                                <p class="text-sm text-gray-500">Try a different status or clear some filters.</p>
                                @if (count($chips) || $selectedStatus)
                                    <a href="{{ route('delivery-management') }}" class="inline-block mt-3 text-sm font-medium text-violet-600">Clear all filters</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->total() > 0)
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-4 border-t border-gray-100 dark:border-white/10 text-sm text-gray-500">
                <span>Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ $orders->total() }}</span>
                <div>{{ $orders->appends(request()->except('highlight'))->render('partials.paginator') }}</div>
            </div>
        @endif
    </div>
</div>

{{-- History modals --}}
@foreach ($orders as $order)
    <div id="modal-history-{{ $order->id }}" class="history-modal fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
        <div class="w-full max-w-lg rounded-2xl bg-white dark:bg-[#181A1E] text-gray-800 dark:text-gray-100 shadow-xl">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-white/10">
                <div>
                    <h3 class="font-semibold text-lg">Order history</h3>
                    <p class="text-sm text-gray-500 font-mono">{{ $order->order_code }}</p>
                </div>
                <button type="button" onclick="closeHistory('{{ $order->id }}')" class="rounded-lg p-1 text-gray-500 hover:bg-gray-100 dark:hover:bg-white/10">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>
            <div class="px-5 py-4 max-h-[60vh] overflow-y-auto">
                @forelse ($order->histories as $history)
                    <div class="relative pl-6 pb-4 border-l-2 border-violet-200 last:pb-0">
                        <span class="absolute -left-[7px] top-1 h-3 w-3 rounded-full bg-violet-500"></span>
                        <p class="text-xs text-gray-500">{{ $history->created_at->format('j M Y, g:i A') }}</p>
                        <p class="font-semibold">{{ $history->action }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-300">By {{ $history->staff->name ?? 'System' }}</p>
                        @if ($history->old_status && $history->new_status)
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $history->old_status }} → {{ $history->new_status }}</p>
                        @endif
                        @if ($history->notes)
                            <p class="text-sm mt-1 rounded-md bg-gray-50 dark:bg-white/5 px-2 py-1">{{ $history->notes }}</p>
                        @endif
                    </div>
                @empty
                    <p class="py-8 text-center text-gray-500">No history recorded yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endforeach
</main>
</section>
@endsection

@section('scripts')
<script>
    function openHistory(id) {
        const modal = document.getElementById('modal-history-' + id);
        modal.classList.replace('hidden', 'flex');
    }

    function closeHistory(id) {
        document.getElementById('modal-history-' + id).classList.replace('flex', 'hidden');
    }

    document.querySelectorAll('.history-modal').forEach(function (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) modal.classList.replace('flex', 'hidden');
        });
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') document.querySelectorAll('.history-modal.flex').forEach(m => m.classList.replace('flex', 'hidden'));
    });

    // Quick date buttons fill the From / To dates and apply
    document.querySelectorAll('.quick-date').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('dateFrom').value = btn.dataset.from;
            document.getElementById('dateTo').value = btn.dataset.to;
            document.getElementById('filterForm').submit();
        });
    });

    // Only list staff who work at the chosen location
    (function () {
        const locationSelect = document.getElementById('locationSelect');
        const staffSelect = document.getElementById('staffSelect');

        function updateStaffOptions() {
            const loc = locationSelect.value;
            Array.from(staffSelect.options).forEach(function (opt) {
                const show = !opt.value || !loc || opt.dataset.location === loc;
                opt.hidden = !show;
                if (!show && opt.selected) staffSelect.value = '';
            });
        }

        locationSelect.addEventListener('change', updateStaffOptions);
        updateStaffOptions();
    })();
</script>
@endsection
