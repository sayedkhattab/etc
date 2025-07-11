<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store\StoreCaseFile;
use App\Models\Store\StoreCasePurchase;
use App\Models\User;
use App\Models\VirtualCourt\ActiveCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StoreCasePurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = StoreCasePurchase::with(['caseFile', 'user', 'activeCase'])->latest()->paginate(20);

        // إحصائيات عامة لعرضها أسفل الجدول
        $statistics = [
            'total'     => StoreCasePurchase::count(),
            'active'    => StoreCasePurchase::whereNotNull('active_case_id')->count(),
            'plaintiff' => StoreCasePurchase::where('role', 'مدعي')->count(),
            'defendant' => StoreCasePurchase::where('role', 'مدعى عليه')->count(),
        ];

        return view('admin.store.purchases.index', compact('purchases', 'statistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $caseFiles = StoreCaseFile::active()->get();
        $users = User::all();
        return view('admin.store.purchases.create', compact('caseFiles', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_file_id' => 'required|exists:store_case_files,id',
            'user_id' => 'required|exists:users,id',
            'role' => ['required', Rule::in(['مدعي', 'مدعى عليه'])],
            'payment_method' => 'required|string|max:255',
            'status' => ['required', Rule::in(['pending', 'completed', 'failed', 'refunded'])],
        ]);

        $caseFile = StoreCaseFile::findOrFail($validated['case_file_id']);
        $validated['price'] = $caseFile->price;

        StoreCasePurchase::create($validated);

        // Increment purchases count if status is completed
        if ($validated['status'] === 'completed') {
            $caseFile->incrementPurchasesCount();
        }

        return redirect()->route('admin.store.purchases.index')
            ->with('success', 'تم إنشاء عملية الشراء بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $purchase = StoreCasePurchase::with(['caseFile.attachments', 'user', 'activeCase'])->findOrFail($id);
        return view('admin.store.purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $purchase = StoreCasePurchase::findOrFail($id);
        $caseFiles = StoreCaseFile::active()->get();
        $users = User::all();
        return view('admin.store.purchases.edit', compact('purchase', 'caseFiles', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $purchase = StoreCasePurchase::findOrFail($id);

        $validated = $request->validate([
            'case_file_id' => 'required|exists:store_case_files,id',
            'user_id' => 'required|exists:users,id',
            'role' => ['required', Rule::in(['مدعي', 'مدعى عليه'])],
            'payment_method' => 'required|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
            'status' => ['required', Rule::in(['pending', 'completed', 'failed', 'refunded'])],
        ]);

        // If case file changed, update price
        if ($purchase->case_file_id != $validated['case_file_id']) {
            $caseFile = StoreCaseFile::findOrFail($validated['case_file_id']);
            $validated['price'] = $caseFile->price;
        }

        // If status changed to completed, increment purchases count
        if ($purchase->status !== 'completed' && $validated['status'] === 'completed') {
            $caseFile = StoreCaseFile::findOrFail($validated['case_file_id']);
            $caseFile->incrementPurchasesCount();
        }

        $purchase->update($validated);

        return redirect()->route('admin.store.purchases.index')
            ->with('success', 'تم تحديث عملية الشراء بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $purchase = StoreCasePurchase::findOrFail($id);

        // Check if purchase is activated
        if ($purchase->active_case_id) {
            return redirect()->route('admin.store.purchases.index')
                ->with('error', 'لا يمكن حذف عملية الشراء لأنها مرتبطة بقضية نشطة');
        }

        $purchase->delete();

        return redirect()->route('admin.store.purchases.index')
            ->with('success', 'تم حذف عملية الشراء بنجاح');
    }

    /**
     * Activate a purchase by creating an active case.
     */
    public function activate(string $id)
    {
        $purchase = StoreCasePurchase::with('caseFile')->findOrFail($id);

        // Check if purchase is already activated
        if ($purchase->active_case_id) {
            return redirect()->route('admin.store.purchases.index')
                ->with('error', 'عملية الشراء مفعلة بالفعل');
        }

        // Check if purchase status is completed
        if ($purchase->status !== 'completed') {
            return redirect()->route('admin.store.purchases.index')
                ->with('error', 'لا يمكن تفعيل عملية الشراء لأن حالتها ليست مكتملة');
        }

        // Check if there's a matching purchase for the opposite role
        $oppositeRole = $purchase->role === 'مدعي' ? 'مدعى عليه' : 'مدعي';
        $matchingPurchase = StoreCasePurchase::where('case_file_id', $purchase->case_file_id)
            ->where('status', 'completed')
            ->where('role', $oppositeRole)
            ->whereNull('active_case_id')
            ->first();

        if (!$matchingPurchase) {
            return redirect()->route('admin.store.purchases.index')
                ->with('error', 'لا يوجد عملية شراء مطابقة للدور المقابل');
        }

        DB::beginTransaction();
        try {
            // Create active case
            $activeCase = ActiveCase::create([
                'store_case_file_id' => $purchase->case_file_id,
                'title' => $purchase->caseFile->title,
                'status' => 'active',
                'plaintiff_id' => $purchase->role === 'مدعي' ? $purchase->user_id : $matchingPurchase->user_id,
                'defendant_id' => $purchase->role === 'مدعى عليه' ? $purchase->user_id : $matchingPurchase->user_id,
                'started_at' => now(),
                'expected_end_at' => now()->addDays($purchase->caseFile->estimated_duration_days),
            ]);

            // Update purchases
            $purchase->activate($activeCase->id);
            $matchingPurchase->activate($activeCase->id);

            DB::commit();

            return redirect()->route('admin.store.purchases.index')
                ->with('success', 'تم تفعيل عملية الشراء وإنشاء قضية نشطة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.store.purchases.index')
                ->with('error', 'حدث خطأ أثناء تفعيل عملية الشراء: ' . $e->getMessage());
        }
    }

    /**
     * Check if a role is available for a case file.
     */
    public function checkRole(Request $request)
    {
        $request->validate([
            'case_file_id' => 'required|exists:store_case_files,id',
            'role' => ['required', Rule::in(['مدعي', 'مدعى عليه'])],
            'exclude_id' => 'nullable|exists:store_case_purchases,id',
        ]);

        $query = StoreCasePurchase::where('case_file_id', $request->case_file_id)
            ->where('role', $request->role)
            ->where('status', 'completed');

        if ($request->has('exclude_id')) {
            $query->where('id', '!=', $request->exclude_id);
        }

        $exists = $query->exists();

        return response()->json([
            'available' => !$exists,
        ]);
    }
}
