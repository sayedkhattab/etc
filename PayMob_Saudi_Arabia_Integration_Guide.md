# PayMob Saudi Arabia Integration Guide

## Overview
This document provides a comprehensive guide for integrating PayMob Saudi Arabia payment gateway into your Laravel application. The integration follows a standard flow for online payment processing:

1. Authentication with PayMob API
2. Creating an order
3. Generating a payment key
4. Redirecting to the payment page
5. Processing the callback

## Prerequisites
Before you begin, you'll need to:
1. Register for a PayMob merchant account at [PayMob Saudi Arabia](https://paymob.com/en/saudi-arabia)
2. Obtain the following credentials from your PayMob dashboard:
   - API Key
   - Merchant ID
   - Integration ID
   - iFrame ID

## Environment Variables
Add the following environment variables to your `.env` file:

```
تم اضافة هذه البيانات في ملف .env
PAYMOB_API_KEY=ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SmpiR0Z6Y3lJNklrMWxjbU5vWVc1MElpd2ljSEp2Wm1sc1pWOXdheUk2TlRRek5Dd2libUZ0WlNJNkltbHVhWFJwWVd3aWZRLnJJX2FheHhzV0JycW1BdHBHcVplYkRMY2Rza1dIc0dRLWxSY1JqejNiX3RhcTlzaUUxWTRudFJlU1psMkhoMTl4ZzRneVBjMUpFTjlJa2liMkFTTUd3
PAYMOB_INTEGRATION_ID=7527
PAYMOB_IFRAME_ID=5900
PAYMOB_MERCHANT_ID=5434
PAYMOB_BASE_URL=https://ksa.paymob.com/api
```

## Integration Steps

### 1. Create Order Controller
Create a controller to handle payment processing:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    /**
     * Generate a payment link for an order
     *
     * @param int $order_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generatePaymentLink($order_id)
    {
        $user = Auth::user();
        $order = Order::findOrFail($order_id);

        try {
            // Step 1: Get Authentication Token
            $authResponse = Http::post(env('PAYMOB_BASE_URL') . '/auth/tokens', [
                'api_key' => env('PAYMOB_API_KEY'),
            ]);

            if (!$authResponse->successful()) {
                return response()->json(['error' => 'Failed to authenticate with Paymob'], 500);
            }

            $authToken = $authResponse->json('token');

            // Step 2: Create an Order
            $orderResponse = Http::withToken($authToken)->post(env('PAYMOB_BASE_URL') . '/ecommerce/orders', [
                'merchant_id' => env('PAYMOB_MERCHANT_ID'),
                'amount_cents' => (int) $order->total_amount * 100, // Amount in cents
                'currency' => 'SAR',
                'items' => [],
                'shipping_data' => [
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'phone_number' => $user->phone ?? 'NA',
                    'street' => 'NA',
                    'building' => 'NA',
                    'floor' => 'NA',
                    'city' => 'NA',
                    'country' => 'SA',
                ],
            ]);

            if (!$orderResponse->successful()) {
                return response()->json(['error' => 'Failed to create order'], 500);
            }

            $orderId = $orderResponse->json('id');

            // Step 3: Create Payment Key
            $paymentKeyResponse = Http::withToken($authToken)->post(env('PAYMOB_BASE_URL') . '/acceptance/payment_keys', [
                'amount_cents' => (int) $order->total_amount * 100,
                'expiration' => 3600,
                'order_id' => $orderId,
                'billing_data' => [
                    'apartment' => 'NA',
                    'email' => $user->email,
                    'floor' => 'NA',
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'street' => 'NA',
                    'building' => 'NA',
                    'phone_number' => $user->phone ?? 'NA',
                    'shipping_method' => 'NA',
                    'postal_code' => 'NA',
                    'city' => 'NA',
                    'country' => 'SA',
                    'state' => 'NA',
                ],
                'currency' => 'SAR',
                'integration_id' => env('PAYMOB_INTEGRATION_ID'),
            ]);

            // Save PayMob order reference to your order
            $order->payment_reference = $orderId;
            $order->save();

            if (!$paymentKeyResponse->successful()) {
                return response()->json(['error' => 'Failed to create payment key'], 500);
            }

            $paymentKey = $paymentKeyResponse->json('token');
            
            // Step 4: Generate Payment Link
            $paymentLink = 'https://ksa.paymob.com/api/acceptance/iframes/' . env('PAYMOB_IFRAME_ID') . '?payment_token=' . $paymentKey;

            // Redirect to payment page
            return redirect()->to($paymentLink);
        } catch (\Throwable $th) {
            // Log the error
            \Log::error('PayMob payment error: ' . $th->getMessage());
            return response()->json(['error' => 'An error occurred during payment processing'], 500);
        }
    }

    /**
     * Handle payment callback from PayMob
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function callback(Request $request)
    {
        // Log the callback data
        \Log::info('PayMob Callback Response', $request->all());
        
        // Find the order using payment reference
        $order = Order::where('payment_reference', $request->input('order'))->first();
        
        if (!$order) {
            \Log::error('Order not found with payment reference: ' . $request->input('order'));
            return redirect()->route('payment.failed')->with('error', 'Invalid payment transaction');
        }

        // Check payment status
        if ($request->success == 'false' || $request->input('success') === false) {
            $order->update(['payment_status' => 'failed']);
            return redirect()->route('payment.failed')->with('error', 'Payment failed');
        } else {
            // Payment successful
            if ($order->payment_status == 'pending') {
                // Update order status
                $order->update(['payment_status' => 'paid']);
                
                // Process successful payment (e.g., activate subscription, deliver product, etc.)
                // Your business logic here
                
                return redirect()->route('payment.success')->with('success', 'Payment completed successfully');
            } else {
                // Payment already processed
                return redirect()->route('payment.success')->with('info', 'Payment was already processed');
            }
        }
    }
}
```

### 2. Define Routes
Add the following routes to your `routes/web.php` file:

```php
// Payment routes
Route::post('/checkout/{order}', [PaymentController::class, 'generatePaymentLink'])->name('checkout');

// Payment callback routes - must be accessible without authentication
Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback.post');
Route::get('/payment/success', function() {
    return view('payments.success');
})->name('payment.success');
Route::get('/payment/failed', function() {
    return view('payments.failed');
})->name('payment.failed');
```

### 3. Create Payment Status Views
Create views for payment success and failure:

#### resources/views/payments/success.blade.php
```html
<div class="payment-status success">
    <h1>Payment Successful</h1>
    <p>Your payment has been processed successfully.</p>
    <a href="/dashboard" class="btn">Return to Dashboard</a>
</div>
```

#### resources/views/payments/failed.blade.php
```html
<div class="payment-status failed">
    <h1>Payment Failed</h1>
    <p>Your payment could not be processed. Please try again.</p>
    <a href="/checkout" class="btn">Try Again</a>
</div>
```

## Order Model
Ensure your Order model has the necessary fields:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total_amount',
        'payment_status', // pending, paid, failed
        'payment_reference', // PayMob order ID
        // Other order fields
    ];
    
    // Relationships and other methods
}
```

## Database Migration
Create a migration for the orders table if you don't have one already:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_status')->default('pending');
            $table->string('payment_reference')->nullable();
            // Add other order fields
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
```

## PayMob Webhook Configuration
In your PayMob dashboard:

1. Go to Integration Settings
2. Configure your transaction processed callback URL:
   - For testing: `https://your-domain.com/payment/callback`
   - Make sure the URL is publicly accessible

## Testing the Integration
1. Create a test order in your application
2. Initiate the payment process
3. You'll be redirected to the PayMob payment page
4. Complete the payment using test card details (available in PayMob documentation)
5. You'll be redirected back to your application based on the payment result

## Security Considerations
1. Always validate the payment callback data
2. Consider implementing HMAC validation for callbacks
3. Store sensitive payment information securely
4. Use HTTPS for all payment-related communications

## Troubleshooting
Common issues and solutions:

1. **Authentication failure**: Verify your API key is correct and active
2. **Order creation failure**: Check the request payload format and merchant ID
3. **Callback not received**: Verify the callback URL is publicly accessible and correctly configured
4. **Payment not processed**: Check for any validation errors in the billing data

## Additional Resources
- [PayMob API Documentation](https://docs.paymob.com)
- [PayMob Saudi Arabia Support](https://paymob.com/en/saudi-arabia/contact-us)

---

This integration guide is based on PayMob Saudi Arabia's payment gateway as implemented in our application. For the most up-to-date information, always refer to the official PayMob documentation. 