@extends('layouts.app')

@section('title', $content->title ?? 'المحتوى')

@push('styles')
<!-- تأكد من تضمين Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    body {
        overflow-x: hidden;
    }
    
    .content-wrapper {
        display: flex;
        min-height: calc(100vh - 56px);
    }
    
    .main-content {
        flex-grow: 1;
        padding: 20px;
        width: 100%;
        max-width: 100%;
    }
    
    .video-container {
        position: relative;
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    video {
        width: 100%;
        border-radius: 8px;
        display: block;
    }
    
    .completion-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #28a745;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        z-index: 10;
    }
    
    .required-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: #dc3545;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        z-index: 10;
    }
    
    .completion-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    
    .completion-modal.show {
        display: flex;
    }
    
    .completion-modal-content {
        background-color: white;
        padding: 30px;
        border-radius: 10px;
        text-align: center;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }
    
    .completion-icon {
        font-size: 3rem;
        color: #28a745;
        margin-bottom: 20px;
    }
    
    /* تنسيق النافذة المنبثقة الجديدة */
    .modal-content {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }
    
    .modal-header.bg-success {
        padding: 15px 20px;
    }
    
    .completion-animation {
        position: relative;
    }
    
    .completion-animation .fa-trophy {
        animation: pulse 1.5s infinite alternate;
    }
    
    @keyframes pulse {
        from {
            transform: scale(1);
            opacity: 0.8;
        }
        to {
            transform: scale(1.1);
            opacity: 1;
        }
    }
    
    .completion-animation .progress {
        height: 8px;
        border-radius: 4px;
    }
    
    .completion-animation .progress-bar {
        animation: progressGlow 2s infinite alternate;
    }
    
    @keyframes progressGlow {
        from {
            box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
        }
        to {
            box-shadow: 0 0 15px rgba(40, 167, 69, 0.8);
        }
    }
    
    .btn-lg {
        padding: 12px 24px;
        font-weight: 600;
    }
    
    .progress-container {
        margin-bottom: 20px;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .progress-container .progress {
        height: 10px;
        margin-top: 5px;
    }
    
    .required-content-alert {
        border-right: 4px solid #dc3545;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .alert {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        position: relative;
        z-index: 100;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
        border-right: 4px solid #28a745;
    }

    /* تنسيق الإشعار الجديد */
    .completion-notification {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.3);
        z-index: 1050;
        text-align: center;
        max-width: 90%;
        width: 400px;
        border: 2px solid #28a745;
        animation: fadeIn 0.5s ease-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translate(-50%, -60%);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%);
        }
    }
    
    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translate(-50%, -50%);
        }
        to {
            opacity: 0;
            transform: translate(-50%, -40%);
        }
    }
    
    .completion-notification.hide {
        animation: fadeOut 0.5s ease-in forwards;
    }
    
    .completion-notification .notification-icon {
        font-size: 3rem;
        color: #28a745;
        margin-bottom: 15px;
        animation: pulse 1.5s infinite alternate;
    }
    
    .completion-notification .notification-title {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 10px;
        color: #28a745;
    }
    
    .completion-notification .notification-content {
        margin-bottom: 15px;
        color: #333;
    }
    
    .completion-notification .notification-actions {
        display: flex;
        justify-content: center;
        gap: 10px;
    }
    
    .page-title {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }
    
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .breadcrumb {
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .card {
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }
    
    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons .btn {
            width: 100%;
            margin-bottom: 5px;
        }
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <div class="main-content">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('student.courses.show', $course->id) }}">{{ $course->title }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $content->title }}</li>
            </ol>
        </nav>

        <h2 class="page-title">{{ $content->title }}</h2>

        @php
            $progress = \App\Models\StudentContentProgress::where('student_id', auth()->id())
                ->where('content_id', $content->id)
                ->first();
            
            // استخدام المتغير الذي تم تمريره من المتحكم
            $isRequired = isset($isRequiredContent) ? $isRequiredContent : false;
            
            // التحقق مما إذا كان هذا المحتوى مطلوبًا من خلال جلسة الطلب
            $requiredContents = session('required_contents', []);
            if (!$isRequired && in_array($content->id, $requiredContents)) {
                $isRequired = true;
            }
            
            // التحقق مما إذا كان هناك أسئلة فشل مرتبطة بهذا المحتوى
            $hasFailedQuestions = \App\Models\FailedQuestion::where('student_id', auth()->id())
                ->whereHas('question', function($query) use ($content) {
                    $query->where('content_id', $content->id);
                })
                ->where('resolved', false)
                ->exists();
                
            if ($hasFailedQuestions) {
                $isRequired = true;
                
                // الحصول على الأسئلة التي فشل فيها الطالب والمرتبطة بهذا المحتوى
                $failedQuestions = \App\Models\FailedQuestion::where('student_id', auth()->id())
                    ->whereHas('question', function($query) use ($content) {
                        $query->where('content_id', $content->id);
                    })
                    ->where('resolved', false)
                    ->with('question')
                    ->get();
            }
        @endphp

        @if(session('warning'))
            <div class="alert alert-warning required-content-alert mb-4">
                <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
            </div>
        @endif

        <div class="video-container mb-4">
            @if($progress && $progress->fully_watched)
                <div class="completion-badge">
                    <i class="fas fa-check-circle"></i> تم الإكمال
                </div>
            @endif
            
            @if($isRequired)
                <div class="required-badge">
                    <i class="fas fa-exclamation-circle"></i> محتوى إجباري
                </div>
            @endif
            
            <video id="videoPlayer" controls poster="{{ $content->poster_url ?? '' }}">
                <source src="{{ $content->content_url ? asset('storage/'.$content->content_url) : $content->content_url_external }}" type="video/mp4">
                متصفحك لا يدعم تشغيل الفيديو.
            </video>
        </div>

        @if($isRequired)
            <div class="alert alert-danger required-content-alert mb-4">
                <h5><i class="fas fa-exclamation-triangle"></i> هذا محتوى إجباري</h5>
                @if(isset($passedPreTest) && !$passedPreTest)
                    <p>يجب عليك مشاهدة هذا المحتوى بالكامل لتحسين فهمك للمواضيع التي رسبت فيها في اختبار تحديد المستوى.</p>
                @else
                    <p>هذا محتوى إجباري للدورة. يُفضل مشاهدته للاستفادة القصوى من الدورة.</p>
                @endif
                
                @if(isset($failedQuestions) && $failedQuestions->isNotEmpty())
                    <hr>
                    <h6>هذا المحتوى مرتبط بالأسئلة التالية التي أخطأت فيها:</h6>
                    <ul class="mt-2">
                        @foreach($failedQuestions as $failedQuestion)
                            <li>{{ $failedQuestion->question->question_text ?? 'سؤال غير معروف' }}</li>
                        @endforeach
                    </ul>
                    <div class="mt-3">
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" style="width: 100%"></div>
                        </div>
                        <p class="mt-2 text-danger">
                            <i class="fas fa-info-circle"></i>
                            بعد مشاهدة هذا المحتوى بالكامل، سيتم تحديث حالة هذه الأسئلة تلقائياً.
                        </p>
                    </div>
                @endif
            </div>
        @elseif(isset($passedPreTest) && !$passedPreTest)
            <div class="alert alert-info mb-4">
                <h5><i class="fas fa-info-circle"></i> معلومات</h5>
                <p>لقد رسبت في اختبار تحديد المستوى، ولكن يمكنك مشاهدة هذا المحتوى لتحسين فهمك.</p>
            </div>
        @elseif(isset($passedPreTest) && $passedPreTest)
            <div class="alert alert-success mb-4">
                <h5><i class="fas fa-check-circle"></i> معلومات</h5>
                <p>لقد اجتزت اختبار تحديد المستوى بنجاح! يمكنك مشاهدة هذا المحتوى للاستفادة منه أو الانتقال للمستوى التالي.</p>
            </div>
        @endif

        @if($progress)
            <div class="progress-container">
                <div class="d-flex justify-content-between">
                    <span>نسبة المشاهدة</span>
                    <span>{{ $progress->fully_watched ? '100%' : floor(($progress->watched_seconds / max(1, $progress->duration_seconds)) * 100) . '%' }}</span>
                </div>
                <div class="progress">
                    <div class="progress-bar {{ $progress->fully_watched ? 'bg-success' : 'bg-primary' }}" 
                        role="progressbar" 
                        style="width: {{ $progress->fully_watched ? '100%' : floor(($progress->watched_seconds / max(1, $progress->duration_seconds)) * 100) . '%' }}" 
                        aria-valuenow="{{ $progress->fully_watched ? '100' : floor(($progress->watched_seconds / max(1, $progress->duration_seconds)) * 100) }}" 
                        aria-valuemin="0" 
                        aria-valuemax="100"></div>
                </div>
            </div>
        @endif

        @if($content->description)
            <div class="card mb-4">
                <div class="card-body">
                    {!! nl2br(e($content->description)) !!}
                </div>
            </div>
        @endif

        <div class="action-buttons">
            <a href="{{ route('student.courses.show', $course->id) }}" class="btn btn-primary me-2">
                <i class="fas fa-th-list"></i> العودة إلى لوحة الدورة
            </a>
            
            <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-right"></i> رجوع
            </a>
            
            <!-- زر تسجيل الإكمال يدويًا -->
            <button id="manualComplete" class="btn btn-success me-2">
                <i class="fas fa-check-circle"></i> تأكيد إكمال المحتوى
            </button>
            
            <!-- زر إعادة تحميل الصفحة -->
            <button id="refreshPageBtn" class="btn btn-info">
                <i class="fas fa-sync-alt"></i> تحديث الصفحة
            </button>
        </div>
    </div>
</div>

<!-- Modal for completion celebration -->
<div id="completionModal" class="completion-modal">
    <div class="completion-modal-content">
        <div class="completion-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h3>تهانينا!</h3>
        <p>لقد أكملت مشاهدة هذا المحتوى بنجاح.</p>
        @if($isRequired)
            <p class="text-success"><strong><i class="fas fa-star"></i> تم تحديث تقدمك في المحتوى الإجباري!</strong></p>
        @endif
        <p>يمكنك الآن الانتقال إلى المحتوى التالي أو العودة إلى لوحة الدورة.</p>
        <div class="mt-4">
            <a href="{{ route('student.courses.show', $course->id) }}" class="btn btn-success">العودة إلى لوحة الدورة</a>
            <button type="button" class="btn btn-secondary ms-2" onclick="closeCompletionModal()">إغلاق</button>
        </div>
    </div>
</div>

<!-- Modal for video completion with options -->
<div class="modal fade" id="videoCompletionModal" tabindex="-1" aria-labelledby="videoCompletionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="videoCompletionModalLabel"><i class="fas fa-check-circle me-2"></i> تهانينا! لقد أكملت المشاهدة</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body text-center">
                <div class="completion-animation mb-4">
                    <i class="fas fa-trophy fa-4x text-warning mb-3"></i>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                
                <h4>أحسنت! لقد أتممت هذا المحتوى بنجاح</h4>
                <p class="text-muted">ماذا تريد أن تفعل الآن؟</p>
                
                <div class="d-grid gap-3 mt-4">
                    @php
                        // التحقق من وجود اختبار تحديد مستوى للمستوى الحالي
                        $hasPreTest = $level->assessments()->where('is_pretest', true)->exists();
                        $hasCompletedPreTest = false;
                        
                        if ($hasPreTest) {
                            $preTest = $level->assessments()->where('is_pretest', true)->first();
                            $hasCompletedPreTest = $preTest ? $preTest->studentAttempts()
                                ->where('student_id', auth()->id())
                                ->where('status', '=', 'completed')
                                ->exists() : false;
                        }
                    @endphp
                    
                    @if($hasPreTest && !$hasCompletedPreTest)
                        <a href="{{ route('levels.pretest', [$course->id, $level->id]) }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-question-circle me-2"></i> الإجابة على اختبار تحديد المستوى
                        </a>
                    @endif
                    
                    @php
                        $nextContent = $level->getNextContent($content->id);
                    @endphp
                    
                    @if($nextContent)
                        <a href="{{ route('student.contents.show', [$course->id, $level->id, $nextContent->id]) }}" class="btn btn-info btn-lg">
                            <i class="fas fa-forward me-2"></i> الانتقال إلى المحتوى التالي
                        </a>
                    @else
                        @php
                            $nextLevel = $course->getNextLevel($level->id);
                        @endphp
                        
                        @if($nextLevel)
                            <a href="{{ route('courses.levels.show', [$course->id, $nextLevel->id]) }}" class="btn btn-info btn-lg">
                                <i class="fas fa-level-up-alt me-2"></i> الانتقال إلى المستوى التالي
                            </a>
                        @endif
                    @endif
                    
                    <a href="{{ route('student.courses.show', $course->id) }}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-th-list me-2"></i> العودة إلى لوحة الدورة
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- تضمين مكتبة Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<!-- تضمين مكتبة Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // تهيئة Axios مع رمز CSRF
        const axiosInstance = window.axios || axios;
        
        // تهيئة رمز CSRF
        axiosInstance.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axiosInstance.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        
        const video = document.getElementById('videoPlayer');
        if (!video) return;

        const CONTENT_ID = {{ $content->id }};
        let lastSent = 0;
        let videoLoaded = false;
        let progressInterval;
        let completionShown = false;
        
        // تهيئة المودال باستخدام JavaScript
        const videoCompletionModal = new bootstrap.Modal(document.getElementById('videoCompletionModal'), {
            backdrop: 'static',
            keyboard: false
        });

        // إضافة معالج النقر لزر تحديث الصفحة
        const refreshPageBtn = document.getElementById('refreshPageBtn');
        if (refreshPageBtn) {
            refreshPageBtn.addEventListener('click', function() {
                window.location.reload();
            });
        }
        
        // تسجيل المحتوى كمكتمل يدوياً
        const manualCompleteBtn = document.getElementById('manualComplete');
        if (manualCompleteBtn) {
            manualCompleteBtn.addEventListener('click', function() {
                this.disabled = true;
                this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> جاري التسجيل...';
                
                fetch(`/contents/${CONTENT_ID}/progress`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        watched_seconds: Math.floor(video.duration || 100),
                        duration_seconds: Math.floor(video.duration || 100),
                        fully_watched: true
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('تم تسجيل الإكمال باستخدام fetch', data);
                    
                    // إضافة شارة الإكمال
                    if (!document.querySelector('.completion-badge')) {
                        const badge = document.createElement('div');
                        badge.className = 'completion-badge';
                        badge.innerHTML = '<i class="fas fa-check-circle"></i> تم الإكمال';
                        document.querySelector('.video-container').appendChild(badge);
                    }
                    
                    // تحديث شريط التقدم
                    updateProgressBar(100);
                    
                    // إظهار إشعار جديد في منتصف الصفحة
                    showCenteredNotification();
                    
                    // استعادة حالة الزر
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-check-circle"></i> تأكيد إكمال المحتوى';
                })
                .catch(error => {
                    console.error('خطأ في تسجيل الإكمال:', error);
                    alert('حدث خطأ أثناء تسجيل إكمال المحتوى. يرجى المحاولة مرة أخرى.');
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-check-circle"></i> تأكيد إكمال المحتوى';
                });
            });
        }

        // تحميل الفيديو وإعداد المدة
        video.addEventListener('loadedmetadata', function() {
            videoLoaded = true;
        });

        function sendProgress(seconds, completed = false, forceComplete = false) {
            if (!videoLoaded && !forceComplete) return;
            
            const duration = Math.floor(video.duration || 100);
            const watchedPercent = Math.floor((seconds / duration) * 100);
            const fullyWatched = completed || forceComplete || watchedPercent >= 90;
            
            // إضافة معلمة عشوائية لمنع التخزين المؤقت
            const timestamp = new Date().getTime();
            
            console.log(`إرسال تقدم المشاهدة: ${seconds}/${duration} (${watchedPercent}%) - مكتمل: ${fullyWatched}`);
            
            // إنشاء نسخة من البيانات للإرسال
            const progressData = {
                watched_seconds: seconds,
                duration_seconds: duration,
                fully_watched: fullyWatched
            };
            
            // إذا كان الفيديو مكتملاً، نتأكد من إرسال المدة الكاملة
            if (fullyWatched) {
                progressData.watched_seconds = duration;
            }
            
            // استخدام axiosInstance بدلاً من window.axios
            return axiosInstance.post(`/contents/${CONTENT_ID}/progress?_t=${timestamp}`, progressData)
            .then(response => {
                if (fullyWatched && !completionShown) {
                    completionShown = true;
                    console.log('تم تسجيل المشاهدة بنجاح');
                    
                    // إضافة شارة الإكمال إذا لم تكن موجودة
                    if (!document.querySelector('.completion-badge')) {
                        const badge = document.createElement('div');
                        badge.className = 'completion-badge';
                        badge.innerHTML = '<i class="fas fa-check-circle"></i> تم الإكمال';
                        document.querySelector('.video-container').appendChild(badge);
                    }
                    
                    // تحديث شريط التقدم
                    updateProgressBar(100);
                    
                    // عرض النافذة المنبثقة الجديدة بدلاً من النافذة القديمة
                    if (!forceComplete) {
                        try {
                            videoCompletionModal.show();
                        } catch (error) {
                            console.error('خطأ في عرض النافذة المنبثقة:', error);
                            showCompletionMessage();
                        }
                    }
                    
                    // التحقق مما إذا كان المستوى مكتملاً
                    if (response.data.level_completed) {
                        // يمكن إضافة إجراءات إضافية هنا إذا كان المستوى مكتملاً
                        console.log('تم إكمال المستوى بنجاح!');
                    }
                    
                    // إذا كان هذا محتوى إجباري وتم حل أسئلة مرتبطة به
                    if (response.data.is_required_content && response.data.resolved_questions > 0) {
                        // إضافة رسالة نجاح
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success mt-3';
                        alertDiv.innerHTML = `<i class="fas fa-check-circle"></i> تم تحديث حالة المحتوى الإجباري وحل ${response.data.resolved_questions} من الأسئلة المرتبطة.`;
                        document.querySelector('.video-container').after(alertDiv);
                        
                        // تحديث الصفحة بعد 3 ثوانٍ
                        setTimeout(() => {
                            window.location.href = "{{ route('student.courses.show', $course->id) }}?refresh=" + new Date().getTime();
                        }, 3000);
                    }
                } else if (!fullyWatched) {
                    // تحديث شريط التقدم حتى لو لم يكتمل الفيديو
                    updateProgressBar(watchedPercent);
                }
                
                return response;
            })
            .catch(err => {
                console.error('خطأ في تسجيل التقدم', err);
                
                // في حالة الخطأ وكان الفيديو مكتملاً، نحاول مرة أخرى بعد ثانية
                if (fullyWatched) {
                    console.log('إعادة المحاولة بعد ثانية...');
                    setTimeout(() => {
                        sendProgress(seconds, completed, forceComplete);
                    }, 1000);
                }
                
                return Promise.reject(err);
            });
        }

        // دالة مساعدة لتحديث شريط التقدم
        function updateProgressBar(percent) {
            const progressContainer = document.querySelector('.progress-container');
            if (progressContainer) {
                const progressBar = progressContainer.querySelector('.progress-bar');
                progressBar.style.width = `${percent}%`;
                progressBar.setAttribute('aria-valuenow', percent);
                
                if (percent >= 100) {
                    progressBar.classList.remove('bg-primary');
                    progressBar.classList.add('bg-success');
                    progressContainer.querySelector('.d-flex span:last-child').textContent = '100%';
                } else {
                    progressContainer.querySelector('.d-flex span:last-child').textContent = `${percent}%`;
                }
            }
        }

        function showCompletionMessage() {
            // إظهار المودال
            const modal = document.getElementById('completionModal');
            modal.classList.add('show');
            
            // إضافة شارة الإكمال إذا لم تكن موجودة
            if (!document.querySelector('.completion-badge')) {
                const badge = document.createElement('div');
                badge.className = 'completion-badge';
                badge.innerHTML = '<i class="fas fa-check-circle"></i> تم الإكمال';
                document.querySelector('.video-container').appendChild(badge);
            }
            
            // إضافة رسالة نجاح إذا لم تكن موجودة
            if (!document.querySelector('.alert-success')) {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success w-100 mb-3';
                alertDiv.innerHTML = '<i class="fas fa-check-circle"></i> لقد أكملت مشاهدة هذا المحتوى بنجاح.';
                
                @if($isRequired)
                alertDiv.innerHTML += '<br><small>تم تحديث تقدمك في المحتوى الإجباري.</small>';
                @endif
                
                // إضافة الإشعار في المكان المناسب
                const videoContainer = document.querySelector('.video-container');
                if (videoContainer) {
                    videoContainer.parentNode.insertBefore(alertDiv, videoContainer.nextSibling);
                }
            }
            
            // تحديث شريط التقدم
            const progressContainer = document.querySelector('.progress-container');
            if (progressContainer) {
                const progressBar = progressContainer.querySelector('.progress-bar');
                progressBar.style.width = '100%';
                progressBar.setAttribute('aria-valuenow', '100');
                progressBar.classList.remove('bg-primary');
                progressBar.classList.add('bg-success');
                progressContainer.querySelector('.d-flex span:last-child').textContent = '100%';
            }
        }
        
        function showLevelCompletionMessage() {
            // إظهار المودال الخاص بإكمال المستوى
            const modal = document.getElementById('completionModal');
            const modalContent = modal.querySelector('.completion-modal-content');
            
            modalContent.innerHTML = `
                <div class="completion-icon">
                    <i class="fas fa-trophy text-warning"></i>
                </div>
                <h3>تهانينا!</h3>
                <p>لقد أكملت جميع المحتويات المطلوبة في هذا المستوى.</p>
                @if($isRequired)
                <p class="text-success"><strong><i class="fas fa-star"></i> تم تحديث تقدمك في المحتوى الإجباري!</strong></p>
                @endif
                <p>سيتم توجيهك إلى لوحة الدورة خلال 5 ثوانٍ...</p>
                <div class="mt-4">
                    <a href="{{ route('student.courses.show', $course->id) }}" class="btn btn-success">العودة الآن إلى لوحة الدورة</a>
                </div>
            `;
            
            modal.classList.add('show');
            
            // إضافة شارة الإكمال
            if (!document.querySelector('.completion-badge')) {
                const badge = document.createElement('div');
                badge.className = 'completion-badge';
                badge.innerHTML = '<i class="fas fa-check-circle"></i> تم الإكمال';
                document.querySelector('.video-container').appendChild(badge);
            }
            
            // إضافة رسالة نجاح
            if (!document.querySelector('.alert-success')) {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success w-100 mb-3';
                alertDiv.innerHTML = '<i class="fas fa-trophy"></i> تهانينا! لقد أكملت جميع المحتويات المطلوبة في هذا المستوى.';
                
                // إضافة الإشعار في المكان المناسب
                const videoContainer = document.querySelector('.video-container');
                if (videoContainer) {
                    videoContainer.parentNode.insertBefore(alertDiv, videoContainer.nextSibling);
                }
            }
            
            // توجيه تلقائي بعد 5 ثوانٍ
            setTimeout(() => {
                window.location.href = "{{ route('student.courses.show', $course->id) }}";
            }, 5000);
        }

        // إرسال التقدم كل 15 ثانية أثناء المشاهدة
        video.addEventListener('play', () => {
            // إرسال التقدم مباشرة عند بدء التشغيل
            sendProgress(Math.floor(video.currentTime), false);
            
            // إرسال التقدم بشكل متكرر
            progressInterval = setInterval(() => {
                if (!video.paused) {
                    const current = Math.floor(video.currentTime);
                    const duration = Math.floor(video.duration || 1);
                    const watchedPercent = Math.floor((current / duration) * 100);
                    
                    // إرسال التقدم الحالي
                    sendProgress(current, watchedPercent >= 90);
                    
                    // إذا وصل التقدم إلى 90% أو أكثر، نعتبره مكتملًا
                    if (watchedPercent >= 90 && !completionShown) {
                        completionShown = true;
                        console.log('تم الوصول إلى 90% من المحتوى، سيتم اعتباره مكتملًا');
                        
                        // تسجيل المحتوى كمكتمل بشكل قطعي
                        sendProgress(duration, true, true)
                            .then(response => {
                                // إضافة رسالة تنبيه صغيرة في أعلى مشغل الفيديو
                                const alertDiv = document.createElement('div');
                                alertDiv.className = 'alert alert-success mt-2';
                                alertDiv.innerHTML = '<i class="fas fa-check-circle"></i> تم تسجيل المحتوى كمكتمل. يمكنك الضغط على زر "تأكيد إكمال المحتوى" لعرض الخيارات التالية.';
                                
                                const videoContainer = document.querySelector('.video-container');
                                if (!document.querySelector('.alert-success') && videoContainer) {
                                    videoContainer.parentNode.insertBefore(alertDiv, videoContainer.nextSibling);
                                }
                                
                                // تحديث واجهة المستخدم لتعكس الإكمال
                                if (!document.querySelector('.completion-badge')) {
                                    const badge = document.createElement('div');
                                    badge.className = 'completion-badge';
                                    badge.innerHTML = '<i class="fas fa-check-circle"></i> تم الإكمال';
                                    videoContainer.appendChild(badge);
                                }
                                
                                // إذا كان هذا محتوى إجباري وتم حل أسئلة مرتبطة به
                                if (response.data.is_required_content && response.data.resolved_questions > 0) {
                                    // إضافة رسالة نجاح إضافية
                                    const requiredAlertDiv = document.createElement('div');
                                    requiredAlertDiv.className = 'alert alert-success mt-2';
                                    requiredAlertDiv.innerHTML = `<i class="fas fa-check-circle"></i> تم تحديث حالة المحتوى الإجباري وحل ${response.data.resolved_questions} من الأسئلة المرتبطة.`;
                                    if (videoContainer) {
                                        videoContainer.parentNode.insertBefore(requiredAlertDiv, alertDiv.nextSibling);
                                    }
                                    
                                    // تحديث الصفحة بعد 3 ثوانٍ
                                    setTimeout(() => {
                                        window.location.href = "{{ route('student.courses.show', $course->id) }}?refresh=" + new Date().getTime();
                                    }, 3000);
                                }
                            });
                    }
                }
            }, 5000); // تقليل الفترة إلى 5 ثوانٍ بدلاً من 10 ثوانٍ
        });
        
        // إيقاف الإرسال عند توقف الفيديو
        video.addEventListener('pause', () => {
            clearInterval(progressInterval);
            // إرسال التقدم الحالي عند التوقف
            sendProgress(Math.floor(video.currentTime), false);
        });

        // تسجيل المشاهدة عند الانتهاء
        video.addEventListener('ended', function() {
            clearInterval(progressInterval);
            
            // تسجيل المحتوى كمكتمل بشكل قطعي مع إعادة المحاولة
            const markAsCompleted = () => {
                try {
                    console.log('محاولة تسجيل إكمال الفيديو...');
                    return sendProgress(Math.floor(video.duration || 100), true, true)
                        .then(response => {
                            console.log('تم تسجيل إكمال الفيديو بنجاح');
                            
                            // إضافة شارة الإكمال إذا لم تكن موجودة
                            if (!document.querySelector('.completion-badge')) {
                                const badge = document.createElement('div');
                                badge.className = 'completion-badge';
                                badge.innerHTML = '<i class="fas fa-check-circle"></i> تم الإكمال';
                                document.querySelector('.video-container').appendChild(badge);
                            }
                            
                            // تحديث شريط التقدم
                            updateProgressBar(100);
                            
                            // إظهار الإشعار الجديد بدلاً من المودال
                            showCenteredNotification();
                            
                            // إذا كان هذا محتوى إجباري وتم حل أسئلة مرتبطة به
                            if (response.data && response.data.is_required_content && response.data.resolved_questions > 0) {
                                // توجيه المستخدم إلى لوحة الدورة بعد 5 ثوانٍ
                                setTimeout(() => {
                                    window.location.href = "{{ route('student.courses.show', $course->id) }}?refresh=" + new Date().getTime();
                                }, 5000);
                            }
                            
                            return response;
                        })
                        .catch(error => {
                            console.error('خطأ في تسجيل إكمال الفيديو:', error);
                            // إعادة المحاولة بعد ثانية واحدة
                            setTimeout(markAsCompleted, 1000);
                        });
                } catch (error) {
                    console.error('خطأ غير متوقع في markAsCompleted:', error);
                    
                    // طريقة بديلة: تسجيل الإكمال بطريقة بسيطة
                    try {
                        // إعادة المحاولة بعد ثانية واحدة
                        setTimeout(() => {
                            // استخدام طريقة بديلة للتسجيل
                            fetch(`/contents/${CONTENT_ID}/progress`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    watched_seconds: Math.floor(video.duration || 100),
                                    duration_seconds: Math.floor(video.duration || 100),
                                    fully_watched: true
                                })
                            })
                            .then(response => {
                                console.log('تم تسجيل الإكمال باستخدام fetch');
                                showCenteredNotification();
                            })
                            .catch(error => {
                                console.error('فشل تسجيل الإكمال باستخدام fetch:', error);
                                alert('لم نتمكن من تسجيل إكمال الفيديو. يرجى الضغط على زر "تأكيد إكمال المحتوى" يدوياً.');
                            });
                        }, 1000);
                    } catch (fetchError) {
                        console.error('خطأ في محاولة استخدام fetch:', fetchError);
                        alert('لم نتمكن من تسجيل إكمال الفيديو. يرجى الضغط على زر "تأكيد إكمال المحتوى" يدوياً.');
                    }
                }
            };
            
            // بدء محاولة تسجيل الإكمال
            markAsCompleted();
        });
        
        // تسجيل المشاهدة عند مغادرة الصفحة إذا تم مشاهدة أكثر من 90٪
        window.addEventListener('beforeunload', () => {
            if (videoLoaded) {
                const current = Math.floor(video.currentTime);
                const duration = Math.floor(video.duration);
                const watchedPercent = Math.floor((current / duration) * 100);
                
                if (watchedPercent >= 90) {
                    sendProgress(current, true);
                } else {
                    sendProgress(current, false);
                }
            }
        });
        
        // إضافة تتبع النقر على أزرار النافذة المنبثقة
        document.querySelectorAll('#videoCompletionModal .btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // تسجيل المحتوى كمكتمل قبل الانتقال
                sendProgress(Math.floor(video.duration || 0), true, true)
                    .then(response => {
                        // تسجيل إحصائيات عن الخيار المحدد
                        console.log('تم اختيار:', this.textContent.trim());
                        
                        // إضافة تأخير قبل الانتقال للتأكد من إرسال الطلب
                        if (!this.getAttribute('data-bs-dismiss')) {
                            const href = this.getAttribute('href');
                            if (href) {
                                event.preventDefault();
                                
                                // إذا كان الرابط يؤدي إلى لوحة الدورة، نضيف معلمة لتحديث الصفحة
                                if (href.includes('student.courses.show')) {
                                    window.location.href = href + '?refresh=' + new Date().getTime();
                                } else {
                                    setTimeout(() => {
                                        window.location.href = href;
                                    }, 500);
                                }
                            }
                        }
                    });
            });
        });

        // تسجيل المحتوى كمكتمل يدوياً
        document.getElementById('manualComplete').addEventListener('click', function() {
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> جاري التسجيل...';
            
            fetch(`/contents/${CONTENT_ID}/progress`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    watched_seconds: Math.floor(video.duration || 100),
                    duration_seconds: Math.floor(video.duration || 100),
                    fully_watched: true
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('تم تسجيل الإكمال باستخدام fetch', data);
                
                // إضافة شارة الإكمال
                if (!document.querySelector('.completion-badge')) {
                    const badge = document.createElement('div');
                    badge.className = 'completion-badge';
                    badge.innerHTML = '<i class="fas fa-check-circle"></i> تم الإكمال';
                    document.querySelector('.video-container').appendChild(badge);
                }
                
                // تحديث شريط التقدم
                updateProgressBar(100);
                
                // إظهار إشعار جديد في منتصف الصفحة
                showCenteredNotification();
            })
            .catch(error => {
                console.error('خطأ في تسجيل الإكمال:', error);
                alert('حدث خطأ أثناء تسجيل إكمال المحتوى. يرجى المحاولة مرة أخرى.');
                this.disabled = false;
                this.innerHTML = 'تأكيد إكمال المحتوى';
            });
        });
    });
    
    function closeCompletionModal() {
        document.getElementById('completionModal').classList.remove('show');
    }

    // دالة لإظهار إشعار في منتصف الصفحة
    function showCenteredNotification() {
        // إزالة أي إشعارات سابقة
        const existingNotification = document.querySelector('.completion-notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        // إزالة أي إشعارات نجاح أخرى
        document.querySelectorAll('.alert-success').forEach(alert => {
            alert.remove();
        });
        
        // تحديد ما إذا كان المحتوى إجباريًا
        const isRequired = {{ $isRequired ? 'true' : 'false' }};
        
        // تحديد ما إذا كان الطالب قد اجتاز اختبار تحديد المستوى
        const passedPreTest = {{ isset($passedPreTest) && $passedPreTest ? 'true' : 'false' }};
        
        // إنشاء الإشعار الجديد
        const notification = document.createElement('div');
        notification.className = 'completion-notification';
        
        let notificationContent = '';
        
        if (isRequired) {
            if (passedPreTest) {
                // محتوى إجباري والطالب اجتاز اختبار تحديد المستوى
                notificationContent = `
                    <div class="notification-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="notification-title">تهانينا!</div>
                    <div class="notification-content">
                        لقد أكملت مشاهدة هذا المحتوى بنجاح.
                        <p class="text-success"><strong><i class="fas fa-star"></i> تم تحديث تقدمك في الدورة!</strong></p>
                    </div>
                `;
            } else {
                // محتوى إجباري والطالب لم يجتز اختبار تحديد المستوى
                notificationContent = `
                    <div class="notification-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="notification-title">تهانينا!</div>
                    <div class="notification-content">
                        لقد أكملت مشاهدة هذا المحتوى الإجباري بنجاح.
                        <p class="text-success"><strong><i class="fas fa-star"></i> تم تحديث تقدمك في المحتوى الإجباري!</strong></p>
                        <p>هذا سيساعدك في فهم المواضيع التي واجهت صعوبة فيها.</p>
                    </div>
                `;
            }
        } else {
            // محتوى غير إجباري
            notificationContent = `
                <div class="notification-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="notification-title">تهانينا!</div>
                <div class="notification-content">
                    لقد أكملت مشاهدة هذا المحتوى بنجاح.
                    <p>استمر في التقدم لإكمال الدورة.</p>
                </div>
            `;
        }
        
        // إضافة أزرار الإجراءات
        notificationContent += `
            <div class="notification-actions">
                <a href="{{ route('student.courses.show', $course->id) }}?refresh=${new Date().getTime()}" class="btn btn-success">العودة إلى لوحة الدورة</a>
                <button type="button" class="btn btn-secondary" onclick="closeNotification()">إغلاق</button>
            </div>
        `;
        
        notification.innerHTML = notificationContent;
        
        // إضافة الإشعار إلى الصفحة
        document.body.appendChild(notification);
        
        // إغلاق الإشعار تلقائياً بعد 10 ثوانٍ
        setTimeout(() => {
            closeNotification();
        }, 10000);
    }
    
    // دالة لإغلاق الإشعار
    function closeNotification() {
        const notification = document.querySelector('.completion-notification');
        if (notification) {
            notification.classList.add('hide');
            setTimeout(() => {
                notification.remove();
            }, 500);
        }
    }
</script>
@endpush 