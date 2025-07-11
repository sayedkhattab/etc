<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\CourseContentController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\PreTestController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\CourtSessionController;
use App\Http\Controllers\JudgmentController;
use App\Http\Controllers\DefenseEntryController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\CaseFileController;
use App\Http\Controllers\Admin\CaseFileCategoryController;
use App\Http\Controllers\Admin\CaseFileAttachmentController;
use App\Http\Controllers\Admin\ActiveCaseController;
use App\Http\Controllers\Admin\CourtUserController;
use App\Http\Controllers\Admin\CourtSessionController as AdminCourtSessionController;
use App\Http\Controllers\Admin\JudgmentController as AdminJudgmentController;
use App\Http\Controllers\Admin\CourtArchiveController;
use App\Http\Controllers\Admin\CourtReportController;
use App\Http\Controllers\Admin\CourtSettingController;
use App\Http\Controllers\Student\CaseMarketController;
use App\Http\Controllers\Student\MyCaseController;
use App\Http\Controllers\Student\DefenseController;
use App\Http\Controllers\Student\CourtSessionController as StudentCourtSessionController;
use App\Http\Controllers\Student\JudgmentController as StudentJudgmentController;
use App\Http\Controllers\Judge\AssignedCaseController;
use App\Http\Controllers\Judge\SessionController;
use App\Http\Controllers\Judge\DefenseReviewController;
use App\Http\Controllers\Judge\ReconsiderationController;
use App\Http\Controllers\StoreCaseController;
use App\Http\Controllers\StoreCasePurchaseController;
use App\Http\Controllers\ContentProgressController;
use App\Http\Controllers\TestContentProgressController;
use App\Models\Course;
use App\Http\Controllers\PaymentController; // إضافة لاستيراد المتحكم بالدفع
use App\Http\Controllers\DebugController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// تضمين مسارات متجر القضايا
require __DIR__.'/store.php';

// الصفحة الرئيسية
Route::get('/', function () {
    $courses = Course::where('status', 'active')
        ->with('category')
        ->latest()
        ->take(6)
        ->get();
    return view('welcome', compact('courses'));
})->name('home');

// مسارات المصادقة
Auth::routes();

// مسارات لوحة التحكم
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

// مسارات المنصة التعليمية
Route::middleware(['auth'])->group(function () {
    // إدارة الدورات التعليمية
    Route::resource('courses', CourseController::class);
    Route::get('course-catalog', [CourseController::class, 'catalog'])->name('courses.catalog');
    Route::post('courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');

    // لوحة الطالب للدورة
    Route::get('student/courses/{course}', [CourseController::class, 'studentShow'])->name('student.courses.show');
    Route::get('student/courses/{course}/completion', [CourseController::class, 'completion'])->name('student.courses.completion');
    
    // عرض المستوى للطالب
    Route::get('student/courses/{course}/levels/{level}', [LevelController::class, 'show'])->name('student.levels.show');
    
    // إدارة المستويات
    Route::resource('courses.levels', LevelController::class);
    Route::post('courses/{course}/levels/reorder', [LevelController::class, 'reorder'])->name('courses.levels.reorder');
    
    // إدارة محتوى الدورات
    Route::resource('courses.levels.contents', CourseContentController::class);
    Route::post('courses/{course}/levels/{level}/contents/reorder', [CourseContentController::class, 'reorder'])->name('courses.levels.contents.reorder');

    // اختبار تحديد المستوى قبل بدء الدرس
    Route::get('courses/{course}/levels/{level}/pre-test', [PreTestController::class, 'show'])->name('levels.pretest');
    Route::post('courses/{course}/levels/{level}/pre-test', [PreTestController::class, 'submit'])->name('levels.pretest.submit');
    
    // Student content view - access check is handled directly in the controller
    Route::get('student/courses/{course}/levels/{level}/contents/{content}', [CourseContentController::class, 'studentView'])->name('student.contents.show');
    
    // تسجيل تقدم المحتوى
    Route::post('contents/{content}/progress', [ContentProgressController::class, 'store'])->name('contents.progress.store');
    Route::post('test/content-progress', [ContentProgressController::class, 'testProgress'])->name('test.content.progress');
    
    // تصحيح الأخطاء
    Route::get('debug/failed-questions', [DebugController::class, 'showFailedQuestions'])->name('debug.failed-questions');
    
    // إدارة التقييمات
    Route::resource('courses.levels.assessments', AssessmentController::class);
    Route::get('student/courses/{course}/levels/{level}/assessments/{assessment}/take', [AssessmentController::class, 'takeAssessment'])->name('student.assessments.take');
    Route::post('student/courses/{course}/levels/{level}/assessments/{assessment}/submit/{attempt}', [AssessmentController::class, 'submitAssessment'])->name('student.assessments.submit');
    Route::get('student/courses/{course}/levels/{level}/assessments/{assessment}/results/{attempt}', [AssessmentController::class, 'showResults'])->name('student.assessments.results');
    
    // إدارة الأسئلة
    Route::resource('courses.levels.assessments.questions', QuestionController::class);
    
    // إدارة الشهادات
    Route::resource('certificates', CertificateController::class)->except(['edit', 'update']);
    Route::get('certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');
    Route::get('courses/{course}/certificates/create/{student?}', [CertificateController::class, 'create'])->name('courses.certificates.create');
    Route::post('courses/{course}/certificates', [CertificateController::class, 'store'])->name('courses.certificates.store');
    Route::get('student/certificates', [CertificateController::class, 'studentCertificates'])->name('student.certificates');
});

// مسارات المحكمة الافتراضية
Route::middleware(['auth'])->group(function () {
    // إدارة القضايا
    Route::resource('cases', CaseController::class);
    Route::post('cases/{case}/attachments', [CaseController::class, 'addAttachment'])->name('cases.attachments.store');
    Route::delete('cases/{case}/attachments/{attachment}', [CaseController::class, 'deleteAttachment'])->name('cases.attachments.destroy');
    Route::get('cases/{case}/attachments/{attachment}/download', [CaseController::class, 'downloadAttachment'])->name('cases.attachments.download');
    Route::post('cases/{case}/participants', [CaseController::class, 'addParticipant'])->name('cases.participants.store');
    Route::delete('cases/{case}/participants/{participant}', [CaseController::class, 'removeParticipant'])->name('cases.participants.destroy');
    
    // إدارة جلسات المحكمة
    Route::resource('cases.sessions', CourtSessionController::class);
    Route::get('cases/{case}/sessions/{session}/attend', [CourtSessionController::class, 'attendSession'])->name('cases.sessions.attend');
    Route::get('upcoming-sessions', [CourtSessionController::class, 'upcomingSessions'])->name('sessions.upcoming');
    
    // إدارة الأحكام القضائية
    Route::resource('cases.judgments', JudgmentController::class);
    Route::get('cases/{case}/judgments/{judgment}/attachments/{attachment}/download', [JudgmentController::class, 'downloadAttachment'])->name('cases.judgments.attachments.download');
    Route::delete('cases/{case}/judgments/{judgment}/attachments/{attachment}', [JudgmentController::class, 'deleteAttachment'])->name('cases.judgments.attachments.destroy');
    
    // إدارة المذكرات الدفاعية
    Route::resource('cases.defense_entries', DefenseEntryController::class);
    Route::get('cases/{case}/defense_entries/{defenseEntry}/attachments/{attachment}/download', [DefenseEntryController::class, 'downloadAttachment'])->name('cases.defense_entries.attachments.download');
    Route::delete('cases/{case}/defense_entries/{defenseEntry}/attachments/{attachment}', [DefenseEntryController::class, 'deleteAttachment'])->name('cases.defense_entries.attachments.destroy');
    Route::post('cases/{case}/defense_entries/{defenseEntry}/review', [DefenseEntryController::class, 'review'])->name('cases.defense_entries.review');
    
    // متجر القضايا للمستخدمين
    Route::get('store', [StoreCaseController::class, 'index'])->name('store.index');
    Route::get('store/cases/{case}', [StoreCaseController::class, 'show'])->name('store.show');
    Route::get('store/my-purchases', [StoreCaseController::class, 'myPurchases'])->name('store.my-purchases');
    Route::get('store/purchases/{purchase}', [StoreCaseController::class, 'viewPurchase'])->name('store.view-purchase');
    
    // عمليات الشراء في المتجر
    Route::post('store/cases/{case}/confirm', [StoreCasePurchaseController::class, 'confirm'])->name('store.purchase.confirm');
    Route::post('store/cases/{case}/process', [StoreCasePurchaseController::class, 'process'])->name('store.purchase.process');
    Route::get('store/purchases/{purchase}/success', [StoreCasePurchaseController::class, 'success'])->name('store.purchase.success');
    Route::get('store/purchases/{purchase}/bank-transfer', [StoreCasePurchaseController::class, 'bankTransfer'])->name('store.purchase.bank-transfer');
    Route::post('store/purchases/{purchase}/upload-receipt', [StoreCasePurchaseController::class, 'uploadReceipt'])->name('store.purchase.upload-receipt');
});

// مسارات لوحة تحكم المسؤولين
Route::prefix('admin')->name('admin.')->group(function () {
    // مسارات المصادقة للمسؤولين
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    
    // مسارات تسجيل الخروج
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    
    // مسارات لوحة التحكم المحمية
    Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
        // لوحة التحكم الرئيسية
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        
        // إدارة الملف الشخصي
        Route::get('profile', [AdminController::class, 'profile'])->name('profile');
        Route::put('profile', [AdminController::class, 'updateProfile'])->name('profile.update');
        
        // إدارة المسؤولين
        Route::resource('admins', AdminController::class);
        
        // إدارة المستخدمين
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['show']);
        
        // إدارة الأدوار والصلاحيات
        Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
        
        // إدارة الدورات التعليمية
        Route::resource('courses', \App\Http\Controllers\Admin\CourseController::class);
        Route::resource('categories', \App\Http\Controllers\Admin\CourseCategoryController::class);
        
        // إدارة المستويات والمحتوى
        Route::resource('courses.levels', \App\Http\Controllers\Admin\LevelController::class);
        Route::post('courses/{course}/levels/reorder', [\App\Http\Controllers\Admin\LevelController::class, 'reorder'])->name('courses.levels.reorder');
        Route::resource('courses.levels.contents', \App\Http\Controllers\Admin\CourseContentController::class);
        
        // إدارة التقييمات والأسئلة
        Route::resource('assessments', \App\Http\Controllers\Admin\AssessmentController::class);
        Route::resource('questions', \App\Http\Controllers\Admin\QuestionController::class);
        
        // تعريف صريح للمسارات المستخدمة في لوحة التحكم
        Route::get('assessments', [\App\Http\Controllers\Admin\AssessmentController::class, 'index'])->name('assessments.index');
        
        // إدارة الشهادات
        Route::resource('certificates', \App\Http\Controllers\Admin\CertificateController::class);
        Route::resource('certificate-templates', \App\Http\Controllers\Admin\CertificateTemplateController::class);
        
        // إدارة المحكمة الافتراضية
        Route::resource('cases', \App\Http\Controllers\Admin\CaseController::class);
        Route::resource('sessions', \App\Http\Controllers\Admin\CourtSessionController::class);
        Route::resource('judgments', \App\Http\Controllers\Admin\JudgmentController::class);
        
        // تعريف المسارات المستخدمة في لوحة التحكم بشكل صريح
        Route::get('defense-entries', [\App\Http\Controllers\Admin\DefenseEntryController::class, 'index'])->name('defense-entries.index');
        Route::get('defense-entries/create', [\App\Http\Controllers\Admin\DefenseEntryController::class, 'create'])->name('defense-entries.create');
        Route::post('defense-entries', [\App\Http\Controllers\Admin\DefenseEntryController::class, 'store'])->name('defense-entries.store');
        Route::get('defense-entries/{defense_entry}', [\App\Http\Controllers\Admin\DefenseEntryController::class, 'show'])->name('defense-entries.show');
        Route::get('defense-entries/{defense_entry}/edit', [\App\Http\Controllers\Admin\DefenseEntryController::class, 'edit'])->name('defense-entries.edit');
        Route::put('defense-entries/{defense_entry}', [\App\Http\Controllers\Admin\DefenseEntryController::class, 'update'])->name('defense-entries.update');
        Route::delete('defense-entries/{defense_entry}', [\App\Http\Controllers\Admin\DefenseEntryController::class, 'destroy'])->name('defense-entries.destroy');
        
        Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('questions/create', [\App\Http\Controllers\Admin\QuestionController::class, 'create'])->name('questions.create');
        Route::get('backup', [\App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backup');
        
        // إعدادات النظام
        Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.general');
        Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    });
});

// مسارات لوحة تحكم الإدارة
Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    // مسارات المحكمة الافتراضية
    
    // ملفات القضايا
    Route::resource('case-files', CaseFileController::class);
    Route::get('case-files/categories', [CaseFileCategoryController::class, 'index'])->name('case-files.categories.index');
    Route::get('case-files/categories/create', [CaseFileCategoryController::class, 'create'])->name('case-files.categories.create');
    Route::post('case-files/categories', [CaseFileCategoryController::class, 'store'])->name('case-files.categories.store');
    Route::get('case-files/categories/{category}/edit', [CaseFileCategoryController::class, 'edit'])->name('case-files.categories.edit');
    Route::put('case-files/categories/{category}', [CaseFileCategoryController::class, 'update'])->name('case-files.categories.update');
    Route::delete('case-files/categories/{category}', [CaseFileCategoryController::class, 'destroy'])->name('case-files.categories.destroy');
    Route::post('case-files/attachments/{caseFile}', [CaseFileAttachmentController::class, 'store'])->name('case-files.attachments.store');
    Route::delete('case-files/attachments/{attachment}', [CaseFileAttachmentController::class, 'destroy'])->name('case-files.attachments.destroy');
    Route::get('case-files/attachments/{attachment}/download', [CaseFileAttachmentController::class, 'download'])->name('case-files.attachments.download');
    
    // القضايا النشطة
    Route::resource('active-cases', ActiveCaseController::class);
    Route::get('active-cases/pending', [ActiveCaseController::class, 'pending'])->name('active-cases.pending');
    Route::get('active-cases/in-progress', [ActiveCaseController::class, 'inProgress'])->name('active-cases.in-progress');
    Route::get('active-cases/completed', [ActiveCaseController::class, 'completed'])->name('active-cases.completed');
    Route::post('active-cases/{activeCase}/assign-judge', [ActiveCaseController::class, 'assignJudge'])->name('active-cases.assign-judge');
    
    // مستخدمو المحكمة
    Route::get('court-users/judges', [CourtUserController::class, 'judges'])->name('court-users.judges.index');
    Route::get('court-users/students', [CourtUserController::class, 'students'])->name('court-users.students.index');
    Route::get('court-users/witnesses', [CourtUserController::class, 'witnesses'])->name('court-users.witnesses.index');
    
    // جلسات المحكمة
    Route::resource('court-sessions', AdminCourtSessionController::class);
    Route::get('court-sessions/upcoming', [AdminCourtSessionController::class, 'upcoming'])->name('court-sessions.upcoming');
    Route::get('court-sessions/completed', [AdminCourtSessionController::class, 'completed'])->name('court-sessions.completed');
    
    // الأحكام القضائية
    Route::resource('judgments', AdminJudgmentController::class);
    Route::get('judgments/reconsiderations', [AdminJudgmentController::class, 'reconsiderations'])->name('judgments.reconsiderations');
    
    // أرشيف القضايا
    Route::resource('court-archives', CourtArchiveController::class);
    
    // تقارير وإحصائيات
    Route::resource('court-reports', CourtReportController::class);
    
    // إعدادات المحكمة
    Route::resource('court-settings', CourtSettingController::class);
});

// تحديث تقدم مشاهدة المحتوى
Route::post('contents/{content}/progress', [ContentProgressController::class, 'store'])->middleware('auth')->name('contents.progress.store');

// مسار اختباري لتشخيص مشاكل تقدم المحتوى
Route::post('test/content-progress', [TestContentProgressController::class, 'testProgress'])->middleware('auth')->name('test.content.progress');

// مسارات لوحة تحكم الطلاب
Route::prefix('student')->name('student.')->middleware(['auth'])->group(function () {
    // متجر القضايا
    Route::get('case-market', [CaseMarketController::class, 'index'])->name('case-market.index');
    Route::get('case-market/{caseFile}', [CaseMarketController::class, 'show'])->name('case-market.show');
    Route::post('case-market/{caseFile}/purchase', [CaseMarketController::class, 'purchase'])->name('case-market.purchase');
    
    // القضايا الخاصة بي
    Route::get('my-cases', [MyCaseController::class, 'index'])->name('my-cases.index');
    Route::get('my-cases/{activeCase}', [MyCaseController::class, 'show'])->name('my-cases.show');
    
    // المذكرات الدفاعية
    Route::resource('defense', DefenseController::class);
    Route::post('defense/{defense}/attachments', [DefenseController::class, 'addAttachment'])->name('defense.attachments.store');
    Route::delete('defense/{defense}/attachments/{attachment}', [DefenseController::class, 'deleteAttachment'])->name('defense.attachments.destroy');
    
    // جلسات المحكمة
    Route::get('court-sessions', [StudentCourtSessionController::class, 'index'])->name('court-sessions.index');
    Route::get('court-sessions/{session}', [StudentCourtSessionController::class, 'show'])->name('court-sessions.show');
    Route::get('court-sessions/{session}/attend', [StudentCourtSessionController::class, 'attend'])->name('court-sessions.attend');
    
    // الأحكام القضائية
    Route::get('judgments', [StudentJudgmentController::class, 'index'])->name('judgments.index');
    Route::get('judgments/{judgment}', [StudentJudgmentController::class, 'show'])->name('judgments.show');
    Route::post('judgments/{judgment}/reconsideration', [StudentJudgmentController::class, 'requestReconsideration'])->name('judgments.reconsideration');
});

// مسارات لوحة تحكم القضاة
Route::prefix('judge')->name('judge.')->middleware(['auth'])->group(function () {
    // القضايا المسندة
    Route::get('assigned-cases', [AssignedCaseController::class, 'index'])->name('assigned-cases.index');
    Route::get('assigned-cases/{activeCase}', [AssignedCaseController::class, 'show'])->name('assigned-cases.show');
    
    // جلسات المحكمة
    Route::resource('sessions', SessionController::class);
    Route::post('sessions/{session}/start', [SessionController::class, 'start'])->name('sessions.start');
    Route::post('sessions/{session}/end', [SessionController::class, 'end'])->name('sessions.end');
    Route::post('sessions/{session}/minutes', [SessionController::class, 'updateMinutes'])->name('sessions.minutes');
    
    // مراجعة المذكرات الدفاعية
    Route::get('defense-reviews', [DefenseReviewController::class, 'index'])->name('defense-reviews.index');
    Route::get('defense-reviews/{defense}', [DefenseReviewController::class, 'show'])->name('defense-reviews.show');
    Route::post('defense-reviews/{defense}/feedback', [DefenseReviewController::class, 'provideFeedback'])->name('defense-reviews.feedback');
    
    // طلبات إعادة النظر
    Route::resource('reconsiderations', ReconsiderationController::class);
    Route::post('reconsiderations/{reconsideration}/approve', [ReconsiderationController::class, 'approve'])->name('reconsiderations.approve');
    Route::post('reconsiderations/{reconsideration}/reject', [ReconsiderationController::class, 'reject'])->name('reconsiderations.reject');
});

// أضف مسارات الدفع باستخدام PayMob في نهاية الملف أو موقع مناسب
Route::middleware(['auth'])->group(function () {
    Route::post('courses/{course}/checkout', [PaymentController::class, 'generatePaymentLink'])->name('checkout');
});

// مسارات رد باي موب (لا تتطلب مصادقة)
Route::match(['get', 'post'], 'payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::view('payment/success', 'payments.success')->name('payment.success');
Route::view('payment/failed', 'payments.failed')->name('payment.failed');
