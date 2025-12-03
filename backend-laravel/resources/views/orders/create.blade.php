<x-store-layout>
    <style>
        /* Global Styles & Variables (Matching your brand colors) */
        :root {
            --cream-bg: #fcfbf8; 
            --terracotta-accent: #e76f51; 
            --text-color: #333333;
            --light-gray: #e0e0e0;
            --border-radius: 6px;
            --focus-blue: #007bff; 
        }

        .checkout-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            display: flex;
            gap: 40px;
            font-family: 'Roboto', sans-serif;
            color: var(--text-color);
        }

        .checkout-form {
            flex: 2;
        }

        .order-summary {
            flex: 1;
            background: white;
            padding: 20px;
            border-radius: var(--border-radius);
            border: 1px solid var(--light-gray);
            height: fit-content;
        }

        .section-title {
            font-size: 1.5em;
            margin-bottom: 20px;
            font-weight: 700;
            border-bottom: 1px solid var(--light-gray);
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: var(--border-radius);
        }

        /* Payment Options Styles (From User) */
        .payment-options {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .payment-option-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            border: 1px solid var(--light-gray);
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: all 0.2s;
            background: white;
        }

        .payment-option-card:hover {
            border-color: var(--terracotta-accent);
        }

        .payment-option-card.checked {
            border-color: var(--terracotta-accent);
            background-color: #fff5f2; /* Light terracotta tint */
            box-shadow: 0 0 0 1px var(--terracotta-accent);
        }

        .payment-left {
            font-weight: 600;
        }

        .payment-icons {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .payment-option-card input[type="radio"] {
            display: none; /* Hide default radio */
        }

        /* Dynamic Form Field Styles */
        .payment-form-details {
            padding: 20px;
            background-color: #f8f8f8;
            border-radius: var(--border-radius);
            border: 1px dashed #ccc;
            margin-top: 10px;
            display: none; /* Hidden by default */
        }
        .payment-form-details label {
            display: block;
            margin-bottom: 5px;
            font-size: 0.9em;
            font-weight: 500;
            color: #555;
        }
        .payment-form-details input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: var(--border-radius);
        }

        .checkout-btn {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: var(--terracotta-accent);
            color: white;
            text-align: center;
            font-size: 1.1em;
            font-weight: 700;
            border-radius: var(--border-radius);
            border: none;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        .checkout-btn:hover {
            background-color: #d15a3a;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .checkout-container {
                flex-direction: column;
            }
        }
    </style>

    <div class="checkout-container">
        <div class="checkout-form">
            <h1 style="font-family: 'Playfair Display', serif; font-size: 2.5em; margin-bottom: 30px;">Checkout</h1>

            <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                @csrf
                
                <!-- Shipping Information -->
                <div class="form-section" style="margin-bottom: 40px;">
                    <h2 class="section-title">Shipping Information</h2>
                    
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ Auth::user()->name }}" required>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" rows="3" required placeholder="Street address, City, Province, Zip Code"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" required placeholder="09XX-XXX-XXXX">
                    </div>
                </div>

                <!-- Payment Method (User's Design) -->
                <div class="form-section">
                    <h2 class="section-title">Payment Method</h2>
                    <div class="payment-options">
                        
                        <label class="payment-option-card checked" id="card-option">
                            <div class="payment-left">
                                Credit Card / Debit Card
                            </div>
                            <div class="payment-icons">
                                <img src="{{ asset('images/payment/visa-mastercard.png') }}" alt="Visa/Mastercard" style="height: 25px;">
                            </div>
                            <input type="radio" name="payment_method" value="card" checked>
                        </label>

                        <label class="payment-option-card" id="gcash-option">
                            <div class="payment-left">
                                GCash
                            </div>
                            <div class="payment-icons">
                                <img src="{{ asset('images/payment/gcash.png') }}" alt="GCash" style="height: 25px;">
                            </div>
                            <input type="radio" name="payment_method" value="gcash">
                        </label>

                        <label class="payment-option-card" id="cod-option">
                            <div class="payment-left">
                                Cash on Delivery (COD)
                            </div>
                            <div class="payment-icons">
                                💰
                            </div>
                            <input type="radio" name="payment_method" value="cod">
                        </label>

                    </div>
                    
                    <div id="payment-details-container">
                        
                        <div id="card-form" class="payment-form-details" style="display: block;">
                            <p style="margin-bottom: 10px; font-size: 0.9em; color: #555;">Enter card details below:</p>
                            
                            <label for="card-bank">Bank Name / Card Type</label>
                            <input type="text" id="card-bank" name="card_bank" placeholder="e.g., BPI, Metrobank, Visa">
                            
                            <label for="card-number">Card Number</label>
                            <input type="text" id="card-number" name="card_number" placeholder="XXXX XXXX XXXX XXXX">
                            
                            <label for="card-name">Name on Card</label>
                            <input type="text" id="card-name" name="card_name" placeholder="Full Name">
                            
                            <div style="display: flex; gap: 10px;">
                                <div style="width: 50%;">
                                    <label for="card-expiry">Expiration Date</label>
                                    <input type="text" id="card-expiry" name="card_expiry" placeholder="MM/YY">
                                </div>
                                <div style="width: 50%;">
                                    <label for="card-cvc">CVC</label>
                                    <input type="text" id="card-cvc" name="card_cvc" placeholder="XXX">
                                </div>
                            </div>
                        </div>
                        <div id="gcash-form" class="payment-form-details">
                            <p style="margin-bottom: 10px; font-size: 0.9em; color: #555;">Please provide your GCash details for verification:</p>
                            
                            <label for="gcash-phone">GCash Phone Number</label>
                            <input type="text" id="gcash-phone" name="gcash_phone" placeholder="e.g., 09XX-XXX-XXXX">
                            
                            <label for="gcash-name">GCash Account Name</label>
                            <input type="text" id="gcash-name" name="gcash_name" placeholder="Name on GCash Account">
                        </div>
                        
                        <div id="cod-info" class="payment-form-details">
                            <p style="font-weight: 700; color: #333;">Cash on Delivery Selected.</p>
                            <p style="font-size: 0.9em; color: #555; margin-top: 5px;">Please have the exact amount ready upon delivery.</p>
                        </div>
                    </div>
                </div>

                <button type="submit" class="checkout-btn">Place Order</button>
            </form>
        </div>

        <!-- Order Summary -->
        <div class="order-summary">
            <h2 class="section-title" style="font-size: 1.2em;">Order Summary</h2>
            
            @foreach($cartItems as $item)
                <div style="display: flex; gap: 10px; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                    @if($item->product->images->isNotEmpty())
                        <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" alt="{{ $item->product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                    @else
                        <div style="width: 50px; height: 50px; background-color: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 0.6em; color: #888;">No Img</div>
                    @endif
                    <div>
                        <div style="font-weight: 600; font-size: 0.9em;">{{ $item->product->name }}</div>
                        <div style="font-size: 0.8em; color: #666;">Qty: {{ $item->quantity }}</div>
                        <div style="font-size: 0.9em; color: var(--terracotta-accent);">₱{{ number_format($item->product->price * $item->quantity, 2) }}</div>
                    </div>
                </div>
            @endforeach

            <div style="margin-top: 20px; border-top: 2px solid var(--light-gray); padding-top: 15px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span>Subtotal</span>
                    <span>₱{{ number_format($cartItems->sum(fn($i) => $i->product->price * $i->quantity), 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span>Shipping</span>
                    <span>₱100.00</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 1.2em; color: var(--terracotta-accent);">
                    <span>Total</span>
                    <span>₱{{ number_format($cartItems->sum(fn($i) => $i->product->price * $i->quantity) + 100, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Javascript to handle the selection state and dynamic form visibility
        document.addEventListener('DOMContentLoaded', () => {
            const paymentOptions = document.querySelectorAll('.payment-option-card');
            const paymentForms = {
                'card-option': document.getElementById('card-form'),
                'gcash-option': document.getElementById('gcash-form'),
                'cod-option': document.getElementById('cod-info')
            };

            function showForm(selectedId) {
                // Hide all forms
                Object.values(paymentForms).forEach(form => {
                    form.style.display = 'none';
                });
                // Show the form corresponding to the selected payment method
                const selectedForm = paymentForms[selectedId];
                if (selectedForm) {
                    selectedForm.style.display = 'block';
                }
            }

            paymentOptions.forEach(option => {
                option.addEventListener('click', () => {
                    // 1. Update visual checkmark
                    paymentOptions.forEach(o => o.classList.remove('checked'));
                    option.classList.add('checked');
                    
                    // 2. Select the radio button inside
                    const radio = option.querySelector('input[type="radio"]');
                    if (radio) radio.checked = true;

                    // 3. Show the correct input form
                    showForm(option.id);
                });
            });

            // Initialize state: Ensure the default checked option shows its form
            const defaultCheckedOption = document.querySelector('.payment-option-card.checked');
            if (defaultCheckedOption) {
                showForm(defaultCheckedOption.id);
            }
        });
    </script>
</x-store-layout>
