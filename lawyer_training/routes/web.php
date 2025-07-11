<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\JudgeController;
use App\Http\Controllers\Auth\JudgeAuthController;
use App\Http\Controllers\Student\RequestController;
use App\Http\Controllers\Student\RequestController as StudentRequestController;
use App\Http\Controllers\Admin\RequestController as AdminRequestController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Judge\RequestController as JudgeRequestController;
use App\Http\Controllers\Judge\CaseController as JudgeCaseController;
use App\Http\Controllers\Student\CaseController as StudentCaseController;
use App\Http\Controllers\Judge\CaseDetailsController as JudgeCaseDetailsController;
use App\Http\Controllers\Student\CaseDetailsController as StudentCaseDetailsController;
use App\Http\Controllers\Admin\CaseController;
use App\Http\Controllers\Admin\CaseArchiveController;


// الصفحة الرئيسية
Route::get('/', function () {
    return view('frontend.home');
})->name('home'); // أضف اسم للمسار

// مسارات تسجيل دخول الإدمن
    Route::get('/admin/login', [AuthenticatedSessionController::class, 'createAdminLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'storeAdminLogin'])->name('admin.login.store');

// Define routes for judge login
    Route::get('/judge/login', [JudgeAuthController::class, 'showLoginForm'])->name('judge.login');
    Route::post('/judge/login', [JudgeAuthController::class, 'login'])->name('judge.login.submit');

// مجموعة مسارات الإدمن مع تطبيق الوسيط 'auth:web'
Route::middleware(['auth:web'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/judges', [JudgeController::class, 'index'])->name('admin.judges.index');
    Route::get('/admin/judges/create', [JudgeController::class, 'create'])->name('admin.judges.create');
    Route::post('/admin/judges', [JudgeController::class, 'store'])->name('admin.judges.store');
    Route::get('/admin/judges/{id}/edit', [JudgeController::class, 'edit'])->name('admin.judges.edit');
    Route::put('/admin/judges/{id}', [JudgeController::class, 'update'])->name('admin.judges.update');
    Route::delete('/admin/judges/{id}', [JudgeController::class, 'destroy'])->name('admin.judges.destroy');

    Route::get('/admin/requests', [AdminRequestController::class, 'index'])->name('admin.requests.index');
    Route::get('/admin/requests/{request}', [AdminRequestController::class, 'show'])->name('admin.requests.show');
    Route::patch('/admin/requests/{request}/approve', [AdminRequestController::class, 'approve'])->name('admin.requests.approve');
    Route::patch('/admin/requests/{request}/reject', [AdminRequestController::class, 'reject'])->name('admin.requests.reject');
    Route::get('requests/assign-users/{id}', [AdminRequestController::class, 'assignUsers'])->name('admin.requests.assignUsers');
    Route::post('requests/assign-users/{id}', [AdminRequestController::class, 'storeAssignments'])->name('admin.requests.storeAssignments');
    Route::get('/admin/download-attachment/{filename}', [AdminRequestController::class, 'downloadAttachment'])->name('admin.download.attachment');

    Route::get('/admin/employees', [EmployeeController::class, 'index'])->name('admin.employees.index');
    Route::get('/admin/employees/create', [EmployeeController::class, 'create'])->name('admin.employees.create');
    Route::post('/admin/employees', [EmployeeController::class, 'store'])->name('admin.employees.store');
    Route::get('/admin/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('admin.employees.edit');
    Route::put('/admin/employees/{id}', [EmployeeController::class, 'update'])->name('admin.employees.update');
    Route::delete('/admin/employees/{id}', [EmployeeController::class, 'destroy'])->name('admin.employees.destroy');

     // مسارات إدارة الطلاب
    Route::get('/admin/students', [StudentController::class, 'allStudents'])->name('admin.students.index');
    Route::get('/admin/students/{id}/edit', [StudentController::class, 'edit'])->name('admin.students.edit');
    Route::put('/admin/students/{id}', [StudentController::class, 'update'])->name('admin.students.update');
    Route::delete('/admin/students/{id}', [StudentController::class, 'destroy'])->name('admin.students.destroy');

    Route::get('/admin/cases', [CaseController::class, 'index'])->name('admin.cases.index');

    Route::get('/admin/case-archives', [CaseArchiveController::class, 'index'])->name('admin.case-archives.index');
    Route::get('/admin/case-archives/create', [CaseArchiveController::class, 'create'])->name('admin.case-archives.create');
    Route::post('/admin/case-archives', [CaseArchiveController::class, 'store'])->name('admin.case-archives.store');
});

// Define routes for judge dashboard
Route::middleware(['auth:judge'])->group(function () {
    Route::get('/judge/dashboard', [JudgeController::class, 'dashboard'])->name('judge.dashboard');
    Route::get('judges/requests', [JudgeRequestController::class, 'index'])->name('judge.requests.index');
    Route::get('judges/requests/{request}', [JudgeRequestController::class, 'show'])->name('judge.requests.show');
    Route::get('requests/{request}/schedule', [JudgeRequestController::class, 'schedule'])->name('judge.requests.schedule');
    Route::post('requests/{request}/schedule', [JudgeRequestController::class, 'storeSchedule'])->name('judge.requests.storeSchedule');
    Route::get('judges/cases', [JudgeCaseController::class, 'index'])->name('judge.cases.index');
    Route::get('judges/cases/{case}', [JudgeCaseController::class, 'show'])->name('judge.cases.show');
    Route::patch('judges/requests/{request}/move-to-cases', [JudgeRequestController::class, 'moveToCases'])->name('judge.requests.moveToCases');
    Route::post('judges/cases/{case}/update-zoom-link', [JudgeCaseController::class, 'updateZoomLink'])->name('judge.cases.updateZoomLink');

    // مسارات صفحات القضية الفرعية
    Route::get('judge/case_details/{case}', [JudgeCaseDetailsController::class, 'show'])->name('judge.case_details.show');
    Route::get('judge/case_details/{case}/first_defense', [JudgeCaseDetailsController::class, 'firstDefense'])->name('judge.case_details.first_defense');
    Route::get('judge/case_details/{case}/parties', [JudgeCaseDetailsController::class, 'parties'])->name('judge.case_details.parties');
    Route::get('judge/case_details/{case}/sessions', [JudgeCaseDetailsController::class, 'sessions'])->name('judge.case_details.sessions');
    Route::post('judge/case_details/{case}/sessions', [JudgeCaseDetailsController::class, 'storeSession'])->name('judge.case_details.store_session');
    // مسار عرض الأحكام للقاضي
    Route::get('judge/case_details/{case}/judgments', [JudgeCaseDetailsController::class, 'judgments'])->name('judge.case_details.judgments');
    Route::post('judge/case_details/{case}/judgments', [JudgeCaseDetailsController::class, 'storeJudgment'])->name('judge.case_details.store_judgment');

    Route::get('judge/case_details/{case}/requests', [JudgeCaseDetailsController::class, 'requests'])->name('judge.case_details.requests');
    Route::get('judge/case_details/{case}/procedures', [JudgeCaseDetailsController::class, 'procedures'])->name('judge.case_details.procedures');
    Route::get('judge/case_details/{case}/decisions', [JudgeCaseDetailsController::class, 'decisions'])->name('judge.case_details.decisions');
    Route::get('judge/case_details/{case}/judicial_costs', [JudgeCaseDetailsController::class, 'judicialCosts'])->name('judge.case_details.judicial_costs');
    Route::get('judge/case_details/{case}/attachments', [JudgeCaseDetailsController::class, 'attachments'])->name('judge.case_details.attachments');
    Route::get('judge/download/{caseId}/{filename}', [JudgeCaseDetailsController::class, 'downloadAttachment'])->name('judge.download.attachment');

    // مسارات طلبات الالتماس
    Route::get('judge/case_requests/{case}/reconsideration', [JudgeCaseDetailsController::class, 'reconsideration'])->name('judge.case_requests.reconsideration');
    Route::post('judge/case_requests/{case}/reconsideration', [JudgeCaseDetailsController::class, 'storeReconsideration'])->name('judge.case_requests.store_reconsideration');
    Route::patch('judge/case_requests/{case}/update', [JudgeCaseDetailsController::class, 'updateReconsideration'])->name('judge.case_requests.update');

    Route::get('judges/reconsideration_requests', [JudgeCaseDetailsController::class, 'showReconsiderationRequests'])->name('judges.reconsideration_requests');
    Route::post('judges/reconsideration_requests/{requestId}/accept', [JudgeCaseDetailsController::class, 'acceptReconsiderationRequest'])->name('judges.reconsideration_requests.accept');
    Route::post('judges/reconsideration_requests/{requestId}/reject', [JudgeCaseDetailsController::class, 'rejectReconsiderationRequest'])->name('judges.reconsideration_requests.reject');
    Route::get('/download-reconsideration/{caseId}/{filename}', [JudgeCaseDetailsController::class, 'downloadReconsiderationAttachment'])->name('download.reconsideration_attachment');
   
    // مسار لتحميل مرفقات مذكرة الدفاع الأولى
    Route::get('judge/download/first_defense/{caseId}/{filename}', [JudgeCaseDetailsController::class, 'downloadFirstDefenseAttachment'])->name('judge.download.first_defense_attachment');

    // مسار لتحميل مرفقات طلبات الالتماس
    Route::get('judge/download/reconsideration/{caseId}/{filename}', [JudgeCaseDetailsController::class, 'downloadReconsiderationAttachment'])->name('judge.download.reconsideration_attachment');

});

// مسارات تسجيل الدخول والتسجيل
    require __DIR__.'/auth.php';

// مسارات الطلاب
    Route::get('/register/student', [StudentController::class, 'create'])->name('register.student'); // استخدم StudentController هنا
    Route::post('/register/student', [StudentController::class, 'store'])->name('register.student.store'); // استخدم StudentController هنا

// مسارات تسجيل دخول الطلاب
    Route::get('/login/student', function () {
        return view('auth.student-login');
    })->name('login.student');
    Route::post('/login/student', [AuthenticatedSessionController::class, 'store'])->name('student.login');

// مسارات لوحة تحكم الطلاب
Route::middleware(['auth:student'])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'index'])->name('student.dashboard');
    Route::get('/student/requests', [StudentRequestController::class, 'index'])->name('student.requests.index');
    Route::get('/student/requests/create', [StudentRequestController::class, 'create'])->name('student.requests.create');
    Route::post('/student/requests', [StudentRequestController::class, 'store'])->name('student.requests.store');
    Route::get('/student/requests/{request}', [StudentRequestController::class, 'show'])->name('student.requests.show');
    Route::get('/student/requests/{request}/edit', [StudentRequestController::class, 'edit'])->name('student.requests.edit');
    Route::put('/student/requests/{request}', [StudentRequestController::class, 'update'])->name('student.requests.update');
    Route::delete('/student/requests/{request}', [StudentRequestController::class, 'destroy'])->name('student.requests.destroy');
    Route::get('/student/requests/download/{path}', [RequestController::class, 'download'])->name('student.requests.download');

    // مسارات القضايا
    Route::get('/student/cases', [StudentCaseController::class, 'index'])->name('student.cases.index');
    Route::get('/student/cases/{case}', [StudentCaseController::class, 'show'])->name('student.cases.show');

    // مسارات صفحات القضية الفرعية
    Route::get('student/case_details/{case}', [StudentCaseDetailsController::class, 'show'])->name('student.case_details.show');
    Route::get('student/case_details/{case}/parties', [StudentCaseDetailsController::class, 'parties'])->name('student.case_details.parties');
    Route::get('student/case_details/{case}/sessions', [StudentCaseDetailsController::class, 'sessions'])->name('student.case_details.sessions');
    Route::get('student/case_details/{case}/judgments', [StudentCaseDetailsController::class, 'judgments'])->name('student.case_details.judgments');
    Route::get('/student/download/judgment/{caseId}/{filename}', [StudentCaseDetailsController::class, 'downloadJudgmentAttachment'])->name('student.download.judgment_attachment');
    Route::get('student/case_details/{case}/requests', [StudentCaseDetailsController::class, 'requests'])->name('student.case_details.requests');
    Route::get('student/case_details/{case}/procedures', [StudentCaseDetailsController::class, 'procedures'])->name('student.case_details.procedures');
    Route::get('student/case_details/{case}/decisions', [StudentCaseDetailsController::class, 'decisions'])->name('student.case_details.decisions');
    Route::get('student/case_details/{case}/judicial_costs', [StudentCaseDetailsController::class, 'judicialCosts'])->name('student.case_details.judicial_costs');
    Route::get('student/case_details/{case}/attachments', [StudentCaseDetailsController::class, 'attachments'])->name('student.case_details.attachments');

    Route::get('student/case_details/{case}/first_defense', [StudentCaseDetailsController::class, 'firstDefense'])->name('student.case_details.first_defense');
    Route::post('student/case_details/{case}/first_defense', [StudentCaseDetailsController::class, 'storeFirstDefense'])->name('student.case_details.store_first_defense');

    Route::get('/download/{caseId}/{filename}', [StudentCaseDetailsController::class, 'downloadAttachment'])->name('download.attachment');

        // توجيه عرض صفحة طلب الالتماس للطالب
     Route::get('student/case_details/case_requests/{case}/reconsideration', [StudentCaseDetailsController::class, 'reconsideration'])->name('student.case_requests.reconsideration');
        // توجيه تخزين طلب الالتماس
    Route::post('student/case_details/case_requests/{case}/storeReconsideration', [StudentCaseDetailsController::class, 'storeReconsideration'])->name('student.case_requests.storeReconsideration');
// توجيه جديد لتحميل مرفقات طلب الالتماس
Route::get('/download-reconsideration/{caseId}/{filename}', [StudentCaseDetailsController::class, 'downloadReconsiderationAttachment'])->name('download.reconsideration_attachment');

});

    Route::get('/admin/students', [StudentController::class, 'allStudents'])->name('admin.students.index');
    Route::get('/admin/students/pending', [StudentController::class, 'showPendingStudents'])->name('admin.students.pending');
    Route::post('/admin/students/approve/{id}', [StudentController::class, 'approveStudent'])->name('admin.students.approve');
    Route::delete('/admin/students/reject/{id}', [StudentController::class, 'rejectStudent'])->name('admin.students.reject');


// مسارات الصفحة الشخصية
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
