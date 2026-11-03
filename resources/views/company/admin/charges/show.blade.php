@extends('company.admin.main')

@section('title', 'Show Charge')

@section('content')

<style>
    .charge-wrapper {
        max-width: 900px;
        margin: 0 auto;
        padding: 30px;
    }

    .charge-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .charge-header h1 {
        font-size: 26px;
        font-weight: 600;
        color: #000;
    }

    .charge-card {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }

    .charge-card h3 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 20px;
        border-bottom: 1px solid #e5e5e5;
        padding-bottom: 10px;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px 40px;
    }

    .detail-item {
        font-size: 15px;
        line-height: 1.6;
    }

    .detail-item strong {
        display: block;
        font-weight: 600;
        margin-bottom: 3px;
        color: #111;
    }

    .detail-item span {
        color: #333;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 13px;
        border: 1px solid #000;
    }

    .status-inactive {
        border-style: dashed;
    }

    .action-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 30px;
    }

    .action-buttons a,
    .action-buttons button {
        padding: 8px 20px;
        font-size: 14px;
        border-radius: 6px;
        background: #fff;
        border: 1px solid #000;
        cursor: pointer;
        text-decoration: none;
        color: #000;
        transition: all 0.2s ease;
    }

    .action-buttons a:hover,
    .action-buttons button:hover {
        background: #f5f5f5;
    }

    @media(max-width: 768px) {
        .details-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="charge-wrapper">

    <div class="charge-header">
        <h1>Show Charges</h1>
    </div>

    <div class="charge-card">

        <h3>Charge Information</h3>

        <div class="details-grid">

            <div class="detail-item">
                <strong>Title</strong>
                <span>{{ $charge->name }}</span>
            </div>

            <div class="detail-item">
                <strong>Amount</strong>
                <span>
                    @if($charge->type == 1)
                        {{ number_format($charge->amount, 2) }}
                    @else
                        {{ number_format($charge->amount, 2) }}%
                    @endif
                </span>
            </div>

            <div class="detail-item">
                <strong>Type</strong>
                <span>
                    {{ $charge->type == 1 ? 'Fixed Amount' : 'Percentage' }}
                </span>
            </div>

            <div class="detail-item">
                <strong>Location</strong>
                <span>{{ $charge->location->location_name ?? 'N/A' }}</span>
            </div>

            <div class="detail-item">
                <strong>Status</strong>
                @if($charge->status == 1)
                    <span class="status-badge">Active</span>
                @else
                    <span class="status-badge status-inactive">Inactive</span>
                @endif
            </div>

            <div class="detail-item">
                <strong>Created At</strong>
                <span>{{ $charge->created_at->format('Y-m-d H:i:s') }}</span>
            </div>

            <div class="detail-item">
                <strong>Updated At</strong>
                <span>{{ $charge->updated_at->format('Y-m-d H:i:s') }}</span>
            </div>

        </div>

        <form action="{{ route('charges.destroy', $charge->id) }}"
      method="POST"
      onsubmit="return confirm('Are you sure you want to delete this charge?');">
    @csrf
    @method('DELETE')

    <div class="action-buttons">
        <a href="{{ route('charges.edit', $charge->id) }}">Edit</a>

        <button type="submit">Delete</button>

        <a href="{{ route('charges.index') }}">Cancel</a>
    </div>
</form>


    </div>

</div>

@endsection
