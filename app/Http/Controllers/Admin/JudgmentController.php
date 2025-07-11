<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Judgment;
use App\Models\JudgmentType;
use App\Models\CaseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JudgmentController extends Controller
{
    /**
     * Display a listing of judgments.
     */
    public function index()
    {
        $judgments = Judgment::with(['case', 'judgmentType'])->paginate(20);
        return view('admin.judgments.index', compact('judgments'));
    }

    /**
     * Show the form for creating a new judgment.
     */
    public function create()
    {
        $cases         = CaseModel::all();
        $judgmentTypes = JudgmentType::all();
        return view('admin.judgments.create', compact('cases', 'judgmentTypes'));
    }

    /**
     * Store a newly created judgment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_id'          => 'required|exists:cases,id',
            'judgment_type_id' => 'required|exists:judgment_types,id',
            'title'            => 'required|string|max:255',
            'content'          => 'required|string',
            'judgment_date'    => 'required|date',
            'status'           => 'required|in:draft,published,final',
        ]);

        $judgment = Judgment::create($validated);

        return redirect()->route('admin.judgments.show', $judgment)->with('success', 'تم إنشاء الحكم بنجاح');
    }

    /**
     * Display the specified judgment.
     */
    public function show(Judgment $judgment)
    {
        $judgment->load(['case', 'judgmentType']);
        return view('admin.judgments.show', compact('judgment'));
    }

    /**
     * Remove the specified judgment from storage.
     */
    public function destroy(Judgment $judgment)
    {
        // حذف المرفقات ان وجدت
        foreach ($judgment->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }
        }
        $judgment->delete();
        return redirect()->route('admin.judgments.index')->with('success', 'تم حذف الحكم بنجاح');
    }
} 