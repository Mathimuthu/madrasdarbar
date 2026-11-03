@extends('company.admin.main')
@section('title', 'Edit Banner')

@section('content')
<div class="promotion-discount-index">
    <section>
        <main>

            @if (session('success'))
                <div class="success-message left-green">
                    <i class='bx bxs-check-circle'></i>
                    <div class="text">
                        <span>Success</span>
                        <span class="message">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="header">
                <div class="left">
                    <h1>Edit Banner #{{ $banner->id }}</h1>
                </div>
                <a href="{{ route('banners.index') }}" class="create">
                    <span>← Back</span>
                </a>
            </div>

            <div class="index-section">
                <div class="container" style="max-width:900px;">

                    <div class="card border-0 shadow-sm">
                        <img src="{{ asset($banner->image) }}" 
                             class="card-img-top" 
                             alt="Current banner"
                             style="max-height:450px; object-fit:contain; background:#f8f9fa;">

                        <div class="card-body p-5">
                            <form action="{{ route('banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Replace Image (leave empty to keep current)</label>
                                    <input type="file" name="image" accept="image/*"
                                           class="form-control form-control-lg @error('image') is-invalid @enderror">
                                    @error('image')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="text-end">
                                    <a href="{{ route('banners.index') }}" class="btn btn-secondary px-4">Cancel</a>
                                    <button type="submit" class="btn btn-success px-5">Update Banner</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </section>
</div>
@endsection