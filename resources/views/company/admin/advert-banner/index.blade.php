{{-- resources/views/company/admin/advert-banner/index.blade.php --}}
@extends('company.admin.main')
@section('title', 'Advert Banners')

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
                    <h1>Advert Banners</h1>
                </div>
                <a href="{{ route('banners.create') }}" class="create">
                    <span>+ Add New Banner</span>
                </a>
            </div>

            <div class="index-section">
                <div class="container">

                    <div class="header">
                        <i class='bx bxs-image-alt'></i>
                        <h3>All Banners</h3>
                        <i class='bx bx-filter'></i>
                        <form action="{{ route('banners.index') }}" method="GET">
                            <div class="search-field">
                                <i class='bx bx-search'></i>
                                <input type="text" name="search" placeholder="Search banners..." value="{{ request('search') }}">
                            </div>
                        </form>
                    </div>

                    @if($banners->isEmpty())
                        <div style="text-align:center; padding:100px 20px; color:#999;">
                            <i class='bx bxs-image' style="font-size:100px; color:#eee;"></i>
                            <h4 class="mt-4">No banners uploaded yet</h4>
                            <p>Start adding beautiful banners to promote your business!</p>
                            <a href="{{ route('banners.create') }}" class="btn btn-primary mt-3">Upload First Banner</a>
                        </div>
                    @else
                        <table>
                            <thead>
                                <tr>
                                    <th><input type="checkbox"></th>
                                    <th>ID</th>
                                    <th>Preview</th>
                                    <th>File Name</th>
                                    <th>Size</th>
                                    <th>Uploaded</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($banners as $banner)
                                <tr>
                                    <td><input type="checkbox"></td>
                                    <td>#{{ $banner->id }}</td>
                                    <td>
                                        <img src="{{ asset($banner->image) }}"
                                             style="width:100px; height:60px; object-fit:cover; border-radius:6px; border:1px solid #ddd;">
                                    </td>
                                    <td>
                                        <a href="{{ asset($banner->image) }}" target="_blank" style="color:#6f42c1;">
                                            {{ \Illuminate\Support\Str::limit(basename($banner->image), 30) }}
                                        </a>
                                    </td>
                                    <td>
                                        @php
                                            $size = file_exists(public_path($banner->image)) 
                                                ? round(filesize(public_path($banner->image)) / 1024, 1) 
                                                : 0;
                                            echo $size . ' KB';
                                        @endphp
                                    </td>
                                    <td>
                                        {{ $banner->created_at->format('j M Y') }}<br>
                                        <small style="color:#777;">{{ $banner->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('banners.edit', $banner->id) }}"
                                           class="btn btn-sm btn-warning">
                                            <i class='bx bxs-pencil'></i> Edit
                                        </a>

                                        <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Delete this banner permanently?')">
                                                <i class='bx bxs-trash'></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="pagination">
                            <div class="count">
                                Showing {{ $banners->firstItem() }} to {{ $banners->lastItem() }} 
                                of {{ $banners->total() }} banners
                            </div>
                            <div class="pagination-number">
                                {{ $banners->appends(request()->query())->links('company.partials.paginator') }}
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </main>
    </section>
</div>
@endsection