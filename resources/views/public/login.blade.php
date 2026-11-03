<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MadrasDarbar - Order</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-red: rgb(216, 25, 25);
            --primary-hover: #b71515;
            --border-color: #e2e8f0;
            --text-main: #1f2937;
            --text-muted: #64748b;
        }

        html, body {
            width: 100%;
            min-height: 100%;
            overflow-x: hidden;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.65)),
                        url('https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=1920') center/cover no-repeat fixed;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            min-height: 100dvh;
            padding: 24px 16px;
            -webkit-font-smoothing: antialiased;
        }

        .order-container {
            background: white;
            border-radius: 20px;
            padding: 36px 32px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.45);
            margin: auto 0;
            transition: all 0.3s ease;
        }

        .logo {
            background: var(--primary-red);
            text-align: center;
            padding: 20px 15px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(216, 25, 25, 0.25);
        }

        .logo img {
            height: 65px;
            max-width: 85%;
            object-fit: contain;
            display: block;
        }

        .order-type {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
        }

        .type-option {
            flex: 1;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 16px 12px;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            text-align: center;
            min-height: 98px;
            justify-content: center;
            background: #fafafa;
            -webkit-tap-highlight-color: transparent;
            user-select: none;
        }

        .type-option:hover {
            border-color: var(--primary-red);
            background: #fffafa;
        }

        .type-option.active {
            border-color: var(--primary-red);
            background: #fff5f5;
            box-shadow: 0 4px 12px rgba(216, 25, 25, 0.12);
        }

        .type-option input[type="radio"] {
            display: none;
        }

        .type-icon {
            font-size: 28px;
            line-height: 1;
        }

        .type-label {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-main);
        }

        .location-section, .phone-section {
            margin-bottom: 22px;
        }

        .location-section h3, .phone-section h3 {
            margin-bottom: 10px;
            color: var(--text-main);
            font-size: 15px;
            font-weight: 700;
            letter-spacing: -0.2px;
        }

        .location-select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 16px; /* 16px prevents iOS Safari auto-zoom */
            background-color: white;
            cursor: pointer;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 16px;
            color: var(--text-main);
        }

        .location-select:focus {
            outline: none;
            border-color: var(--primary-red);
            box-shadow: 0 0 0 3px rgba(216, 25, 25, 0.15);
        }

        .location-info {
            margin-top: 10px;
            padding: 10px 14px;
            background: #f8fafc;
            border-radius: 8px;
            font-size: 13px;
            color: #475569;
            display: none;
            border-left: 3px solid var(--primary-red);
        }

        .location-info.show {
            display: block;
        }

        .phone-input-container {
            display: flex;
            gap: 10px;
            width: 100%;
        }

        .phone-input {
            padding: 14px 14px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 16px; /* 16px prevents iOS Safari auto-zoom */
            background-color: white;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            color: var(--text-main);
        }

        #countryCode {
            width: 78px;
            min-width: 78px;
            max-width: 82px;
            background-color: #f1f5f9;
            text-align: center;
            font-weight: 700;
            color: var(--text-main);
            flex-shrink: 0;
        }

        #phoneInput {
            flex: 1;
            min-width: 0;
        }

        .phone-input:focus {
            outline: none;
            border-color: var(--primary-red);
            box-shadow: 0 0 0 3px rgba(216, 25, 25, 0.15);
        }

        .phone-input::placeholder {
            color: #94a3b8;
        }

        .phone-info {
            margin-top: 8px;
            font-size: 12px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
            line-height: 1.4;
        }

        .account-notice {
            margin-top: 14px;
            padding: 12px 14px;
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 10px;
            font-size: 13px;
            color: #92400e;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            line-height: 1.4;
        }

        .account-notice-icon {
            font-size: 18px;
            flex-shrink: 0;
            line-height: 1;
        }

        .continue-button {
            width: 100%;
            padding: 16px;
            background: var(--primary-red);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            min-height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(216, 25, 25, 0.25);
        }

        .continue-button:hover:not(:disabled) {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(216, 25, 25, 0.35);
        }

        .continue-button:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .message {
            margin-top: 15px;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 14px;
            display: none;
            text-align: center;
            line-height: 1.4;
        }

        .message.show {
            display: block;
        }

        .message.error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .message.success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .loader {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive adjustments for Tablets & Phones */
        @media (max-width: 576px) {
            body {
                padding: 16px 12px;
            }

            .order-container {
                padding: 22px 16px;
                border-radius: 16px;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.35);
            }

            .logo {
                padding: 14px 10px;
                margin-bottom: 18px;
                border-radius: 10px;
            }

            .logo img {
                height: 48px;
            }

            .order-type {
                gap: 10px;
                margin-bottom: 18px;
            }

            .type-option {
                padding: 12px 8px;
                min-height: 82px;
                border-radius: 10px;
            }

            .type-icon {
                font-size: 24px;
            }

            .type-label {
                font-size: 14px;
            }

            .location-section, .phone-section {
                margin-bottom: 18px;
            }

            .location-section h3, .phone-section h3 {
                font-size: 14px;
                margin-bottom: 8px;
            }

            .location-select {
                padding: 12px 14px;
                border-radius: 10px;
                font-size: 16px;
            }

            .phone-input-container {
                gap: 8px;
            }

            .phone-input {
                padding: 12px 12px;
                border-radius: 10px;
                font-size: 16px;
            }

            #countryCode {
                width: 72px;
                min-width: 72px;
                padding: 12px 6px;
            }

            .account-notice {
                margin-top: 12px;
                padding: 10px 12px;
                font-size: 12px;
            }

            .continue-button {
                padding: 14px;
                font-size: 15px;
                min-height: 48px;
                border-radius: 10px;
            }
        }

        /* Extra small devices (<= 360px) */
        @media (max-width: 360px) {
            body {
                padding: 10px 8px;
            }

            .order-container {
                padding: 16px 12px;
            }

            .logo {
                padding: 12px 8px;
                margin-bottom: 15px;
            }

            .logo img {
                height: 42px;
            }

            .type-option {
                padding: 10px 6px;
                min-height: 72px;
            }

            .type-icon {
                font-size: 22px;
            }

            .type-label {
                font-size: 13px;
            }

            #countryCode {
                width: 65px;
                min-width: 65px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="order-container">
        <div class="logo">
            <img src="{{ asset('images/logo.jpeg') }}" alt="MadrasDarbar">
        </div>

        <form id="orderForm">
            @csrf
            
            <div class="order-type">
                <label class="type-option active" for="delivery">
                    <input type="radio" name="order_type" id="delivery" value="delivery" checked>
                    <div class="type-icon">🚚</div>
                    <div class="type-label">Delivery</div>
                </label>
                <label class="type-option" for="pickup">
                    <input type="radio" name="order_type" id="pickup" value="pickup">
                    <div class="type-icon">🏪</div>
                    <div class="type-label">Pickup</div>
                </label>
            </div>

            <div class="location-section">
                <h3>Select Your Location</h3>
                <select name="location_id" id="locationSelect" class="location-select" required>
                    <option value="" disabled selected>Choose a location...</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->location_id }}" class="location-option" 
                                data-code="{{ $location->mobile_code ?? '+66' }}"
                                data-name="{{ $location->location_name }}">
                            {{ $location->location_name }} 
                        </option>
                    @endforeach
                </select>
                <div class="location-info" id="locationInfo">
                    <strong>Selected Location:</strong>
                    <span id="selectedLocationText"></span>
                </div>
            </div>

            <div class="phone-section">
                <h3>Enter Your Phone Number</h3>
                <div class="phone-input-container">
                    <input 
                        type="text" 
                        id="countryCode" 
                        class="phone-input" 
                        value=""
                        readonly
                    >
                    <input 
                        type="tel" 
                        name="phone_number" 
                        id="phoneInput" 
                        class="phone-input" 
                        placeholder="Enter your mobile number"
                        required
                    >
                </div>
                <div class="phone-info">
                    <i>📞</i>
                    <span>We'll use this to contact you about your order</span>
                </div>
                
                <!-- Simple Account Notice -->
                <div class="account-notice">
                    <div class="account-notice-icon">💡</div>
                    <div>
                        <strong>Already have an account?</strong> 
                        Use the same phone number to access your order history.
                    </div>
                </div>
            </div>

            <button type="submit" class="continue-button" id="continueBtn">
                Continue to Menu
            </button>
            <div class="message" id="message"></div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const orderForm = document.getElementById('orderForm');
        const phoneInput = document.getElementById('phoneInput');
        const locationSelect = document.getElementById('locationSelect');
        const locationInfo = document.getElementById('locationInfo');
        const selectedLocationText = document.getElementById('selectedLocationText');
        const messageDiv = document.getElementById('message');
        const continueBtn = document.getElementById('continueBtn');
        const countryCodeInput = document.getElementById('countryCode');

        // Order type selection
        document.querySelectorAll('.type-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.type-option').forEach(opt => {
                    opt.classList.remove('active');
                });
                this.classList.add('active');
                this.querySelector('input[type="radio"]').checked = true;
            });
        });

        // Location selection change
        locationSelect.addEventListener('change', function() {
            if (this.value) {
                const selectedOption = this.options[this.selectedIndex];
                const locationName = selectedOption.textContent.trim();
                selectedLocationText.textContent = locationName;
                locationInfo.classList.add('show');
                
                // Update country code based on location name
                const locationNameLower = locationName.toLowerCase();
                
                if (locationNameLower.includes('phuket') || 
                    locationNameLower.includes('bangkok') || 
                    locationNameLower.includes('pattaya')) {
                    countryCodeInput.value = '+66';
                } else if (locationNameLower.includes('colombo')) {
                    countryCodeInput.value = '+94';
                } else {
                    countryCodeInput.value = '+66'; // Default Thailand
                }
            } else {
                locationInfo.classList.remove('show');
                countryCodeInput.value = '';
            }
            validateForm();
        });

        // Form validation
        function validateForm() {
            const locationValid = locationSelect.value !== '';
            continueBtn.disabled = !(locationValid);
        }

        // Form submission
        orderForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const orderType = document.querySelector('input[name="order_type"]:checked').value;
            const phoneNumber = phoneInput.value;
            const locationId = locationSelect.value;
            const countryCode = countryCodeInput.value;

            if (!phoneNumber) {
                showMessage('Please enter a phone number', 'error');
                return;
            }

            if (!locationId) {
                showMessage('Please select a location', 'error');
                return;
            }

            // Show loading state
            continueBtn.disabled = true;
            continueBtn.innerHTML = '<div class="loader"></div> Processing...';

            try {
                const response = await fetch('/order/submit', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        order_type: orderType,
                        location_id: locationId,
                        phone_number: countryCode + phoneNumber
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showMessage('Phone number and location saved successfully! Redirecting...', 'success');
                    // Redirect to menu page
                    setTimeout(() => {
                        window.location.href = '/home';
                    }, 1000);
                } else {
                    showMessage(data.message || 'An error occurred', 'error');
                }
            } catch (error) {
                showMessage('Network error. Please try again.', 'error');
                console.error('Submission error:', error);
            } finally {
                continueBtn.disabled = false;
                continueBtn.innerHTML = 'Continue to Menu';
            }
        });

        function showMessage(text, type) {
            messageDiv.textContent = text;
            messageDiv.className = `message ${type} show`;
            
            setTimeout(() => {
                messageDiv.classList.remove('show');
            }, 5000);
        }

        // Auto-select first location if only one exists
        if (locationSelect.options.length === 2) {
            locationSelect.selectedIndex = 1;
            locationSelect.dispatchEvent(new Event('change'));
        }

        if (locationSelect.value) {
            locationSelect.dispatchEvent(new Event('change'));
        }

        // Initial form validation
        validateForm();
        
        // Phone input validation
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            
            if (this.value.length < 9) {
                phoneInput.style.borderColor = '#ff6b6b';
            } else {
                phoneInput.style.borderColor = '#e0e0e0';
            }
        });
    });
</script>
</body>
</html>