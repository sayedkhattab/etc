<?php

namespace App\Http\Controllers;

use App\Models\CaseModel;
use App\Models\CourtSession;
use App\Models\SessionStatus;
use App\Models\SessionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourtSessionController extends Controller
{
    /**
     * عرض قائمة الجلسات لقضية معينة
     */
    public function index(CaseModel $case)
    {
        $this->authorize('view', $case);
        
        $sessions = $case->sessions()
            ->with(['sessionType', 'status'])
            ->orderBy('date_time')
            ->get();
            
        return view('sessions.index', compact('case', 'sessions'));
    }

    /**
     * عرض نموذج إنشاء جلسة جديدة
     */
    public function create(CaseModel $case)
    {
        $this->authorize('createSession', $case);
        
        $sessionTypes = SessionType::all();
        $sessionStatuses = SessionStatus::all();
        
        return view('sessions.create', compact('case', 'sessionTypes', 'sessionStatuses'));
    }

    /**
     * حفظ جلسة جديدة
     */
    public function store(Request $request, CaseModel $case)
    {
        $this->authorize('createSession', $case);
        
        $validated = $request->validate([
            'session_type_id' => 'required|exists:session_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_time' => 'required|date|after_or_equal:now',
            'duration' => 'nullable|integer|min:1',
            'location' => 'nullable|string|max:255',
            'zoom_link' => 'nullable|url|max:255',
            'notes' => 'nullable|string',
            'status_id' => 'required|exists:session_statuses,id',
        ]);
        
        $validated['case_id'] = $case->id;
        
        $session = CourtSession::create($validated);
        
        // إرسال إشعارات للمشاركين في القضية (يمكن تنفيذها لاحقًا)
        
        return redirect()->route('cases.sessions.show', [$case, $session])
            ->with('success', 'تم إنشاء الجلسة بنجاح');
    }

    /**
     * عرض جلسة محددة
     */
    public function show(CaseModel $case, CourtSession $session)
    {
        $this->authorize('view', $case);
        
        // التأكد من أن الجلسة تنتمي للقضية
        if ($session->case_id !== $case->id) {
            abort(404);
        }
        
        $session->load(['sessionType', 'status']);
        
        return view('sessions.show', compact('case', 'session'));
    }

    /**
     * عرض نموذج تعديل جلسة
     */
    public function edit(CaseModel $case, CourtSession $session)
    {
        $this->authorize('updateSession', $case);
        
        // التأكد من أن الجلسة تنتمي للقضية
        if ($session->case_id !== $case->id) {
            abort(404);
        }
        
        $sessionTypes = SessionType::all();
        $sessionStatuses = SessionStatus::all();
        
        return view('sessions.edit', compact('case', 'session', 'sessionTypes', 'sessionStatuses'));
    }

    /**
     * تحديث جلسة
     */
    public function update(Request $request, CaseModel $case, CourtSession $session)
    {
        $this->authorize('updateSession', $case);
        
        // التأكد من أن الجلسة تنتمي للقضية
        if ($session->case_id !== $case->id) {
            abort(404);
        }
        
        $validated = $request->validate([
            'session_type_id' => 'required|exists:session_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_time' => 'required|date',
            'duration' => 'nullable|integer|min:1',
            'location' => 'nullable|string|max:255',
            'zoom_link' => 'nullable|url|max:255',
            'recording_url' => 'nullable|url|max:255',
            'notes' => 'nullable|string',
            'status_id' => 'required|exists:session_statuses,id',
        ]);
        
        $session->update($validated);
        
        // إرسال إشعارات للمشاركين في القضية بالتحديث (يمكن تنفيذها لاحقًا)
        
        return redirect()->route('cases.sessions.show', [$case, $session])
            ->with('success', 'تم تحديث الجلسة بنجاح');
    }

    /**
     * حذف جلسة
     */
    public function destroy(CaseModel $case, CourtSession $session)
    {
        $this->authorize('deleteSession', $case);
        
        // التأكد من أن الجلسة تنتمي للقضية
        if ($session->case_id !== $case->id) {
            abort(404);
        }
        
        $session->delete();
        
        return redirect()->route('cases.sessions.index', $case)
            ->with('success', 'تم حذف الجلسة بنجاح');
    }
    
    /**
     * تسجيل حضور الطالب للجلسة
     */
    public function attendSession(CaseModel $case, CourtSession $session)
    {
        // التأكد من أن الجلسة تنتمي للقضية
        if ($session->case_id !== $case->id) {
            abort(404);
        }
        
        // التحقق من أن المستخدم مشارك في القضية
        $isParticipant = $case->participants()->where('user_id', Auth::id())->exists() || 
                         $case->defendant_id === Auth::id() ||
                         $case->judge_id === Auth::id();
                         
        if (!$isParticipant) {
            return redirect()->route('cases.show', $case)
                ->with('error', 'غير مصرح لك بحضور هذه الجلسة');
        }
        
        // التحقق من أن الجلسة حالية
        if (!$session->isInProgress()) {
            return redirect()->route('cases.sessions.show', [$case, $session])
                ->with('error', 'الجلسة غير متاحة للحضور حاليًا');
        }
        
        // هنا يمكن تسجيل حضور الطالب (مثلاً من خلال جدول منفصل)
        // SessionAttendance::create([
        //     'session_id' => $session->id,
        //     'user_id' => Auth::id(),
        //     'attended_at' => now(),
        // ]);
        
        return view('sessions.attend', compact('case', 'session'));
    }
    
    /**
     * عرض الجلسات القادمة للمستخدم الحالي
     */
    public function upcomingSessions()
    {
        $user = Auth::user();
        
        // الحصول على القضايا التي يشارك فيها المستخدم
        $casesQuery = CaseModel::where(function ($query) use ($user) {
            $query->where('judge_id', $user->id)
                  ->orWhere('defendant_id', $user->id)
                  ->orWhereHas('participants', function ($q) use ($user) {
                      $q->where('user_id', $user->id);
                  });
        });
        
        // الحصول على الجلسات القادمة
        $upcomingSessions = CourtSession::whereIn('case_id', $casesQuery->pluck('id'))
            ->where('date_time', '>', now())
            ->with(['case', 'sessionType', 'status'])
            ->orderBy('date_time')
            ->paginate(10);
            
        return view('sessions.upcoming', compact('upcomingSessions'));
    }
} 