@extends('company.admin.main')
@section('title', 'Food Location')
@section('content')

<div class="food-menu-index">
    <section>
        <main>

            @if (session('success-message'))
                <div class="success-message left-green">
                    <i class='bx bxs-check-circle'></i>
                    <div class="text">
                        <span>Success</span>
                        <span class="message">{{ session('success-message') }}</span>
                    </div>
                </div>
            @endif

            <!-- Header -->
            <div class="header">
                <div class="left">
                    <h1>Food Location</h1>
                </div>
                <a href="{{ route('food-location-create') }}" class="create">
                    <span>Add Food Location</span>
                </a>
            </div>

            <!-- Card -->
            <div class="custom-card1">
                <div class="container">
                    <div class="header">
                        <i class='bx bx-detail'></i>
                        <h3>Location Wise Price</h3>
                        <i class='bx bx-filter'></i>

                        <form action="" method="GET" id="search-form">
                            <div class="search-field">
                                <i class='bx bx-search' id="search-button"></i>
                                <input type="text" name="search" placeholder="Search" value="{{ request('search') }}">
                            </div>
                        </form>
                    </div>

                    <!-- Table -->
                    <div class="table-wrapper">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Food Name</th>
                                    <th>Location</th>
                                    <th>Price</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($food as $index => $item)
                                    <tr>
                                        <td>{{ $food->firstItem() + $index }}</td>
                                        <td>{{ $item->foodMenu->name ?? 'N/A' }}</td>
                                        <td>{{ $item->location->location_name ?? 'N/A' }}</td>
                                        <td>{{ number_format($item->price, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No Food Locations Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination">
                        <div class="count">
                            Showing {{ $food->firstItem() }} to {{ $food->lastItem() }} out of {{ $food->total() }} results
                        </div>

                        <div class="pagination-number">
                            <div class="page-number">
                                {{ $food->render('partials.paginator') }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </main>
    </section>
</div>

@endsection



@section('scripts')
<!-- ✅ jQuery & DataTables -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#foodTable').DataTable({
            paging: false,       // disable DataTable pagination (Laravel handles it)
            ordering: true,      // enable column sorting
            info: false,         // hide info text
            searching: true,     // enable search
            columnDefs: [
                { orderable: false, targets: 0 } // disable sorting on first column
            ],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search food or location..."
            }
        });
    });
</script>
@endsection
