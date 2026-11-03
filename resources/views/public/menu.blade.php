@extends('main')

@section('title', 'Menu')

@section('content')

<div class="menu-page">
    <section>
        <main>
            <div class="page">
                    
                {{-- Location Selection --}}
                <div class="bg-gradient bg-primary text-white py-5 mt-5">
                    <div class="container text-center">
                        <h1 class="display-4 fw-bold mb-3">Select Your Location</h1>
                        <p class="lead mb-0 fs-5">Choose your location to explore our delicious menu</p>
                    </div>
                </div>

                <div class="container py-5">
                    <div class="row g-4 justify-content-center">
                        @foreach($locations as $location)
                            <div class="col-12 col-sm-6 col-lg-3">
                                <a href="{{ route('location.menu', ['id' => $location->location_id]) }}" class="text-decoration-none">
                                    <div class="card border-0 shadow-lg h-100 overflow-hidden location-card">
                                        <div class="position-relative" style="height: 320px;">
                                            @if($location->location_name == 'Phuket')
                                                <img src="https://images.unsplash.com/photo-1589394815804-964ed0be2eb5?w=800&q=80" class="w-100 h-100 object-fit-cover" alt="Phuket">
                                            @elseif($location->location_name == 'Bangkok')
                                                <img src="https://images.unsplash.com/photo-1508009603885-50cf7c579365?w=800&q=80" class="w-100 h-100 object-fit-cover" alt="Bangkok">
                                            @elseif($location->location_name == 'Pattaya')
                                                <img src="https://images.unsplash.com/photo-1562602833-0f4ab2fc46e3?w=800&q=80" class="w-100 h-100 object-fit-cover" alt="Pattaya">
                                            @elseif($location->location_name == 'Colombo')
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/6/62/Colombo_city_skyline_at_night.png" class="w-100 h-100 object-fit-cover" alt="Colombo">
                                            @else
                                                <img src="https://images.unsplash.com/photo-1533105079780-92b9be482077?w=800&q=80" class="w-100 h-100 object-fit-cover" alt="{{ $location->location_name }}">
                                            @endif
                                            
                                            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.4); backdrop-filter: blur(2px);"></div>
                                            
                                            <div class="position-absolute top-50 start-50 translate-middle text-center w-100 px-3">
                                                <div class="bg-white bg-opacity-25 rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 70px; height: 70px;">
                                                    <i class='bx bx-map text-white' style="font-size: 2rem;"></i>
                                                </div>
                                                <h3 class="text-white fw-bold fs-2 mb-3">{{ $location->location_name }}</h3>
                                                <span class="badge bg-white bg-opacity-25 text-white px-4 py-2 fs-6 fw-semibold shadow-sm">{{ $location->currency }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </main>

        {{-- @include('public.modal.success-message') --}}

    </section>
</div>

<style>
    .location-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 20px;
    }

    .location-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 1.5rem 3rem rgba(0,0,0,0.3) !important;
    }

    .location-card img {
        transition: transform 0.5s ease;
    }

    .location-card:hover img {
        transform: scale(1.1);
    }

    .object-fit-cover {
        object-fit: cover;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection