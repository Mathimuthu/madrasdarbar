@extends('company.admin.main')

@section('title', 'Edit Charge')

@section('content')

<style>
    .edit-wrapper {
        max-width: 900px;
        margin: 0 auto;
        padding: 30px;
    }

    .edit-header {
        margin-bottom: 25px;
    }

    .edit-header h1 {
        font-size: 26px;
        font-weight: 600;
        color: #000;
    }

    .edit-card {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }

    .edit-card h3 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 20px;
        border-bottom: 1px solid #e5e5e5;
        padding-bottom: 10px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px 40px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        font-size: 14px;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 6px;
        color: #111;
    }

    .form-group input,
    .form-group select {
        padding: 8px 10px;
        border-radius: 6px;
        border: 1px solid #bbb;
        background: #fff;
        font-size: 14px;
    }

    .form-group small {
        margin-top: 6px;
        font-size: 12px;
        color: #444;
    }

    .action-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 30px;
    }

    .action-buttons input,
    .action-buttons a {
        padding: 8px 22px;
        border-radius: 6px;
        font-size: 14px;
        background: #fff;
        border: 1px solid #000;
        cursor: pointer;
        text-decoration: none;
        color: #000;
        transition: background 0.2s ease;
    }

    .action-buttons input:hover,
    .action-buttons a:hover {
        background: #f5f5f5;
    }

    @media(max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="edit-wrapper">

    @error('error-message')
        <div style="border:1px solid #000;padding:12px;margin-bottom:20px;">
            <strong>Error:</strong> {{ $message }}
        </div>
    @enderror

    <div class="edit-header">
        <h1>Edit Charges</h1>
    </div>

    <div class="edit-card">

        <h3>Edit Charge Details</h3>

        <form action="{{ route('charges.update',$charge->id) }}" method="POST">
            @method('PUT')
            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" value="{{ old('name', $charge->name) }}" required>
                </div>

                <div class="form-group">
                    <label>Type</label>
                    <select name="type" id="typeSelect" required onchange="updateAmountPlaceholder()">
                        <option value="1" {{ old('type', $charge->type) == 1 ? 'selected' : '' }}>Fixed Amount</option>
                        <option value="2" {{ old('type', $charge->type) == 2 ? 'selected' : '' }}>Percentage</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Amount</label>
                    <input type="number" name="amount" id="amountInput"
                           value="{{ old('amount', $charge->amount) }}" step="0.01" required>
                    <small id="amountHelp"></small>
                </div>

                <div class="form-group">
                    <label>Location</label>
                    <select name="location_id" required>
                        <option value="">Select Location</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location->location_id }}"
                                {{ old('location_id', $charge->location_id) == $location->location_id ? 'selected' : '' }}>
                                {{ $location->location_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="1" {{ old('status', $charge->status) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $charge->status) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

            </div>

            <div class="action-buttons">
                <input type="submit" value="Update Charges">
                <a href="{{ route('charges.show',$charge->id) }}">Cancel</a>
            </div>

        </form>

    </div>

</div>

@endsection

@section('scripts')
<script>
function updateAmountPlaceholder() {
    const typeSelect = document.getElementById('typeSelect');
    const amountHelp = document.getElementById('amountHelp');

    if (typeSelect.value === '1') {
        amountHelp.textContent = "Fixed amount: Positive for charges, negative for discounts";
    } else {
        amountHelp.textContent = "Percentage: Enter value without % sign (e.g., 18.00 for 18%)";
    }
}

document.addEventListener('DOMContentLoaded', updateAmountPlaceholder);
</script>
@endsection
