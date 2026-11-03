@extends('company.admin.main')
@section('title', 'Upload New Banner')

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
                    <h1>Upload New Banner</h1>
                </div>
                <a href="{{ route('banners.index') }}" class="create">
                    <span>← Back to Banners</span>
                </a>
            </div>

            <div class="index-section">
                <div class="container" style="max-width:700px;">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-5">

                            <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="text-center mb-4">
                                    <i class='bx bxs-image-alt' style="font-size:70px;color:#ddd;"></i>
                                    <p class="text-muted">Max 2MB • JPG, PNG, WEBP, GIF</p>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Select Banner Image *</label>
                                    <input type="file" name="image" accept="image/*" required
                                           class="form-control form-control-lg @error('image') is-invalid @enderror">
                                    @error('image')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="text-end">
                                    <a href="{{ route('banners.index') }}" class="btn btn-secondary px-4">Cancel</a>
                                    <button type="submit" class="btn btn-primary px-5">Upload Banner</button>
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