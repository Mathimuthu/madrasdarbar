@extends('main')

@section('title', 'Menu - ' . $location->location_name)

@section('content')
<script>
    window.locationCurrency = "{{ $currency ?? 'RM' }}";
</script>

@if(session('restore_cart'))
    <script>
        alert("Welcome back! We've restored your cart.");
    </script>
@endif

@if(session('pending_order'))
    <div class="alert alert-warning mt-4">
        <i class="bi bi-exclamation-triangle"></i>
        You still have an order in progress. 
        <a href="{{ route('orders.history', session('pending_order')) }}" class="alert-link">View Order</a>
    </div>
@endif

<!-- Delivery Option Modal -->
<div class="modal fade" id="deliveryOptionModal" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-0">
        <div class="delivery-modal-header">
          <div class="icon-container">
            <i class='bx bx-food-menu'></i>
          </div>
          <h3 class="modal-title-custom">Choose Your Order Type</h3>
          <p class="modal-subtitle">Select how you'd like to receive your order</p>
        </div>
        
        <div class="delivery-options-grid">
          <button class="delivery-option-card" data-option="Doorstep Delivery" data-location-id="{{ $location->location_id }}">
            <div class="option-icon">🚗</div>
            <h4>Doorstep Delivery</h4>
            <p>We'll bring it to you</p>
            <div class="option-badge">Most Popular</div>
          </button>
          
          <button class="delivery-option-card" data-option="Counter Pickup" data-location-id="{{ $location->location_id }}">
            <div class="option-icon">🏪</div>
            <h4>Counter Pickup</h4>
            <p>Pick up from restaurant</p>
            <div class="option-badge">Fast & Easy</div>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Header Section --}}
<div class="top-bar clean-header">
    <div class="location-clean">
        <span class="deliver-label">Delivering to</span>
        <span class="deliver-location">{{ $location->location_name }}</span>
    </div>
</div>

{{-- Banner Section --}}
<div class="banner-section">
    <div class="container-fluid p-0">
        <div class="banner-wrapper">
            @php
                $banner = \App\Models\Banner::latest()->first();
            @endphp

            @if($banner && $banner->image)
                <img src="{{ asset($banner->image) }}" 
                     alt="Promotion Banner" 
                     class="banner-image">
            @else
                <img src="{{ asset('images/default-banner.jpg') }}" 
                     alt="Default Banner" 
                     class="banner-image">
            @endif
        </div>
    </div>
</div>

{{-- Search Bar --}}
<div class="search-container-wrapper">
    <div class="search-box">
        <i class='bx bx-search'></i>
        <input type="text" 
               placeholder='Search for dishes or categories...' 
               class="search-input"
               id="searchInput"
               autocomplete="off"
               aria-label="Search for dishes or categories">
        <button type="button" 
                class="search-clear-btn" 
                id="clearSearch"
                aria-label="Clear search"
                style="display: none;">
            <i class='bx bx-x'></i>
        </button>
        {{-- <i class='bx bx-microphone microphone-icon' 
           title="Voice search"
           role="button"
           tabindex="0"></i> --}}
    </div>
</div>

{{-- Categories Section --}}
@if(!$categories->isEmpty())
<div class="categories-section">
    <div class="categories-container">
        <div class="categories-scroll">
            <div class="categories-list">
                <button class="category-item active" data-category="all">
                    <div class="category-icon-wrapper">🍽️</div>
                    <span>All Items</span>
                </button>
                
                @foreach($categories as $category)
                    <button class="category-item" data-category="{{ $category->id }}">
                        <div class="category-icon-wrapper">
                            @if($category->image)
                                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}">
                            @else
                                🍴
                            @endif
                        </div>
                        <span>{{ $category->name }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

{{-- Menu Section --}}
<div class="full-bg-wrapper">
    <div id="menuSection" class="menu-section">
        <div class="menu-container-wrapper">
            <div class="menu-header">
                <h2 class="menu-title" id="categoryTitle">Our Delicious Menu</h2>
            </div>
            
            @if($foodMenu->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">🍽️</div>
                    <h3>No Menu Items Available</h3>
                    <p>Check back soon for delicious options!</p>
                </div>
            @else
                <div class="menu-grid">
                    @foreach($foodMenu as $item)
                        @php
                            $categoryImage = '';
                            if($item->category_id && $categories->where('id', $item->category_id)->first()) {
                                $category = $categories->where('id', $item->category_id)->first();
                                $categoryImage = $category->image ? asset($category->image) : '';
                            }
                        @endphp
                        
                        <div class="menu-card" data-category="{{ $item->category_id ?? 'uncategorized' }}">
                            <div class="menu-card-body">
                                @if($item->category)
                                    <div class="menu-card-tag">{{ $item->category }}</div>
                                @endif
                                
                                <h3 class="menu-card-title">{{ $item->name }}</h3>
                                
                                <div class="menu-card-bottom">
                                    <div class="menu-card-price">
                                        <span class="price-value">{{ $location->currency }} {{ number_format($item->price, 2) }}</span>
                                    </div>
                                    
                                    <button class="add-btn-new" 
                                            data-food-id="{{ $item->id }}" 
                                            data-food-name="{{ $item->name }}" 
                                            data-food-price="{{ $item->price }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#itemModal{{ $item->id }}">
                                        <i class='bx bx-plus'></i>
                                        Add
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Item Detail Modal --}}
                        <div class="modal fade" id="itemModal{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content item-modal">
                                    <div class="modal-header">
                                        <div class="modal-header-info">
                                            <i class='bx bx-dish'></i>
                                            <h3 class="modal-title">{{ $item->name }}</h3>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                                            <i class='bx bx-x'></i>
                                        </button>
                                    </div>
                                    
                                    <div class="modal-body">
                                        @if($item->category)
                                            <div class="modal-category">
                                                <i class='bx bx-category-alt'></i>
                                                <span>{{ $item->category }}</span>
                                            </div>
                                        @endif
                                        
                                        @if($item->description)
                                            <div class="modal-description">
                                                <div class="description-label">
                                                    <i class='bx bx-info-circle'></i>
                                                    <span>About this dish</span>
                                                </div>
                                                <p>{{ $item->description }}</p>
                                            </div>
                                        @endif
                                        
                                        <div class="modal-price-section">
                                            <div class="price-label">
                                                <i class='bx bx-purchase-tag'></i>
                                                <span>Price</span>
                                            </div>
                                            <span class="price-amount">
                                                {{ $location->currency }} {{ number_format($item->price, 2) }}
                                            </span>
                                        </div>
                                        
                                        <button type="button" 
                                                class="add-to-cart-btn add-to-cart"
                                                data-food-id="{{ $item->id }}" 
                                                data-food-name="{{ $item->name }}" 
                                                data-food-price="{{ $item->price }}"
                                                data-food-category="{{ $item->category ?? '' }}"
                                                data-food-description="{{ $item->description ?? '' }}"
                                                data-category-image="{{ $categoryImage }}"
                                                data-delivery-type=""
                                                data-location-id="{{ $location->location_id }}"
                                                data-bs-dismiss="modal">
                                            <i class='bx bx-cart-add'></i>
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .full-bg-wrapper {
        background: url('/mnt/data/28dcc8a0-6cf8-4280-b368-27032544a3be.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        padding: 1rem 0;
    }

    :root {
        --primary-red: #dc2626;
        --dark-red: #991b1b;
        --light-red: #fee2e2;
        --white: #ffffff;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-500: #6b7280;
        --gray-800: #1f2937;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Header Styles */
    .clean-header {
        background: transparent !important;
        padding: 15px 20px 10px;
        display: flex;
        justify-content: flex-start;
        align-items: flex-end;
        height: 70px;
        position: relative;
        z-index: 10;
    }

    .location-clean {
        display: flex;
        flex-direction: column;
    }

    .deliver-label {
        font-size: 14px;
        opacity: 0.9;
        color: #666;
        font-weight: 500;
        margin-bottom: 2px;
    }

    .deliver-location {
        font-size: 24px;
        font-weight: 800;
        line-height: 1;
        color: var(--primary-red) !important;
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }

    /* Banner Section */
    .banner-section {
        margin: 0.5rem 0 1rem;
        padding: 0 1rem;
    }

    .banner-wrapper {
        position: relative;
        width: 100%;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(220, 38, 38, 0.12);
        border: 3px solid #fff;
        aspect-ratio: 3 / 1;
    }

    .banner-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    /* Search Bar */
    .search-container-wrapper {
        margin: 1rem auto 0.75rem;
        padding: 0 1rem;
        max-width: 1200px;
    }

    .search-box {
        background: var(--white);
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        padding: 0.875rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border: 2px solid var(--gray-100);
    }

    .search-input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 1rem;
        color: var(--gray-800);
        font-weight: 500;
        background: transparent;
    }

    .search-input::placeholder {
        color: var(--gray-500);
    }

    .search-box i {
        font-size: 1.1rem;
    }

    .search-box .bx-search {
        color: var(--gray-500);
    }

    .microphone-icon {
        color: var(--primary-red);
        cursor: pointer;
    }

    /* Categories */
    .categories-section {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        margin-bottom: 0.5rem;
    }

    .categories-container {
        padding: 0 1rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .categories-scroll {
        overflow-x: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .categories-scroll::-webkit-scrollbar {
        display: none;
    }

    .categories-list {
        display: flex;
        gap: 0.5rem;
        min-width: min-content;
        padding: 0.25rem 0;
    }

    .category-item {
        background: var(--white);
        border: 2px solid var(--gray-200);
        border-radius: 0.75rem;
        padding: 0.5rem 0.75rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.375rem;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.3s;
        font-weight: 600;
        color: var(--gray-800);
        min-width: 65px;
        font-size: 0.75rem;
    }

    .category-item:hover,
    .category-item.active {
        background: var(--primary-red);
        border-color: var(--primary-red);
        color: var(--white);
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(220, 38, 38, 0.2);
    }

    .category-icon-wrapper {
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
    }

    .category-icon-wrapper img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }

    /* Menu Section - 2 CARDS PER ROW WITH SPACING */
    .menu-section {
        background: transparent !important;
    }

    .menu-container-wrapper {
        padding: 0.5rem 1rem 1rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .menu-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .menu-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-800);
    }

    /* Menu Grid - 2 CARDS PER ROW WITH GOOD SPACING */
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        width: 100%;
    }

    /* Compact Menu Cards with Good Spacing */
    .menu-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
        border-radius: 0.875rem;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.3);
        height: fit-content;
    }

    .menu-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.15);
        border-color: var(--primary-red);
    }

    .menu-card.hidden {
        display: none;
    }

    .menu-card-body {
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.625rem;
    }

    .menu-card-tag {
        background: var(--light-red);
        color: var(--primary-red);
        font-size: 0.65rem;
        font-weight: 700;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        width: fit-content;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        line-height: 1;
    }

    .menu-card-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--gray-800);
        margin: 0;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.2rem;
    }

    .menu-card-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 0.375rem;
        padding-top: 0.75rem;
        border-top: 1px solid var(--gray-100);
    }

    .menu-card-price {
        display: flex;
        flex-direction: column;
    }

    .price-value {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--primary-red);
        line-height: 1;
    }

    .add-btn-new {
        background: var(--primary-red);
        color: var(--white);
        border: none;
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.3rem;
        cursor: pointer;
        transition: all 0.3s;
        line-height: 1;
    }

    .add-btn-new:hover {
        background: var(--dark-red);
        transform: scale(1.05);
        box-shadow: 0 3px 8px rgba(220, 38, 38, 0.3);
    }

    .add-btn-new i {
        font-size: 0.85rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        background: var(--white);
        border-radius: 1rem;
        grid-column: 1 / -1;
    }

    .empty-icon {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        opacity: 0.5;
    }

    .empty-state h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--gray-800);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--gray-500);
        font-size: 0.9rem;
    }

    /* Tablet View - 3 cards per row */
    @media (min-width: 768px) and (max-width: 1023px) {
        .menu-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 1.25rem;
        }

        .menu-card-body {
            padding: 1.25rem;
        }

        .menu-card-title {
            font-size: 1rem;
        }

        .price-value {
            font-size: 1.05rem;
        }

        .add-btn-new {
            padding: 0.6rem 0.9rem;
            font-size: 0.8rem;
        }
    }

    /* Desktop View - 4 cards per row */
    @media (min-width: 1024px) {
        .menu-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }

        .menu-card-body {
            padding: 1.25rem;
        }

        .menu-card-title {
            font-size: 1rem;
            min-height: 2.4rem;
        }

        .price-value {
            font-size: 1.1rem;
        }

        .add-btn-new {
            padding: 0.6rem 1rem;
            font-size: 0.8rem;
        }
    }

    /* Mobile Optimization */
    @media (max-width: 768px) {
        .clean-header {
            padding: 12px 15px 8px;
            height: 60px;
        }

        .deliver-label {
            font-size: 12px;
        }

        .deliver-location {
            font-size: 20px;
        }

        .banner-section {
            margin: 0.25rem 0 0.75rem;
            padding: 0 0.75rem;
        }

        .banner-wrapper {
            border-radius: 0.75rem;
            aspect-ratio: 2 / 1;
        }

        .search-container-wrapper {
            margin: 0.75rem auto 0.5rem;
            padding: 0 0.75rem;
        }

        .search-box {
            padding: 0.75rem 1rem;
            border-radius: 0.875rem;
        }

        .search-input {
            font-size: 0.9rem;
        }

        .menu-title {
            font-size: 1.25rem;
        }

        .menu-container-wrapper {
            padding: 0.25rem 0.75rem 0.75rem;
        }

        /* Ensure 2 cards per row on mobile with good spacing */
        .menu-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.875rem;
        }

        .menu-card-body {
            padding: 0.875rem;
        }

        .menu-card-title {
            font-size: 0.85rem;
            min-height: 2rem;
        }

        .price-value {
            font-size: 0.9rem;
        }

        .add-btn-new {
            padding: 0.45rem 0.65rem;
            font-size: 0.7rem;
        }
    }

    @media (max-width: 480px) {
        .clean-header {
            padding: 10px 12px 6px;
        }

        .deliver-location {
            font-size: 18px;
        }

        .banner-wrapper {
            aspect-ratio: 1.8 / 1;
            border-radius: 0.625rem;
        }

        .categories-list {
            gap: 0.375rem;
        }

        .category-item {
            padding: 0.375rem 0.5rem;
            min-width: 55px;
            font-size: 0.7rem;
            border-radius: 0.5rem;
        }

        .category-icon-wrapper {
            width: 25px;
            height: 25px;
            font-size: 1rem;
        }

        .menu-container-wrapper {
            padding: 0.25rem 0.5rem 0.5rem;
        }

        .menu-title {
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        /* Mobile spacing adjustments */
        .menu-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .menu-card-body {
            padding: 0.75rem;
            gap: 0.5rem;
        }

        .menu-card-title {
            font-size: 0.8rem;
            min-height: 1.8rem;
        }

        .price-value {
            font-size: 0.85rem;
        }

        .add-btn-new {
            padding: 0.4rem 0.6rem;
            font-size: 0.65rem;
            gap: 0.2rem;
        }

        .add-btn-new i {
            font-size: 0.75rem;
        }

        .menu-card-bottom {
            padding-top: 0.625rem;
            margin-top: 0.25rem;
        }
    }

    /* Very small screens - maintain 2 cards with adjusted spacing */
    @media (max-width: 360px) {
        .menu-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.625rem;
        }

        .menu-card-body {
            padding: 0.625rem;
        }

        .menu-card-title {
            font-size: 0.75rem;
            min-height: 1.6rem;
        }

        .price-value {
            font-size: 0.8rem;
        }

        .add-btn-new {
            padding: 0.35rem 0.5rem;
            font-size: 0.6rem;
        }
    }

    /* Modal Styles (unchanged) */
    .item-modal .modal-content {
        border: none;
        border-radius: 1.5rem;
        overflow: hidden;
    }

    .item-modal .modal-header {
        background: var(--white);
        border-bottom: 3px solid var(--primary-red);
        padding: 1.5rem;
    }

    .modal-header-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .modal-header-info i {
        font-size: 2rem;
        color: var(--primary-red);
    }

    .item-modal .modal-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-800);
    }

    .item-modal .btn-close {
        background: var(--primary-red);
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        border: none;
        cursor: pointer;
    }

    .item-modal .modal-body {
        padding: 1.5rem;
    }

    .modal-category {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--light-red);
        color: var(--dark-red);
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .modal-description {
        margin-bottom: 1rem;
        background: var(--gray-50);
        padding: 1rem;
        border-radius: 0.5rem;
        border-left: 4px solid var(--primary-red);
    }

    .description-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        color: var(--gray-800);
        margin-bottom: 0.5rem;
    }

    .modal-description p {
        color: var(--gray-500);
        line-height: 1.6;
        margin: 0;
    }

    .modal-price-section {
        background: var(--primary-red);
        padding: 1rem 1.25rem;
        border-radius: 0.75rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .price-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--white);
        font-weight: 600;
    }

    .price-amount {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--white);
    }

    .add-to-cart-btn {
        width: 100%;
        background: var(--dark-red);
        color: var(--white);
        border: none;
        padding: 1rem;
        border-radius: 0.75rem;
        font-size: 1.125rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .add-to-cart-btn:hover {
        background: var(--primary-red);
        transform: translateY(-2px);
    }

    /* Delivery Modal */
    .delivery-modal-header {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
        padding: 2rem;
        text-align: center;
        color: var(--white);
    }

    .icon-container {
        width: 64px;
        height: 64px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 2rem;
    }

    .modal-title-custom {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .modal-subtitle {
        opacity: 0.9;
    }

    .delivery-options-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        padding: 1.5rem;
    }

    .delivery-option-card {
        background: var(--white);
        border: 2px solid var(--gray-200);
        border-radius: 1rem;
        padding: 1.5rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
    }

    .delivery-option-card:hover {
        border-color: var(--primary-red);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(220, 38, 38, 0.2);
    }

    .option-icon {
        font-size: 3rem;
        margin-bottom: 0.75rem;
    }

    .delivery-option-card h4 {
        font-size: 1rem;
        font-weight: 700;
        margin: 0.5rem 0;
        color: var(--gray-800);
    }

    .delivery-option-card p {
        color: var(--gray-500);
        margin: 0;
        font-size: 0.875rem;
    }

    .option-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: var(--primary-red);
        color: var(--white);
        padding: 0.25rem 0.5rem;
        border-radius: 1rem;
        font-size: 0.625rem;
        font-weight: 700;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show menu section on page load
    const menuSection = document.getElementById('menuSection');
    if (menuSection) {
        menuSection.style.display = 'block';
    }

    // Category filtering
    const categoryCards = document.querySelectorAll('.category-item');
    const foodItems = document.querySelectorAll('.menu-card');
    const categoryTitle = document.getElementById('categoryTitle');
    
    categoryCards.forEach(card => {
        card.addEventListener('click', function() {
            const selectedCategory = this.getAttribute('data-category');
            
            // Update active state
            categoryCards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            
            // Filter menu items
            let visibleCount = 0;
            foodItems.forEach(item => {
                const itemCategory = item.getAttribute('data-category');
                
                if (selectedCategory === 'all') {
                    item.classList.remove('hidden');
                    visibleCount++;
                    categoryTitle.textContent = 'Our Delicious Menu';
                } else {
                    if (itemCategory === selectedCategory) {
                        item.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        item.classList.add('hidden');
                    }
                    const categoryName = this.querySelector('span').textContent;
                    categoryTitle.textContent = categoryName;
                }
            });

            // Show menu section if hidden
            if (menuSection) {
                menuSection.style.display = 'block';
            }
            
            // Scroll to menu section
            menuSection.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });

            console.log(`Filtered to category: ${selectedCategory}, showing ${visibleCount} items`);
        });
    });

    // Add to cart functionality
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get data from button
            const foodId = this.getAttribute('data-food-id');
            const foodName = this.getAttribute('data-food-name');
            const foodPrice = this.getAttribute('data-food-price');
            const deliveryType = this.getAttribute('data-delivery-type') || '';
            const locationId = this.getAttribute('data-location-id');
            
            // Check if delivery type is selected (if required)
            if (!deliveryType) {
                // Show delivery option modal first
                const deliveryModal = new bootstrap.Modal(document.getElementById('deliveryOptionModal'));
                deliveryModal.show();
                
                // Store item data for later
                sessionStorage.setItem('pendingCartItem', JSON.stringify({
                    foodId: foodId,
                    foodName: foodName,
                    foodPrice: foodPrice,
                    locationId: locationId
                }));
                
                return;
            }
            
            // Add to cart logic
            addItemToCart(foodId, foodName, foodPrice, deliveryType, locationId);
        });
    });

    // Handle delivery option selection
    const deliveryOptions = document.querySelectorAll('.delivery-option-card');
    
    deliveryOptions.forEach(option => {
        option.addEventListener('click', function() {
            const deliveryType = this.getAttribute('data-option');
            const locationId = this.getAttribute('data-location-id');
            
            // Get pending item from session storage
            const pendingItem = sessionStorage.getItem('pendingCartItem');
            
            if (pendingItem) {
                const item = JSON.parse(pendingItem);
                addItemToCart(item.foodId, item.foodName, item.foodPrice, deliveryType, locationId);
                sessionStorage.removeItem('pendingCartItem');
            }
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('deliveryOptionModal'));
            modal.hide();
        });
    });

    // Fix for modal aria-hidden focus issue
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function() {
            // Remove focus from any buttons inside the modal
            const focusedElement = this.querySelector(':focus');
            if (focusedElement) {
                focusedElement.blur();
            }
        });
        
        modal.addEventListener('hide.bs.modal', function() {
            // Remove focus before hiding
            const focusedElement = this.querySelector(':focus');
            if (focusedElement) {
                focusedElement.blur();
            }
        });
    });
});
</script>

@endsection