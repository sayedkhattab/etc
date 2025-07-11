<?php

namespace App\Http\Controllers;

use App\Models\Store\StoreCaseCategory;
use App\Models\Store\StoreCaseFile;
use App\Models\Store\StoreCasePurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreCaseController extends Controller
{
    /**
     * Display a listing of case files.
     */
    public function index(Request $request)
    {
        $query = StoreCaseFile::with('category')->active();
        
        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }
        
        // Filter by difficulty
        if ($request->has('difficulty') && $request->difficulty) {
            $query->where('difficulty', $request->difficulty);
        }
        
        // Filter by price range
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Search by title or description
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Sort
        $sort = $request->sort ?? 'newest';
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('purchases_count', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        // Get featured case files
        $featuredCaseFiles = StoreCaseFile::active()->featured()->take(3)->get();
        
        // Get categories for filter
        $categories = StoreCaseCategory::active()->ordered()->get();
        
        // Get case files with pagination
        $caseFiles = $query->paginate(12);
        
        return view('store.index', compact('caseFiles', 'featuredCaseFiles', 'categories'));
    }

    /**
     * Display the specified case file.
     */
    public function show(string $id)
    {
        $caseFile = StoreCaseFile::with(['category', 'attachments' => function($query) {
            $query->where('is_visible_before_purchase', true);
        }])->active()->findOrFail($id);
        
        // Check if user has purchased this case file
        $userPurchase = null;
        if (Auth::check()) {
            $userPurchase = StoreCasePurchase::where('case_file_id', $caseFile->id)
                ->where('user_id', Auth::id())
                ->where('status', 'completed')
                ->first();
        }
        
        // Get related case files from the same category
        $relatedCaseFiles = StoreCaseFile::where('category_id', $caseFile->category_id)
            ->where('id', '!=', $caseFile->id)
            ->active()
            ->take(4)
            ->get();
            
        return view('store.show', compact('caseFile', 'userPurchase', 'relatedCaseFiles'));
    }

    /**
     * Display user's purchased case files.
     */
    public function myPurchases()
    {
        $purchases = StoreCasePurchase::with(['caseFile', 'activeCase'])
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->latest()
            ->get();
            
        return view('store.my-purchases', compact('purchases'));
    }

    /**
     * Display case file details after purchase.
     */
    public function viewPurchase(string $id)
    {
        $purchase = StoreCasePurchase::with(['caseFile.attachments', 'activeCase'])
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->findOrFail($id);
            
        return view('store.view-purchase', compact('purchase'));
    }
}
