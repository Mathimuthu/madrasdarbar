@extends('company.admin.main')
@section('title', 'Locations')
@section('content')

<div class="food-menu-index">
    <section>
        <main>

            @if (session('success-message'))
                <div class="success-message left-green">
                    <i class='bx bxs-check-circle'></i>
                    <div class="text">
                        <span>Success</span>
                        <span class="message">{!! session('success-message') !!}</span>
                    </div>
                </div>
            @endif

            @if (session('error-message'))
                <div class="error-message left-red">
                    <i class='bx bxs-x-circle'></i>
                    <div class="text">
                        <span>Error</span>
                        <span class="message">{{ session('error-message') }}</span>
                    </div>
                </div>
            @endif

            <!-- Header -->
            <div class="header">
                <div class="left">
                    <h1>Locations</h1>
                </div>
                <a href="{{ route('admin-location-create') }}" class="create">
                    <span>Add Location</span>
                </a>
            </div>

            <!-- Card -->
            <div class="custom-card1">
                <div class="container">
                    <div class="header">
                        <i class='bx bx-map-alt'></i>
                        <h3>All Locations</h3>
                        <i class='bx bx-filter'></i>

                        <form action="{{ route('admin-location-search') }}" method="GET" id="search-form">
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
                                    <th>Location Name</th>
                                    <th>Country</th>
                                    <th>Currency</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($locations as $index => $location)
                                    <tr>
                                        <td>{{ $locations->firstItem() + $index }}</td>
                                        <td>{{ $location->location_name }}</td>
                                        <td>{{ $location->country ?? '—' }}</td>
                                        <td>{{ $location->currency ?? '—' }}</td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('admin-location-edit', $location->location_id) }}"
                                                   class="action-btn edit-btn" title="Edit">
                                                    <i class='bx bx-edit'></i>
                                                </a>
                                                <button type="button"
                                                        class="action-btn delete-btn delete-trigger"
                                                        data-id="{{ $location->location_id }}"
                                                        data-name="{{ $location->location_name }}"
                                                        title="Delete">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            </div>

                                            <!-- Hidden delete form -->
                                            <form id="delete-form-{{ $location->location_id }}"
                                                  action="{{ route('admin-location.destroy', $location->location_id) }}"
                                                  method="POST" style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No Locations Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination">
                        <div class="count">
                            Showing {{ $locations->firstItem() ?? 0 }} to {{ $locations->lastItem() ?? 0 }} out of {{ $locations->total() }} results
                        </div>
                        <div class="pagination-number">
                            <div class="page-number">
                                {{ $locations->render('partials.paginator') }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </main>
    </section>
</div>

<!-- Delete Confirmation Popup -->
<div class="delete-confirmation" id="deletePopup" style="display:none;">
    <i class='bx bxs-info-circle'></i>
    <h1>Warning</h1>
    <h3>Delete location "<span id="popup-location-name"></span>"?</h3>
    <p>This will affect all food mappings and staff accounts linked to this location. This action cannot be undone!</p>
    <div class="button">
        <button class="close-popup" id="cancelDelete">Cancel</button>
        <button class="confirm-delete" id="confirmDelete">Delete</button>
    </div>
</div>

<style>
    .action-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 16px;
        text-decoration: none;
        transition: background 0.2s ease;
    }

    .edit-btn {
        background: #e0f2fe;
        color: #0284c7;
    }

    .edit-btn:hover {
        background: #bae6fd;
    }

    .delete-btn {
        background: #fee2e2;
        color: #dc2626;
    }

    .delete-btn:hover {
        background: #fecaca;
    }
</style>

@endsection

@section('scripts')
<script>
    let pendingDeleteId = null;

    document.querySelectorAll('.delete-trigger').forEach(btn => {
        btn.addEventListener('click', function () {
            pendingDeleteId = this.dataset.id;
            document.getElementById('popup-location-name').textContent = this.dataset.name;
            document.getElementById('deletePopup').style.display = 'flex';
        });
    });

    document.getElementById('cancelDelete').addEventListener('click', function () {
        document.getElementById('deletePopup').style.display = 'none';
        pendingDeleteId = null;
    });

    document.getElementById('confirmDelete').addEventListener('click', function () {
        if (pendingDeleteId) {
            document.getElementById('delete-form-' + pendingDeleteId).submit();
        }
    });
</script>
@endsection
