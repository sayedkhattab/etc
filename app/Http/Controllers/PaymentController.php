<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    /**
     * Generate a PayMob payment link for the given course purchase.
     */
    public function generatePaymentLink(Course $course)
    {
        $user = Auth::user();

        // If course is free, enroll directly
        if ($course->price <= 0) {
            return redirect()->route('courses.enroll', $course->id);
        }

        // Create local order record (payment_status = pending)
        $order = Order::create([
            'user_id'          => $user->id,
            'course_id'        => $course->id,
            'total_amount'     => $course->price,
            'payment_status'   => Order::STATUS_PENDING,
        ]);

        try {
            // Step 1: Authentication
            $authResponse = Http::post(env('PAYMOB_BASE_URL', 'https://ksa.paymob.com/api') . '/auth/tokens', [
                'api_key' => env('PAYMOB_API_KEY'),
            ]);

            if (! $authResponse->successful()) {
                \Log::error('PayMob auth failed', $authResponse->json());
                return back()->with('error', 'تعذّر الاتصال بمزوّد الدفع.');
            }

            $authToken = $authResponse->json('token');

            // Step 2: Create PayMob order
            $orderResponse = Http::withToken($authToken)->post(env('PAYMOB_BASE_URL') . '/ecommerce/orders', [
                'merchant_id'   => env('PAYMOB_MERCHANT_ID'),
                'amount_cents'  => (int) ($course->price * 100),
                'currency'      => 'SAR',
                'items'         => [],
                'shipping_data' => [
                    'first_name'    => $user->first_name ?? $user->name,
                    'last_name'     => $user->last_name ?: 'NA',
                    'email'         => $user->email,
                    'phone_number'  => $user->phone ?? 'NA',
                    'street'        => 'NA',
                    'building'      => 'NA',
                    'floor'         => 'NA',
                    'city'          => 'NA',
                    'country'       => 'SA',
                ],
            ]);

            if (! $orderResponse->successful()) {
                \Log::error('PayMob order creation failed', $orderResponse->json());
                return back()->with('error', 'تعذّر إنشاء طلب الدفع.');
            }

            $paymobOrderId = $orderResponse->json('id');

            // Step 3: Payment key
            $paymentKeyResponse = Http::withToken($authToken)->post(env('PAYMOB_BASE_URL') . '/acceptance/payment_keys', [
                'amount_cents'  => (int) ($course->price * 100),
                'expiration'    => 3600,
                'order_id'      => $paymobOrderId,
                'billing_data'  => [
                    'apartment'        => 'NA',
                    'email'            => $user->email,
                    'floor'            => 'NA',
                    'first_name'       => $user->first_name ?? $user->name,
                    'last_name'        => $user->last_name ?: 'NA',
                    'street'           => 'NA',
                    'building'         => 'NA',
                    'phone_number'     => $user->phone ?? 'NA',
                    'shipping_method'  => 'NA',
                    'postal_code'      => 'NA',
                    'city'             => 'NA',
                    'country'          => 'SA',
                    'state'            => 'NA',
                ],
                'currency'      => 'SAR',
                'integration_id'=> env('PAYMOB_INTEGRATION_ID'),
            ]);

            if (! $paymentKeyResponse->successful()) {
                \Log::error('PayMob payment key failed', $paymentKeyResponse->json());
                return back()->with('error', 'تعذّر توليد مفتاح الدفع.');
            }

            $paymentKey = $paymentKeyResponse->json('token');

            // Save reference numbers
            $order->update([
                'payment_reference' => $paymobOrderId,
            ]);

            // Step 4: Redirect to PayMob iframe
            $paymentLink = env('PAYMOB_BASE_URL', 'https://ksa.paymob.com/api') . '/acceptance/iframes/' . env('PAYMOB_IFRAME_ID') . '?payment_token=' . $paymentKey;

            return redirect()->away($paymentLink);
        } catch (\Throwable $e) {
            \Log::error('PayMob exception: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء معالجة عملية الدفع.');
        }
    }

    /**
     * PayMob callback to update order status.
     */
    public function callback(Request $request)
    {
        \Log::info('PayMob callback', $request->all());

        $paymobOrderId = $request->input('order');
        $success       = $request->input('success');

        $order = Order::where('payment_reference', $paymobOrderId)->first();
        if (! $order) {
            \Log::warning('Order not found for PayMob reference ' . $paymobOrderId);
            return redirect()->route('payment.failed')->with('error', 'عملية دفع غير معروفة.');
        }

        // Update payment status
        if ($success === 'false' || $success === false) {
            $order->update(['payment_status' => Order::STATUS_FAILED]);
            return redirect()->route('payment.failed')->with('error', 'عملية الدفع فشلت.');
        }

        // Only process if still pending
        if ($order->payment_status === Order::STATUS_PENDING) {
            $order->update(['payment_status' => Order::STATUS_PAID]);
            // Enroll user in the course automatically
            $order->course->students()->syncWithoutDetaching([$order->user_id => [
                'enrollment_date'      => now(),
                'status'               => 'enrolled',
                'progress_percentage'  => 0,
            ]]);
        }

        return redirect()->route('payment.success')->with('success', 'تمت عملية الدفع بنجاح.');
    }
} 