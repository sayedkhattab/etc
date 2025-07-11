<?php

namespace App\Http\Controllers;

use App\Models\Store\StoreCaseFile;
use App\Models\Store\StoreCasePurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreCasePurchaseController extends Controller
{
    /**
     * Display the purchase confirmation page.
     */
    public function confirm(Request $request, string $id)
    {
        $caseFile = StoreCaseFile::active()->findOrFail($id);
        
        $validated = $request->validate([
            'role' => ['required', Rule::in(['مدعي', 'مدعى عليه'])],
        ]);
        
        // Check if user already purchased this case file with the same role
        if (Auth::check()) {
            $existingPurchase = StoreCasePurchase::where('case_file_id', $caseFile->id)
                ->where('user_id', Auth::id())
                ->where('role', $validated['role'])
                ->where('status', 'completed')
                ->first();
                
            if ($existingPurchase) {
                return redirect()->route('store.my-purchases')
                    ->with('error', 'لقد قمت بشراء هذه القضية بهذا الدور من قبل');
            }
        }
        
        $role = $validated['role'];
        
        return view('store.confirm-purchase', compact('caseFile', 'role'));
    }

    /**
     * Process the purchase.
     */
    public function process(Request $request, string $id)
    {
        $caseFile = StoreCaseFile::active()->findOrFail($id);
        
        $validated = $request->validate([
            'role' => ['required', Rule::in(['مدعي', 'مدعى عليه'])],
            'payment_method' => ['required', Rule::in(['credit', 'bank_transfer'])],
        ]);
        
        // Check if user already purchased this case file with the same role
        $existingPurchase = StoreCasePurchase::where('case_file_id', $caseFile->id)
            ->where('user_id', Auth::id())
            ->where('role', $validated['role'])
            ->where('status', 'completed')
            ->first();
            
        if ($existingPurchase) {
            return redirect()->route('store.my-purchases')
                ->with('error', 'لقد قمت بشراء هذه القضية بهذا الدور من قبل');
        }
        
        // Create purchase record
        $purchase = StoreCasePurchase::create([
            'case_file_id' => $caseFile->id,
            'user_id' => Auth::id(),
            'role' => $validated['role'],
            'price' => $caseFile->price,
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
        ]);
        
        // Process payment based on payment method
        if ($validated['payment_method'] === 'credit') {
            // Simulate credit payment (in a real app, this would integrate with a payment gateway)
            $purchase->update([
                'status' => 'completed',
                'transaction_id' => 'CR-' . time(),
            ]);
            
            // Increment purchases count
            $caseFile->incrementPurchasesCount();
            
            return redirect()->route('store.purchase.success', $purchase->id);
        } else {
            // Bank transfer requires manual approval
            return redirect()->route('store.purchase.bank-transfer', $purchase->id);
        }
    }

    /**
     * Display the purchase success page.
     */
    public function success(string $id)
    {
        $purchase = StoreCasePurchase::with('caseFile')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        if ($purchase->status !== 'completed') {
            return redirect()->route('store.index')
                ->with('error', 'عملية الشراء غير مكتملة');
        }
        
        return view('store.purchase-success', compact('purchase'));
    }

    /**
     * Display the bank transfer instructions page.
     */
    public function bankTransfer(string $id)
    {
        $purchase = StoreCasePurchase::with('caseFile')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        if ($purchase->payment_method !== 'bank_transfer') {
            return redirect()->route('store.index')
                ->with('error', 'طريقة الدفع غير صحيحة');
        }
        
        // Bank account details (should be stored in settings)
        $bankDetails = [
            'bank_name' => 'البنك الأهلي السعودي',
            'account_name' => 'منصة اثبات التعليمية',
            'account_number' => 'SA0380000000123456789012',
            'iban' => 'SA0380000000123456789012',
        ];
        
        return view('store.bank-transfer', compact('purchase', 'bankDetails'));
    }

    /**
     * Upload bank transfer receipt.
     */
    public function uploadReceipt(Request $request, string $id)
    {
        $purchase = StoreCasePurchase::where('user_id', Auth::id())->findOrFail($id);
        
        if ($purchase->payment_method !== 'bank_transfer') {
            return redirect()->route('store.index')
                ->with('error', 'طريقة الدفع غير صحيحة');
        }
        
        $validated = $request->validate([
            'receipt' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'notes' => 'nullable|string|max:500',
        ]);
        
        // Store receipt
        $receiptPath = $request->file('receipt')->store('receipts', 'public');
        
        // Update purchase with receipt information
        $purchase->update([
            'transaction_id' => 'BT-' . time(),
            'receipt_path' => $receiptPath,
            'receipt_notes' => $validated['notes'] ?? null,
        ]);
        
        return redirect()->route('store.my-purchases')
            ->with('success', 'تم رفع إيصال التحويل البنكي بنجاح وسيتم مراجعته قريبًا');
    }
}
