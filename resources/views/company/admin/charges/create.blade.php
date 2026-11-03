@extends('company.admin.main')

@section('title', 'Create Charges')

@section('content')

<style>
    .create-wrapper {
        max-width: 900px;
        margin: 0 auto;
        padding: 30px;
    }

    .create-header {
        margin-bottom: 25px;
    }

    .create-header h1 {
        font-size: 26px;
        font-weight: 600;
        color: #000;
    }

    .create-header p {
        margin-top: 6px;
        font-size: 14px;
        color: #444;
    }

    .message-box {
        border: 1px solid #000;
        padding: 14px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .form-card {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .form-card-header {
        padding: 18px 25px;
        border-bottom: 1px solid #e5e5e5;
    }

    .form-card-header h2 {
        font-size: 20px;
        font-weight: 600;
    }

    .form-card-body {
        padding: 25px;
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
    }

    .form-group input,
    .form-group select {
        padding: 9px 10px;
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

    .error-text {
        margin-top: 6px;
        font-size: 12px;
        color: #000;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e5e5e5;
    }

    .action-buttons button,
    .action-buttons a {
        padding: 9px 22px;
        font-size: 14px;
        border-radius: 6px;
        border: 1px solid #000;
        background: #fff;
        cursor: pointer;
        text-decoration: none;
        color: #000;
        transition: background 0.2s ease;
    }

    .action-buttons button:hover,
    .action-buttons a:hover {
        background: #f5f5f5;
    }

    @media(max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="create-wrapper">

    <div class="create-header">
        <h1>Create Charges</h1>
        <p>Add a new charge or discount (Tax, Delivery Fee, Service Charge)</p>
    </div>

    {{-- Success / Error Messages --}}
    @if (session('success-message'))
        <div class="message-box">
            <strong>Success:</strong> {{ session('success-message') }}
        </div>
    @endif

    @error('error-message')
        <div class="message-box">
            <strong>Error:</strong> {{ $message }}
        </div>
    @enderror

    <div class="form-card">

        <div class="form-card-header">
            <h2>Charge Details</h2>
        </div>

        <div class="form-card-body">
            <form action="{{ route('charges.store') }}" method="POST">
                @csrf

                <div class="form-grid">

                    {{-- Name --}}
                    <div class="form-group">
                        <label>Name *</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="GST Tax, Delivery Fee, Service Charge"
                               required>
                        @error('name')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Type --}}
                    <div class="form-group">
                        <label>Type *</label>
                        <select name="type" id="typeSelect" required onchange="updateAmountPlaceholder()">
                            <option value="">Select Type</option>
                            <option value="1" {{ old('type') == 1 ? 'selected' : '' }}>
                                Fixed Amount
                            </option>
                            <option value="2" {{ old('type') == 2 ? 'selected' : '' }}>
                                Percentage
                            </option>
                        </select>
                        @error('type')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Amount --}}
                    <div class="form-group">
                        <label>Amount *</label>
                        <input type="number"
                               name="amount"
                               id="amountInput"
                               step="0.01"
                               value="{{ old('amount') }}"
                               required>
                        <small id="amountHelp">Enter amount based on selected type</small>
                        @error('amount')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Location --}}
                    <div class="form-group">
                        <label>Location *</label>
                        <select name="location_id" required>
                            <option value="">Select Location</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->location_id }}"
                                    {{ old('location_id') == $location->location_id ? 'selected' : '' }}>
                                    {{ $location->location_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('location_id')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div class="action-buttons">
                    <button type="submit">Create Charge</button>
                    <a href="{{ route('charges.index') }}">Cancel</a>
                </div>

            </form>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
function updateAmountPlaceholder() {
    const typeSelect = document.getElementById('typeSelect');
    const amountInput = document.getElementById('amountInput');
    const amountHelp = document.getElementById('amountHelp');

    if (typeSelect.value === '1') {
        amountInput.placeholder = "e.g., 50.00 or -10.00";
        amountHelp.textContent = "Fixed amount: Positive for charges, negative for discounts";
    } else if (typeSelect.value === '2') {
        amountInput.placeholder = "e.g., 18.00";
        amountHelp.textContent = "Percentage: Enter value without % sign";
    } else {
        amountInput.placeholder = "Enter amount";
        amountHelp.textContent = "Enter amount based on selected type";
    }
}

document.addEventListener('DOMContentLoaded', updateAmountPlaceholder);
</script>
@endsection
