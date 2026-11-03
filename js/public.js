/*
*  ------------------- MAIN SCRIPT ----------------------
*/
document.addEventListener('DOMContentLoaded', function() {
    // ================== SCROLLING TOP BAR ==================
    const topbar = document.querySelector('.topbar');
    if (topbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 0) {
                topbar.classList.add('scrolled');
            } else {
                topbar.classList.remove('scrolled');
            }
        });
    }

    // ================== INITIAL DELIVERY OPTION SELECTION ==================
    const deliveryModal = document.getElementById('deliveryOptionModal');
    const menuSection = document.getElementById('menuSection');
    
    if (deliveryModal) {
        const bsDeliveryModal = new bootstrap.Modal(deliveryModal);
        const navEntries = performance.getEntriesByType("navigation");
        const isReload = navEntries.length > 0 && navEntries[0].type === "reload";

        if (!isReload) {
            bsDeliveryModal.show();
        } else {
            if (menuSection) {
                menuSection.style.display = 'block';
            }

            const selectedOption = sessionStorage.getItem('delivery_type');
            const selectedLocationId = sessionStorage.getItem('location_id');
            
            if (selectedOption) {
                document.querySelectorAll('.add-to-cart').forEach(btn => {
                    btn.dataset.deliveryType = selectedOption;
                    btn.dataset.locationId = selectedLocationId;
                });
            }
        }

        document.querySelectorAll('.delivery-option-card').forEach(button => {
            button.addEventListener('click', function() {
                const selectedOption = this.dataset.option;
                const selectedLocationId = this.dataset.locationId;

                sessionStorage.setItem('delivery_type', selectedOption);
                sessionStorage.setItem('location_id', selectedLocationId);
                
                bsDeliveryModal.hide();
                if (menuSection) {
                    menuSection.style.display = 'block';
                }

                document.querySelectorAll('.add-to-cart').forEach(btn => {
                    btn.dataset.deliveryType = selectedOption;
                    btn.dataset.locationId = selectedLocationId;
                });
            });
        });
    }

    // ================== SEARCH FUNCTIONALITY WITH AJAX ==================
    const searchInput = document.querySelector('.search-input');
    const menuGrid = document.querySelector('.menu-grid');
    const categoryButtons = document.querySelectorAll('.category-item');
    const categoryTitle = document.getElementById('categoryTitle');
    const clearSearchBtn = document.getElementById('clearSearch');
    
    // Get location ID from session or data attribute
    const locationId = sessionStorage.getItem('location_id') || 
                       document.body.getAttribute('data-location-id');
    
    let searchTimeout;
    let currentCategory = 'all';

    if (searchInput) {
        // Search input event listener with debouncing
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.trim();
            
            // Show/hide clear button
            if (clearSearchBtn) {
                clearSearchBtn.style.display = searchTerm ? 'block' : 'none';
            }
            
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Add searching state
            searchInput.parentElement.classList.add('searching');
            
            // Debounce search - wait 300ms after user stops typing
            searchTimeout = setTimeout(() => {
                performSearch(searchTerm, currentCategory);
            }, 300);
        });

        // Clear search on Escape key
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                clearSearch();
            }
        });
    }

    // Clear button functionality
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            clearSearch();
        });
    }

    // Clear search function
    function clearSearch() {
        if (searchInput) {
            searchInput.value = '';
        }
        if (clearSearchBtn) {
            clearSearchBtn.style.display = 'none';
        }
        performSearch('', currentCategory);
    }

    // Category filter functionality
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const selectedCategory = this.getAttribute('data-category');
            currentCategory = selectedCategory;
            
            // Update active state
            categoryButtons.forEach(btn => {
                btn.classList.remove('active', 'search-highlight');
            });
            this.classList.add('active');

            // Clear search input
            if (searchInput) {
                searchInput.value = '';
            }
            if (clearSearchBtn) {
                clearSearchBtn.style.display = 'none';
            }

            // Perform search with category filter
            performSearch('', selectedCategory);

            // Show menu section and scroll
            if (menuSection) {
                menuSection.style.display = 'block';
                menuSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Main search function with AJAX
    function performSearch(searchTerm, categoryId = 'all') {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Show loading state
        if (menuGrid) {
            menuGrid.style.opacity = '0.5';
            menuGrid.style.pointerEvents = 'none';
        }

        fetch('/menu/search', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                location_id: locationId,
                search: searchTerm,
                category_id: categoryId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displaySearchResults(data.items, data.count, data.search_term, data.matched_categories, data.currency);
            } else {
                showToast(data.message || 'Search failed', 'danger');
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            showToast('Error performing search', 'danger');
        })
        .finally(() => {
            // Remove loading state
            if (menuGrid) {
                menuGrid.style.opacity = '1';
                menuGrid.style.pointerEvents = 'auto';
            }
            if (searchInput) {
                searchInput.parentElement.classList.remove('searching');
            }
        });
    }

    // Display search results
    function displaySearchResults(items, count, searchTerm, matchedCategories, currency) {
        if (!menuGrid) return;

        // Clear existing items
        menuGrid.innerHTML = '';

        // Update category title
        updateCategoryTitle(searchTerm, count, currentCategory);

        // Highlight matched categories
        highlightMatchingCategories(matchedCategories);

        // Show menu section
        if (menuSection) {
            menuSection.style.display = 'block';
        }

        // Display items or empty state
        if (items.length === 0) {
            showEmptyState(searchTerm);
        } else {
            items.forEach(item => {
                const menuCard = createMenuCard(item, currency);
                menuGrid.appendChild(menuCard);
            });
        }
    }

    // Create menu card element
    function createMenuCard(item, currency) {
        const card = document.createElement('div');
        card.className = 'menu-card';
        card.setAttribute('data-category', item.category_id || 'uncategorized');
        card.style.animation = 'fadeIn 0.3s ease';

        card.innerHTML = `
            <div class="menu-card-body">
                ${item.category_name ? `<div class="menu-card-tag">${item.category_name}</div>` : ''}
                
                <h3 class="menu-card-title">${item.name}</h3>
                
                <div class="menu-card-bottom">
                    <div class="menu-card-price">
                        <span class="price-value">${currency} ${parseFloat(item.price).toFixed(2)}</span>
                    </div>
                    
                    <button class="add-btn-new" 
                            data-food-id="${item.id}" 
                            data-food-name="${item.name}" 
                            data-food-price="${item.price}"
                            data-bs-toggle="modal" 
                            data-bs-target="#itemModal${item.id}">
                        <i class='bx bx-plus'></i>
                        Add
                    </button>
                </div>
            </div>
        `;

        // Create and append modal
        const modal = createItemModal(item, currency);
        document.body.appendChild(modal);

        return card;
    }

    // Create item modal
    function createItemModal(item, currency) {
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.id = `itemModal${item.id}`;
        modal.setAttribute('tabindex', '-1');

        modal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content item-modal">
                    <div class="modal-header">
                        <div class="modal-header-info">
                            <i class='bx bx-dish'></i>
                            <h3 class="modal-title">${item.name}</h3>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                            <i class='bx bx-x'></i>
                        </button>
                    </div>
                    
                    <div class="modal-body">
                        ${item.category_name ? `
                            <div class="modal-category">
                                <i class='bx bx-category-alt'></i>
                                <span>${item.category_name}</span>
                            </div>
                        ` : ''}
                        
                        ${item.description ? `
                            <div class="modal-description">
                                <div class="description-label">
                                    <i class='bx bx-info-circle'></i>
                                    <span>About this dish</span>
                                </div>
                                <p>${item.description}</p>
                            </div>
                        ` : ''}
                        
                        <div class="modal-price-section">
                            <div class="price-label">
                                <i class='bx bx-purchase-tag'></i>
                                <span>Price</span>
                            </div>
                            <span class="price-amount">
                                ${currency} ${parseFloat(item.price).toFixed(2)}
                            </span>
                        </div>
                        
                        <button type="button" 
                                class="add-to-cart-btn add-to-cart"
                                data-food-id="${item.id}" 
                                data-food-name="${item.name}" 
                                data-food-price="${item.price}"
                                data-food-category="${item.category_name || ''}"
                                data-food-description="${item.description || ''}"
                                data-delivery-type="${sessionStorage.getItem('delivery_type') || ''}"
                                data-location-id="${locationId}"
                                data-bs-dismiss="modal">
                            <i class='bx bx-cart-add'></i>
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        `;

        return modal;
    }

    // Update category title
    function updateCategoryTitle(searchTerm, count, categoryId) {
        if (!categoryTitle) return;

        if (searchTerm) {
            categoryTitle.textContent = `Search results for "${searchTerm}" (${count} item${count !== 1 ? 's' : ''})`;
        } else if (categoryId === 'all') {
            categoryTitle.textContent = 'Our Delicious Menu';
        } else {
            const activeCategory = document.querySelector('.category-item.active span');
            const categoryName = activeCategory ? activeCategory.textContent : 'Category';
            categoryTitle.textContent = categoryName;
        }
    }

    // Highlight matching categories
    function highlightMatchingCategories(matchedCategories) {
        categoryButtons.forEach(btn => {
            const categoryId = btn.getAttribute('data-category');
            
            // Remove highlight
            btn.classList.remove('search-highlight');
            
            // Add highlight to matched categories
            if (matchedCategories && matchedCategories.includes(parseInt(categoryId))) {
                btn.classList.add('search-highlight');
            }
        });
    }

    // Show empty state
    function showEmptyState(searchTerm) {
        if (!menuGrid) return;

        menuGrid.innerHTML = `
            <div class="search-empty-state">
                <div class="empty-icon">🔍</div>
                <h3>No Items Found</h3>
                <p>${searchTerm ? `No results found for "${searchTerm}"` : 'No items available in this category'}</p>
                <button class="btn btn-primary mt-3" onclick="document.querySelector('.search-input').value = ''; document.querySelector('.search-input').dispatchEvent(new Event('input'));">
                    <i class='bx bx-refresh'></i> Clear Search
                </button>
            </div>
        `;
    }

    // Voice search functionality
    const microphoneIcon = document.querySelector('.microphone-icon');
    if (microphoneIcon) {
        microphoneIcon.addEventListener('click', function() {
            if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                const recognition = new SpeechRecognition();
                
                recognition.lang = 'en-US';
                recognition.continuous = false;
                recognition.interimResults = false;

                recognition.onstart = function() {
                    microphoneIcon.style.color = 'var(--dark-red)';
                    if (searchInput) {
                        searchInput.placeholder = 'Listening...';
                    }
                };

                recognition.onresult = function(event) {
                    const transcript = event.results[0][0].transcript;
                    if (searchInput) {
                        searchInput.value = transcript;
                        performSearch(transcript, currentCategory);
                    }
                };

                recognition.onerror = function(event) {
                    console.error('Speech recognition error:', event.error);
                    if (searchInput) {
                        searchInput.placeholder = 'Search for dishes...';
                    }
                    showToast('Voice search error: ' + event.error, 'warning');
                };

                recognition.onend = function() {
                    microphoneIcon.style.color = 'var(--primary-red)';
                    if (searchInput) {
                        searchInput.placeholder = 'Search for dishes...';
                    }
                };

                recognition.start();
            } else {
                showToast('Voice search is not supported in your browser', 'warning');
            }
        });
    }

    // ================== CART MANAGEMENT ==================
    const cartList = document.querySelector('.cart-list');
    const addProduct = document.querySelectorAll('.add-to-cart');
    const tableNumberInput = document.querySelector('input[name="table_number"]');
    const customerContactInput = document.querySelector('input[name="customer_contact"]');
    const additionalContactInput = document.querySelector('input[name="additional_contact"]');
    const customerAddressInput = document.querySelector('textarea[name="customer_address"]');
    const confirmOrderBtn = document.querySelector('.confirm-order');
    const cartBadge = document.getElementById('cart-quantity');

    // Initialize cart
    loadCustomerAddress();
    loadCart();

    // Event Listeners for form inputs
    if (customerContactInput) {
        customerContactInput.addEventListener('input', () => {
            updateConfirmButtonState();
            sessionStorage.setItem('temp_contact', customerContactInput.value);
        });
    }

    if (additionalContactInput) {
        additionalContactInput.addEventListener('input', () => {
            sessionStorage.setItem('temp_additional_contact', additionalContactInput.value);
        });
    }

    if (customerAddressInput) {
        customerAddressInput.addEventListener('input', () => {
            updateConfirmButtonState();
            sessionStorage.setItem('temp_address', customerAddressInput.value);
        });
    }

    if (tableNumberInput) {
        tableNumberInput.addEventListener('input', updateConfirmButtonState);
    }

    // ================== ADD TO CART ==================
    // Event delegation for dynamically added buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-to-cart')) {
            const button = e.target.closest('.add-to-cart');
            const foodId = button.getAttribute('data-food-id');
            const foodName = button.getAttribute('data-food-name');
            const deliveryType = button.getAttribute('data-delivery-type') || sessionStorage.getItem('delivery_type') || '';
            const locationIdAttr = button.getAttribute('data-location-id') || sessionStorage.getItem('location_id') || '';

            if (!locationIdAttr) {
                showToast('⚠️ Location ID is missing. Please select a location first.', 'warning');
                return;
            }
            if (!deliveryType) {
                showToast('⚠️ Please select a delivery type before adding to cart!', 'warning');
                return;
            }

            // Add to database via AJAX
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    food_id: foodId,
                    quantity: 1,
                    delivery_type: deliveryType,
                    location_id: locationIdAttr
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(`✓ ${foodName} added to cart!`, 'success');
                    loadCart();
                } else {
                    showToast(data.message, 'warning');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error adding item to cart', 'danger');
            });
        }
    });

    // ================== QUANTITY CONTROLS ==================
    document.addEventListener('click', (event) => {
        // Minus button
        if (event.target.classList.contains('minus-btn') || event.target.closest('.minus-btn')) {
            const button = event.target.classList.contains('minus-btn') ? event.target : event.target.closest('.minus-btn');
            const foodId = button.getAttribute('data-food-id');
            const currentQty = parseInt(button.nextElementSibling.textContent);
            
            if (currentQty > 1) {
                updateCartQuantity(foodId, currentQty - 1);
            }
        }

        // Plus button
        if (event.target.classList.contains('plus-btn') || event.target.closest('.plus-btn')) {
            const button = event.target.classList.contains('plus-btn') ? event.target : event.target.closest('.plus-btn');
            const foodId = button.getAttribute('data-food-id');
            const currentQty = parseInt(button.previousElementSibling.textContent);
            
            updateCartQuantity(foodId, currentQty + 1);
        }

        // Delete button
        if (event.target.closest('.delete-btn')) {
            const foodId = event.target.closest('.delete-btn').getAttribute('data-food-id');
            removeFromCart(foodId);
        }
    });

    // ================== CHANGE DELIVERY TYPE FUNCTIONALITY ==================
    const changeDeliveryBtn = document.getElementById('changeDeliveryType');
    const changeDeliveryModal = document.getElementById('changeDeliveryModal');
    
    if (changeDeliveryBtn && changeDeliveryModal) {
        const bsChangeModal = new bootstrap.Modal(changeDeliveryModal);
        
        // Open change delivery modal
        changeDeliveryBtn.addEventListener('click', function() {
            bsChangeModal.show();
        });
        
        // Handle delivery option selection
        document.querySelectorAll('.btn-delivery-option').forEach(button => {
            button.addEventListener('click', function() {
                const newDeliveryType = this.getAttribute('data-option');
                const locationIdValue = sessionStorage.getItem('location_id');
                const customerId = document.body.getAttribute('data-customer-id');
                
                // Update delivery type
                updateDeliveryType(newDeliveryType, locationIdValue, customerId);
                bsChangeModal.hide();
            });
        });
    }

    // Function to update delivery type in cart
    function updateDeliveryType(newDeliveryType, locationIdValue, customerId) {
        // Show loading state
        const changeBtn = document.getElementById('changeDeliveryType');
        if (changeBtn) {
            changeBtn.disabled = true;
            changeBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Updating...';
        }

        // Update delivery type via AJAX
        fetch('/cart/update-delivery-type', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                delivery_type: newDeliveryType,
                location_id: locationIdValue,
                customer_id: customerId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(`✓ Order type changed to ${newDeliveryType}`, 'success');
                
                // Update session storage FIRST
                sessionStorage.setItem('delivery_type', newDeliveryType);
                
                // Update all add-to-cart buttons
                document.querySelectorAll('.add-to-cart').forEach(btn => {
                    btn.dataset.deliveryType = newDeliveryType;
                });
                
                // Update the display immediately
                const deliveryTypeSpan = document.getElementById('selected-delivery-type');
                if (deliveryTypeSpan) {
                    deliveryTypeSpan.textContent = newDeliveryType;
                }
                
                // Update the delivery info section visibility
                const deliveryInfoSection = document.getElementById('deliveryInfoSection');
                if (deliveryInfoSection) {
                    deliveryInfoSection.classList.remove('d-none');
                }
                
                // Update form sections based on delivery type
                updateDeliveryTypeDisplay(newDeliveryType);
                
                // Clear and reset form fields based on delivery type
                if (newDeliveryType === 'Doorstep Delivery') {
                    if (tableNumberInput) tableNumberInput.value = '';
                    // Load saved address if available
                    loadCustomerAddress();
                } else if (newDeliveryType === 'Restaurant Dine-in') {
                    if (customerAddressInput) customerAddressInput.value = '';
                } else {
                    // Counter Pickup
                    if (tableNumberInput) tableNumberInput.value = '';
                    if (customerAddressInput) customerAddressInput.value = '';
                }
                
                // Reload cart to reflect changes
                loadCart();
                
                // Re-enable button
                if (changeBtn) {
                    changeBtn.disabled = false;
                    changeBtn.innerHTML = '<i class="bx bx-edit"></i> Change';
                }
            } else {
                showToast(data.message || 'Failed to update delivery type', 'danger');
                
                // Re-enable button
                if (changeBtn) {
                    changeBtn.disabled = false;
                    changeBtn.innerHTML = '<i class="bx bx-edit"></i> Change';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error changing delivery type', 'danger');
            
            // Re-enable button
            if (changeBtn) {
                changeBtn.disabled = false;
                changeBtn.innerHTML = '<i class="bx bx-edit"></i> Change';
            }
        });
    }

    // ================== CART FUNCTIONS ==================
    function loadCart() {
        fetch('/cart/get', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayCart(data.cart_items, data.total_amount, data.cart_count, data.delivery_type, data.currency);
                
                // Update session storage with current delivery type
                if (data.delivery_type) {
                    sessionStorage.setItem('delivery_type', data.delivery_type);
                }
            }
        })
        .catch(error => {
            console.error('Error loading cart:', error);
        });
    }

    function displayCart(cartItems, totalAmount, cartCount, deliveryType, currency) {
        if (!cartList) return;
        
        cartList.innerHTML = '';

        if (cartItems.length === 0) {
            cartList.innerHTML = `
                <li class="empty-cart">
                    <i class="bi bi-cart-x" style="font-size: 3rem;"></i>
                    <p class="mt-3 mb-0">No items in cart</p>
                </li>
            `;
        } else {
            cartItems.forEach(item => {
                const listItem = document.createElement('li');
                listItem.innerHTML = `
                    <div class="cart-item">
                        <div class="card-body p-3">
                            <div class="row g-3 align-items-center">                               
                                <div class="col">
                                    <h6 class="mb-1 fw-semibold">${item.name}</h6>
                                    <small class="text-muted d-block mb-2">
                                        ${item.currency} ${parseFloat(item.price).toFixed(2)} each
                                    </small>
                                    <div class="fw-bold text-primary">
                                        ${item.currency} ${parseFloat(item.total).toFixed(2)}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-2 mt-2">
                                <div class="col-7">
                                    <div class="qty-controls">
                                        <button type="button" 
                                                class="btn qty-btn minus-btn" 
                                                data-food-id="${item.id}">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <span class="fw-bold mx-2">${item.quantity}</span>
                                        <button type="button" 
                                                class="btn qty-btn plus-btn" 
                                                data-food-id="${item.id}">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <button type="button" 
                                            class="btn btn-danger w-100 btn-sm btn-delete delete-btn" 
                                            data-food-id="${item.id}">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                cartList.appendChild(listItem);
            });
        }

        // Update Summary
        const cartTotalAmount = document.getElementById('cart-total-amount');
        const cartItemCount = document.getElementById('cart-item-count');
        
        if (cartTotalAmount) {
            cartTotalAmount.textContent = `${currency} ${parseFloat(totalAmount).toFixed(2)}`;
        }
        if (cartItemCount) {
            cartItemCount.textContent = `${cartCount} item${cartCount !== 1 ? 's' : ''}`;
        }
        
        // Update Both Cart Badges (Desktop + Mobile)
        const cartBadges = document.querySelectorAll('#cart-quantity, #cart-quantity-mobile');
        
        cartBadges.forEach(badge => {
            if (cartCount > 0) {
                badge.textContent = cartCount;
                badge.style.display = 'inline-flex';
            } else {
                badge.textContent = '0';
                badge.style.display = 'none';
            }
        });

        // Update Delivery Type Display
        updateDeliveryTypeDisplay(deliveryType);
        updateConfirmButtonState();
    }

    function updateDeliveryTypeDisplay(deliveryType) {
        const deliveryInfoSection = document.getElementById('deliveryInfoSection');
        const deliveryTypeSpan = document.getElementById('selected-delivery-type');
        const tableNumberSection = document.getElementById('tableNumberSection');
        const addressSection = document.getElementById('addressSection');
        const cashNote = document.getElementById('cashNote');

        if (deliveryTypeSpan) {
            deliveryTypeSpan.textContent = deliveryType || '';
        }

        if (deliveryInfoSection) {
            if (deliveryType) {
                deliveryInfoSection.classList.remove('d-none');
            } else {
                deliveryInfoSection.classList.add('d-none');
            }
        }

        // Show/hide sections based on delivery type
        if (deliveryType === 'Doorstep Delivery') {
            if (tableNumberSection) tableNumberSection.classList.add('d-none');
            if (addressSection) addressSection.classList.remove('d-none');
            if (cashNote) cashNote.classList.remove('d-none');
        } else if (deliveryType === 'Restaurant Dine-in') {
            if (tableNumberSection) tableNumberSection.classList.remove('d-none');
            if (addressSection) addressSection.classList.add('d-none');
            if (cashNote) cashNote.classList.remove('d-none');
        } else {
            // Counter Pickup
            if (tableNumberSection) tableNumberSection.classList.add('d-none');
            if (addressSection) addressSection.classList.add('d-none');
            if (cashNote) cashNote.classList.remove('d-none');
        }
    }

    function updateCartQuantity(foodId, quantity) {
        fetch('/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                food_id: foodId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadCart();
            } else {
                showToast(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error updating cart', 'danger');
        });
    }

    function removeFromCart(foodId) {
        fetch('/cart/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                food_id: foodId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'warning');
                loadCart();
            } else {
                showToast(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error removing item', 'danger');
        });
    }

    function updateConfirmButtonState() {
        if (!confirmOrderBtn) return;

        const tableNumberValue = tableNumberInput ? tableNumberInput.value.trim() : '';
        const hasContact = customerContactInput ? customerContactInput.value.trim() !== '' : false;
        const addressValue = customerAddressInput ? customerAddressInput.value.trim() : '';

        // Check cart via badge or make an API call
        const cartCount = cartBadge ? parseInt(cartBadge.textContent) || 0 : 0;
        const isCartEmpty = cartCount === 0;

        const deliveryTypeSpan = document.getElementById('selected-delivery-type');
        const deliveryType = deliveryTypeSpan ? deliveryTypeSpan.textContent : '';
        
        const requiresTable = deliveryType === 'Restaurant Dine-in';
        const requiresAddress = deliveryType === 'Doorstep Delivery';

        const isEnabled = !isCartEmpty && 
                          hasContact && 
                          deliveryType !== '' &&
                          (!requiresTable || tableNumberValue !== '') && 
                          (!requiresAddress || addressValue !== '');

        confirmOrderBtn.disabled = !isEnabled;
    }

    function loadCustomerAddress() {
        const customerId = document.body.getAttribute('data-customer-id');
        if (customerId && customerId.trim() !== '') {
            fetch(`/customer/address/${customerId}`)
                .then(response => response.ok ? response.json() : Promise.reject())
                .then(data => {
                    if (data.address && data.address.trim() !== '' && customerAddressInput) {
                        customerAddressInput.value = data.address;
                    } else {
                        const tempAddress = sessionStorage.getItem('temp_address');
                        if (tempAddress && customerAddressInput) {
                            customerAddressInput.value = tempAddress;
                        }
                    }
                    
                    if (data.contact && data.contact.trim() !== '' && customerContactInput) {
                        customerContactInput.value = data.contact;
                    } else {
                        const tempContact = sessionStorage.getItem('temp_contact');
                        if (tempContact && customerContactInput) {
                            customerContactInput.value = tempContact;
                        }
                    }
                    
                    const tempAdditionalContact = sessionStorage.getItem('temp_additional_contact');
                    if (tempAdditionalContact && additionalContactInput) {
                        additionalContactInput.value = tempAdditionalContact;
                    }
                    
                    updateConfirmButtonState();
                })
                .catch(() => {
                    const tempAddress = sessionStorage.getItem('temp_address');
                    if (tempAddress && customerAddressInput) {
                        customerAddressInput.value = tempAddress;
                    }
                    
                    const tempContact = sessionStorage.getItem('temp_contact');
                    if (tempContact && customerContactInput) {
                        customerContactInput.value = tempContact;
                    }
                    
                    const tempAdditionalContact = sessionStorage.getItem('temp_additional_contact');
                    if (tempAdditionalContact && additionalContactInput) {
                        additionalContactInput.value = tempAdditionalContact;
                    }
                    
                    updateConfirmButtonState();
                });
        }
    }

    // ================== PAYMENT AND CONFIRMATION FLOW ==================
    let paymentModal, finalConfirmationModal;

    function initializeModals() {
        if (!paymentModal) {
            const paymentModalEl = document.getElementById('paymentConfirmationModal');
            if (paymentModalEl) {
                paymentModal = new bootstrap.Modal(paymentModalEl);
            }
        }
        if (!finalConfirmationModal) {
            const finalModalEl = document.getElementById('finalConfirmationModal');
            if (finalModalEl) {
                finalConfirmationModal = new bootstrap.Modal(finalModalEl);
            }
        }
    }

    if (confirmOrderBtn) {
        confirmOrderBtn.addEventListener('click', () => {
            initializeModals();
            setupConfirmationFlow();
            
            const totalAmount = document.getElementById('cart-total-amount').textContent;
            document.getElementById('paymentOrderSummary').textContent = `Total: ${totalAmount}`;
            
            if (paymentModal) {
                paymentModal.show();
            }
        });
    }

    function setupConfirmationFlow() {
        const confirmPaymentBtn = document.getElementById('confirmPayment');
        const finalConfirmOrderBtn = document.getElementById('finalConfirmOrder');
        
        if (!confirmPaymentBtn || !finalConfirmOrderBtn) return;
        
        const newConfirmPaymentBtn = confirmPaymentBtn.cloneNode(true);
        const newFinalConfirmOrderBtn = finalConfirmOrderBtn.cloneNode(true);
        
        confirmPaymentBtn.parentNode.replaceChild(newConfirmPaymentBtn, confirmPaymentBtn);
        finalConfirmOrderBtn.parentNode.replaceChild(newFinalConfirmOrderBtn, finalConfirmOrderBtn);

        newConfirmPaymentBtn.addEventListener('click', handlePaymentConfirmation);
        newFinalConfirmOrderBtn.addEventListener('click', handleFinalConfirmation);
    }

    function handlePaymentConfirmation() {
        const selectedPaymentInput = document.querySelector('input[name="paymentType"]:checked');
        if (!selectedPaymentInput) return;
        
        const selectedPayment = selectedPaymentInput.value;
        const paymentMethod = selectedPayment === 'cash' ? 'Cash' : 'Card';
        const totalAmount = document.getElementById('cart-total-amount').textContent;
        const deliveryType = document.getElementById('selected-delivery-type').textContent;
        const address = customerAddressInput ? customerAddressInput.value : '';
        const additionalContact = additionalContactInput ? additionalContactInput.value : '';
        
        let finalMessage = `Please confirm your order:\n\n• Order Type: ${deliveryType}\n• Payment Method: ${paymentMethod}\n• ${totalAmount}\n`;
        
        if (deliveryType === 'Doorstep Delivery' && address) {
            finalMessage += `• Delivery Address: ${address}\n`;
        }
        
        if (additionalContact) {
            finalMessage += `• Additional Contact: ${additionalContact}\n`;
        }
        
        finalMessage += `\nThis action cannot be undone.`;
        
        document.getElementById('finalConfirmationMessage').textContent = finalMessage;
        
        if (paymentModal) paymentModal.hide();
        if (finalConfirmationModal) finalConfirmationModal.show();
    }

    function handleFinalConfirmation() {
        const selectedPaymentInput = document.querySelector('input[name="paymentType"]:checked');
        const selectedPayment = selectedPaymentInput ? selectedPaymentInput.value : 'cash';
        sendOrderData(selectedPayment);
    }

    function sendOrderData(paymentType) {
        const table_number = tableNumberInput ? tableNumberInput.value : '';
        const customer_contact = customerContactInput ? customerContactInput.value : '';
        const customer_address = customerAddressInput ? customerAddressInput.value : '';
        const additional_contact = additionalContactInput ? additionalContactInput.value : '';

        const totalAmountElement = document.getElementById('cart-total-amount');
        let totalAmountText = totalAmountElement ? totalAmountElement.textContent : '0.00';
    
        const totalAmount = totalAmountText.replace(/[^\d.]/g, '');

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/menu/create-order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ 
                table_number, 
                customer_contact,
                customer_address,
                additional_contact,
                payment_type: paymentType,
                total_amount: totalAmount,
            }),
        })
        .then(res => res.ok ? res.json() : Promise.reject('Network error'))
        .then(data => {
            if (finalConfirmationModal) finalConfirmationModal.hide();
            
            if (data['success-message']) {
                sessionStorage.removeItem('temp_address');
                sessionStorage.removeItem('temp_additional_contact');
                
                const successMessage = document.getElementById('successMessage');
                const successModalEl = document.getElementById('successModal');
                const closeModalBtn = document.getElementById('closeSuccessModal');

                if (successModalEl && successMessage) {
                    successMessage.textContent = data['success-message'];

                    const bsModal = new bootstrap.Modal(successModalEl);
                    bsModal.show();

                    if (closeModalBtn) {
                        const newCloseBtn = closeModalBtn.cloneNode(true);
                        closeModalBtn.parentNode.replaceChild(newCloseBtn, closeModalBtn);
                        
                        newCloseBtn.addEventListener('click', () => {
                            sessionStorage.removeItem('temp_address');
                            sessionStorage.removeItem('temp_additional_contact');
                            location.reload();
                        });
                    }
                }
            } else if (data['validation-error-message']) {
                showToast(data['validation-error-message'], 'danger');
            }
        })
        .catch(error => {
            if (finalConfirmationModal) finalConfirmationModal.hide();
            console.error('Error:', error);
            showToast('An error occurred while placing your order. Please try again.', 'danger');
        });
    }

    // ================== UTILITY FUNCTIONS ==================
    function showToast(message, type) {
        const toastContainer = document.querySelector('.toast-container') || createToastContainer();
        
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'danger'} border-0`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        toastContainer.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', () => toast.remove());
    }

    function createToastContainer() {
        const container = document.createElement('div');
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
        return container;
    }
});