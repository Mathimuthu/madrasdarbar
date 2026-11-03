@extends('company.admin.main')

@section('title', 'Create Staff Account')

<style>
    :root {
        --primary: #4F46E5;
        --primary-dark: #4338CA;
        --success: #10B981;
        --error: #EF4444;
        --gray-50: #F9FAFB;
        --gray-100: #F3F4F6;
        --gray-200: #E5E7EB;
        --gray-300: #D1D5DB;
        --gray-600: #4B5563;
        --gray-700: #374151;
        --gray-900: #111827;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }


html, body {
    background: var(--gray-50) !important;
    min-height: 100% !important;
    height: auto !important;
}

.staff-account-create {
    background: var(--gray-50) !important;
    min-height: 100% !important;
    height: auto !important;
    padding: 2rem !important;
}

/* Fix main container background (from your master layout) */
main {
    background: transparent !important;
}

    /* Alert Messages */
    .alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success {
        background: #ECFDF5;
        border: 1px solid #A7F3D0;
        color: #065F46;
    }

    .alert-error {
        background: #FEF2F2;
        border: 1px solid #FECACA;
        color: #991B1B;
    }

    .alert i {
        font-size: 24px;
    }

    .alert-text {
        flex: 1;
    }

    .alert-text strong {
        display: block;
        font-weight: 600;
        margin-bottom: 2px;
    }

    /* Page Header */
    .page-header {
        background: white;
        padding: 24px 32px;
        border-radius: 16px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
    }

    .page-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: var(--gray-900);
        margin: 0;
    }

    .page-header p {
        color: var(--gray-600);
        margin: 8px 0 0 0;
    }

    /* Form Card */
    .form-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-sm);
        padding: 32px;
        margin-bottom: 24px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--gray-900);
        margin: 0 0 24px 0;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--gray-100);
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width {
        grid-column: span 2;
    }

    .form-label {
        font-size: 14px;
        font-weight: 500;
        color: var(--gray-700);
        margin-bottom: 8px;
    }

    .required {
        color: var(--error);
        margin-left: 2px;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 12px 16px;
        font-size: 15px;
        border: 1.5px solid var(--gray-300);
        border-radius: 10px;
        background: white;
        color: var(--gray-900);
        transition: all 0.2s;
        font-family: inherit;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%234B5563' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 18px;
        padding-right: 40px;
        cursor: pointer;
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .validation-error {
        color: var(--error);
        font-size: 13px;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Action Buttons */
    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 32px;
        grid-column: span 2;
    }

    .btn {
        padding: 12px 24px;
        font-size: 15px;
        font-weight: 600;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .btn-secondary {
        background: var(--gray-100);
        color: var(--gray-700);
    }

    .btn-secondary:hover {
        background: var(--gray-200);
    }

    /* Table Section */
    .table-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .table-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--gray-200);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .table-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .table-header-left i {
        font-size: 24px;
        color: var(--primary);
    }

    .table-header-left h3 {
        font-size: 18px;
        font-weight: 600;
        color: var(--gray-900);
        margin: 0;
    }

    .search-box {
        position: relative;
        width: 280px;
    }

    .search-box input {
        width: 100%;
        padding: 10px 16px 10px 40px;
        border: 1.5px solid var(--gray-300);
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.2s;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .search-box i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-600);
        font-size: 18px;
    }

    /* Table */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: var(--gray-50);
    }

    th {
        padding: 14px 24px;
        text-align: left;
        font-size: 13px;
        font-weight: 600;
        color: var(--gray-700);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    td {
        padding: 16px 24px;
        color: var(--gray-900);
        font-size: 14px;
        border-top: 1px solid var(--gray-200);
    }

    tbody tr {
        transition: background 0.15s;
    }

    tbody tr:hover {
        background: var(--gray-50);
    }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
    }

    .badge-primary {
        background: #EEF2FF;
        color: var(--primary);
    }

    .btn-delete {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        background: #FEF2F2;
        color: var(--error);
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-delete:hover {
        background: #FEE2E2;
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 20px 24px;
        border-top: 1px solid var(--gray-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .pagination-info {
        color: var(--gray-600);
        font-size: 14px;
    }

    /* Custom Pagination Styling */
    .pagination-number nav {
        display: flex;
        gap: 4px;
    }

    .pagination-number .pagination {
        display: flex;
        gap: 4px;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .pagination-number .page-item {
        display: inline-block;
    }

    .pagination-number .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 8px 12px;
        font-size: 14px;
        font-weight: 500;
        color: var(--gray-700);
        background: white;
        border: 1.5px solid var(--gray-300);
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .pagination-number .page-link:hover {
        background: var(--gray-50);
        border-color: var(--gray-400);
        color: var(--gray-900);
    }

    .pagination-number .page-item.active .page-link {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
        font-weight: 600;
    }

    .pagination-number .page-item.disabled .page-link {
        color: var(--gray-400);
        background: var(--gray-100);
        border-color: var(--gray-200);
        cursor: not-allowed;
        pointer-events: none;
    }

    .pagination-number .page-link svg {
        width: 16px;
        height: 16px;
    }

    /* Mobile Cards (hidden on desktop) */
    .mobile-cards {
        display: none;
    }

    .staff-card {
        padding: 16px;
        border-bottom: 1px solid var(--gray-200);
    }

    .staff-card:last-child {
        border-bottom: none;
    }

    .staff-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .staff-card-body {
        display: flex;
        flex-direction: column;
        gap: 8px;
        color: var(--gray-600);
        font-size: 14px;
    }

    .staff-card-row {
        display: flex;
        justify-content: space-between;
    }

    .staff-card-label {
        font-weight: 500;
        color: var(--gray-700);
    }

    /* Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal {
        background: white;
        border-radius: 16px;
        padding: 32px;
        max-width: 480px;
        width: 90%;
        box-shadow: var(--shadow-lg);
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .modal-icon {
        width: 56px;
        height: 56px;
        background: #FEF2F2;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .modal-icon i {
        font-size: 28px;
        color: var(--error);
    }

    .modal h2 {
        font-size: 22px;
        font-weight: 700;
        color: var(--gray-900);
        text-align: center;
        margin: 0 0 8px 0;
    }

    .modal p {
        color: var(--gray-600);
        text-align: center;
        margin: 0 0 24px 0;
        line-height: 1.5;
    }

    .modal-actions {
        display: flex;
        gap: 12px;
    }

    .modal-actions button {
        flex: 1;
    }

    @media (max-width: 768px) {
        .staff-account-create {
            padding: 1rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: span 1;
        }

        .form-actions {
            grid-column: span 1;
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .table-header {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box {
            width: 100%;
        }

        .pagination-wrapper {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }

        .pagination-info {
            text-align: center;
        }

        .pagination-number {
            display: flex;
            justify-content: center;
        }

        .pagination-number .page-link {
            min-width: 36px;
            height: 36px;
            padding: 6px 10px;
            font-size: 13px;
        }

        /* Hide table on mobile, show cards instead */
        table {
            display: none;
        }

        .mobile-cards {
            display: block;
        }
    }
</style>

@section('content')
<div class="staff-account-create">
    <section>
        <main>
            @if (session('success-message'))
                <div class="alert alert-success">
                    <i class='bx bxs-check-circle'></i>
                    <div class="alert-text">
                        <strong>Success</strong>
                    </div>
                </div>
            @endif

            @error('error-message')
                <div class="alert alert-error">
                    <i class='bx bxs-x-circle'></i>
                    <div class="alert-text">
                        <strong>Error</strong>
                        <span>{{ $message }}</span>
                    </div>
                </div>
            @enderror

            <div class="page-header">
                <h1>Create New Staff Account</h1>
                <p>Add a new staff member to your organization</p>
            </div>

            <form action="{{ route('staff-account.store') }}" method="POST">
                @csrf

                <div class="form-card">
                    <h2 class="section-title">Staff Login Information</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Staff ID <span class="required">*</span></label>
                            <input type="text" name="staff_id" value="{{ old('staff_id') }}" 
                                   class="form-input" placeholder="e.g. ST20250001" required>
                            @error('staff_id')
                                <span class="validation-error">
                                    <i class='bx bx-error-circle'></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Full Name <span class="required">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" 
                                   class="form-input" placeholder="John Doe" required>
                            @error('name')
                                <span class="validation-error">
                                    <i class='bx bx-error-circle'></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email Address <span class="">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" 
                                   class="form-input" placeholder="john@example.com" >
                            @error('email')
                                <span class="validation-error">
                                    <i class='bx bx-error-circle'></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Phone Number <span class="">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" 
                                   class="form-input" placeholder="+1 (555) 000-0000" >
                            @error('phone')
                                <span class="validation-error">
                                    <i class='bx bx-error-circle'></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Role <span class="required">*</span></label>
                            <select name="role_id" class="form-select" required>
                                <option value="">Select Role</option>
                                <option value="1" {{ old('role_id') == 1 ? 'selected' : '' }}>Super Admin</option>
                                <option value="2" {{ old('role_id') == 2 ? 'selected' : '' }}>Admin</option>
                                <option value="3" {{ old('role_id') == 3 ? 'selected' : '' }}>Staff</option>
                            </select>

                            @error('role_id')
                                <span class="validation-error">
                                    <i class='bx bx-error-circle'></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <h2 class="section-title">Additional Information</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Location <span class="required">*</span></label>
                            <select name="location_id" class="form-select" required>
                                <option value="">Choose Location</option>
                                @foreach ($locations as $loc)
                                    <option value="{{ $loc->location_id }}" 
                                            {{ old('location_id') == $loc->location_id ? 'selected' : '' }}>
                                        {{ $loc->location_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('location_id')
                                <span class="validation-error">
                                    <i class='bx bx-error-circle'></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Gender <span class="">*</span></label>
                            <select name="gender" class="form-select">
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <span class="validation-error">
                                    <i class='bx bx-error-circle'></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Position</label>
                            <input type="text" name="position" value="{{ old('position') }}" 
                                   class="form-input" placeholder="e.g. Delivery Rider">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-textarea" 
                                      placeholder="Home address">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <h2 class="section-title">Security Settings</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Password <span class="required">*</span></label>
                            <input type="password" name="password" class="form-input" 
                                   placeholder="••••••••" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Confirm Password <span class="required">*</span></label>
                            <input type="password" name="password_confirmation" 
                                   class="form-input" placeholder="••••••••" required>
                        </div>

                        @error('password')
                            <div class="form-group full-width">
                                <span class="validation-error">
                                    <i class='bx bx-error-circle'></i>
                                    {{ $message }}
                                </span>
                            </div>
                        @enderror

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-plus-circle'></i>
                                Create Staff Account
                            </button>
                            <a href="{{ route('staff-account') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-card">
                <div class="table-header">
                    <div class="table-header-left">
                        <i class='bx bx-receipt'></i>
                        <h3>Registered Staff IDs</h3>
                    </div>
                    <form action="{{ route('staff-account-search-create') }}" method="GET">
                        <div class="search-box">
                            <i class='bx bx-search'></i>
                            <input type="text" name="search" placeholder="Search staff..." 
                                   value="{{ old('search') }}">
                        </div>
                    </form>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Staff ID</th>
                            <th>Location</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($staffid as $id)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge badge-primary">
                                        {{ $id->staff_account_id }}
                                    </span>
                                </td>
                                <td>{{ @$id->location->location_name }}</td>
                                <td>{{ $id->created_at->format('M d, Y') }}</td>
                                <td>
                                    <form action="{{ route('staff-account-id.destroy', $id->staff_account_id) }}" method="POST" class="delete-form">
                                        @method('DELETE')
                                        @csrf
                                        <button type="button" class="btn-delete delete-trigger">
                                            <i class='bx bxs-trash-alt'></i>
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Mobile Card View -->
                <div class="mobile-cards">
                    @foreach ($staffid as $id)
                        <div class="staff-card">
                            <div class="staff-card-header">
                                <span class="badge badge-primary">{{ $id->staff_account_id }}</span>
                                <form action="{{ route('staff-account-id.destroy', $id->staff_account_id) }}" method="POST" class="delete-form" style="margin: 0;">
                                    @method('DELETE')
                                    @csrf
                                    <button type="button" class="btn-delete delete-trigger">
                                        <i class='bx bxs-trash-alt'></i>
                                        Delete
                                    </button>
                                </form>
                            </div>
                            <div class="staff-card-body">
                                <div class="staff-card-row">
                                    <span class="staff-card-label">No.</span>
                                    <span>{{ $loop->iteration }}</span>
                                </div>
                                <div class="staff-card-row">
                                    <span class="staff-card-label">Location</span>
                                    <span>{{ @$id->location->location_name }}</span>
                                </div>
                                <div class="staff-card-row">
                                    <span class="staff-card-label">Created</span>
                                    <span>{{ $id->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        Showing {{ $staffid->firstItem() }} to {{ $staffid->lastItem() }} 
                        of {{ $staffid->total() }} results
                    </div>
                    <div class="pagination-number">
                        {{ $staffid->render('company.partials.paginator') }}
                    </div>
                </div>
            </div>

            <div class="modal-overlay" id="deleteModal">
                <div class="modal">
                    <div class="modal-icon">
                        <i class='bx bxs-error'></i>
                    </div>
                    <h2>Delete Staff ID</h2>
                    <p>Are you sure you want to delete this staff ID? This action cannot be undone and all associated data will be permanently removed.</p>
                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                        <button type="button" class="btn btn-primary confirm-delete" 
                                style="background: var(--error);">
                            <i class='bx bx-trash'></i>
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </section>
</div>

<script>
    // Delete confirmation modal
    const modal = document.getElementById('deleteModal');
    const deleteTriggers = document.querySelectorAll('.delete-trigger');
    const closeModalBtns = document.querySelectorAll('.close-modal');
    const confirmDeleteBtn = document.querySelector('.confirm-delete');
    let currentForm = null;

    deleteTriggers.forEach(btn => {
        btn.addEventListener('click', function() {
            currentForm = this.closest('form');
            modal.classList.add('active');
        });
    });

    closeModalBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            modal.classList.remove('active');
            currentForm = null;
        });
    });

    confirmDeleteBtn.addEventListener('click', () => {
        if (currentForm) {
            currentForm.submit();
        }
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('active');
            currentForm = null;
        }
    });

    // Auto-submit search on input
    const searchInput = document.querySelector('.search-box input');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    }
</script>
@endsection