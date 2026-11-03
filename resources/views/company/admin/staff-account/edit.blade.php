@extends('company.admin.main')

@section('title', 'Edit')

@section('content')

    <div class="staff-account-edit">

        <section>

            <main>

                @error('error-message')
                    <div class="error-message left-red">
                        <i class='bx bxs-x-circle'></i>
                        <div class="text">
                            <span>Error</span>
                            <span class="message">{{ $message }}</span>
                        </div>
                    </div>
                @enderror
                
                <div class="header">
                    <div class="left">
                        <h1>Edit Staff Details</h1>
                    </div>
                </div>

                <div class="edit-section">

                    <div class="user-header">
                        <h3>Edit Profile <span class="user-name">{{ $user->name }}</span></h3>
                    </div>

                    <div class="form-staff-update">

                        <form action="{{ route('staff-account.update', $user->id) }}" method="POST">

                            @method('PUT')
                            @csrf

                            <div class="form-group">
                                <label for="staff_id">ID :</label>
                                <input type="text" id="staff_id" value="{{ $user->staff_id }}" disabled class="disabled-field">
                            </div>

                            <div class="form-group">
                                <label for="name">Name :</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" placeholder="Enter name">
                            </div>

                            <div class="form-group">
                                <label for="email">Email :</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Enter email">
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone No :</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Enter phone number">
                            </div>

                            <div class="form-group">
                                <label for="location_id">Location :</label>
                                <select id="location_id" name="location_id">
                                    <option value="">Select Location</option>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->location_id }}"
                                            {{ old('location_id', $user->location_id) == $location->location_id ? 'selected' : '' }}>
                                            {{ $location->location_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="role_id">Role :</label>
                                <select id="role_id" name="role_id">
                                    <option value="1" {{ old('role_id', $user->role) == 1 ? 'selected' : '' }}>Super Admin</option>
                                    <option value="2" {{ old('role_id', $user->role) == 2 ? 'selected' : '' }}>Admin</option>
                                    <option value="3" {{ old('role_id', $user->role) == 3 ? 'selected' : '' }}>Staff</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="position">Position :</label>
                                <input type="text" id="position" name="position" value="{{ old('position', $user->position) }}" placeholder="Enter position">
                            </div>

                            <div class="form-group">
                                <label for="address">Address :</label>
                                <textarea id="address" name="address" rows="3" placeholder="Enter address">{{ old('address', $user->address) }}</textarea>
                            </div>

                            <!-- Password Reset Section -->
                            <div class="password-section">
                                <div class="password-toggle-header" id="togglePasswordSection">
                                    <i class='bx bx-lock-alt'></i>
                                    <span>Change Password</span>
                                    <i class='bx bx-chevron-down toggle-icon'></i>
                                </div>

                                <div class="password-fields" id="passwordFields" style="display:none;">
                                    <p class="password-hint">Leave blank to keep the current password.</p>

                                    <div class="form-group">
                                        <label for="password">New Password :</label>
                                        <div class="password-input-wrap">
                                            <input type="password" id="password" name="password"
                                                   placeholder="Enter new password (min 6 chars)"
                                                   autocomplete="new-password">
                                            <button type="button" class="toggle-pw" data-target="password">
                                                <i class='bx bx-hide'></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <span class="field-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="password_confirmation">Confirm Password :</label>
                                        <div class="password-input-wrap">
                                            <input type="password" id="password_confirmation" name="password_confirmation"
                                                   placeholder="Re-enter new password"
                                                   autocomplete="new-password">
                                            <button type="button" class="toggle-pw" data-target="password_confirmation">
                                                <i class='bx bx-hide'></i>
                                            </button>
                                        </div>
                                        <span class="field-error" id="pw-match-error" style="display:none;">
                                            Passwords do not match.
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="button-section">
                                <button type="submit" class="btn-update" id="submitBtn">Update Profile</button>
                                <a href="{{ route('staff-account-show', ['staff_account' => $user->id]) }}" class="btn-cancel">Cancel</a>
                            </div>

                        </form>

                    </div>

                </div>

            </main>

        </section>

    </div>

    <style>
        .staff-account-edit {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .edit-section {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .user-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .user-header h3 {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .user-name {
            color: #007bff;
            font-weight: 600;
        }

        .form-staff-update form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: grid;
            grid-template-columns: 150px 1fr;
            align-items: start;
            gap: 20px;
        }

        .form-group label {
            font-size: 15px;
            font-weight: 500;
            color: #555;
            padding-top: 12px;
            text-align: left;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
            color: #333;
            background: #fff;
            transition: all 0.3s ease;
            font-family: inherit;
            box-sizing: border-box;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-group select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            padding-right: 40px;
        }

        .disabled-field {
            background-color: #f5f5f5 !important;
            color: #999 !important;
            cursor: not-allowed;
        }

        .button-section {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
        }

        .btn-update,
        .btn-cancel {
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-update {
            background: #007bff;
            color: #fff;
        }

        .btn-update:hover {
            background: #0056b3;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .btn-cancel {
            background: #dc3545;
            color: #fff;
        }

        .btn-cancel:hover {
            background: #c82333;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }

        .error-message {
            background: #fee;
            border-left: 4px solid #dc3545;
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .error-message i {
            color: #dc3545;
            font-size: 24px;
        }

        .error-message .text span {
            display: block;
        }

        .error-message .text span:first-child {
            font-weight: 600;
            color: #dc3545;
            margin-bottom: 5px;
        }

        .error-message .message {
            color: #666;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-group {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .form-group label {
                padding-top: 0;
            }

            .button-section {
                flex-direction: column;
            }

            .btn-update,
            .btn-cancel {
                width: 100%;
            }
        }

        /* ── Password Section ── */
        .password-section {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }

        .password-toggle-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 20px;
            background: #f8fafc;
            cursor: pointer;
            user-select: none;
            font-weight: 600;
            color: #374151;
            font-size: 15px;
            border-bottom: 1px solid transparent;
            transition: background 0.2s ease;
        }

        .password-toggle-header:hover {
            background: #f1f5f9;
        }

        .password-toggle-header i:first-child {
            font-size: 18px;
            color: #6366f1;
        }

        .password-toggle-header span {
            flex: 1;
        }

        .toggle-icon {
            font-size: 20px;
            color: #6b7280;
            transition: transform 0.3s ease;
        }

        .toggle-icon.open {
            transform: rotate(180deg);
        }

        .password-fields {
            padding: 20px;
            border-top: 1px solid #e2e8f0;
            background: #fff;
        }

        .password-hint {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .password-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-input-wrap input {
            padding-right: 46px !important;
        }

        .toggle-pw {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            color: #6b7280;
            font-size: 18px;
            display: flex;
            align-items: center;
            transition: color 0.2s ease;
        }

        .toggle-pw:hover { color: #374151; }

        .field-error {
            display: block;
            color: #dc2626;
            font-size: 12px;
            margin-top: 4px;
        }
    </style>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Accordion toggle ──
    const toggleHeader = document.getElementById('togglePasswordSection');
    const passwordFields = document.getElementById('passwordFields');
    const toggleIcon = toggleHeader ? toggleHeader.querySelector('.toggle-icon') : null;

    if (toggleHeader && passwordFields) {
        toggleHeader.addEventListener('click', function () {
            const isOpen = passwordFields.style.display !== 'none';
            passwordFields.style.display = isOpen ? 'none' : 'block';
            if (toggleIcon) toggleIcon.classList.toggle('open', !isOpen);
            // Clear fields when collapsing
            if (isOpen) {
                const pw = document.getElementById('password');
                const cpw = document.getElementById('password_confirmation');
                const err = document.getElementById('pw-match-error');
                if (pw) pw.value = '';
                if (cpw) cpw.value = '';
                if (err) err.style.display = 'none';
            }
        });
    }

    // Auto-open if there are password validation errors on return
    @if($errors->has('password'))
        if (passwordFields && toggleIcon) {
            passwordFields.style.display = 'block';
            toggleIcon.classList.add('open');
        }
    @endif

    // ── Show / hide password toggle ──
    document.querySelectorAll('.toggle-pw').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            if (!input || !icon) return;
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bx-hide', 'bx-show');
            } else {
                input.type = 'password';
                icon.classList.replace('bx-show', 'bx-hide');
            }
        });
    });

    // ── Client-side password match check on submit ──
    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn) {
        submitBtn.addEventListener('click', function (e) {
            const pw  = document.getElementById('password');
            const cpw = document.getElementById('password_confirmation');
            const err = document.getElementById('pw-match-error');

            if (pw && cpw && pw.value !== '' && pw.value !== cpw.value) {
                e.preventDefault();
                if (err) err.style.display = 'block';
                if (passwordFields) passwordFields.style.display = 'block';
                if (toggleIcon) toggleIcon.classList.add('open');
            } else {
                if (err) err.style.display = 'none';
            }
        });
    }

});
</script>