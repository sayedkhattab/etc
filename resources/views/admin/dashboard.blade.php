@extends('admin.layouts.app')

@section('title', 'لوحة التحكم')

@section('styles')
<style>
    .activity-list .list-group-item {
        transition: all 0.2s ease;
        border-left: none;
        border-right: none;
    }
    
    .activity-list .list-group-item:hover {
        background-color: rgba(0, 0, 0, 0.01);
    }
    
    .activity-icon {
        transition: transform 0.2s ease;
    }
    
    .list-group-item:hover .activity-icon {
        transform: scale(1.1);
    }
</style>
@endsection

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h1>لوحة التحكم</h1>
            <p class="text-muted">مرحباً بك في لوحة تحكم منصة إثبات</p>
        </div>
        <div>
            <span class="text-muted">{{ now()->format('Y-m-d') }}</span>
        </div>
    </div>
    
    <!-- إحصائيات سريعة -->
    <div class="row mt-4">
        <div class="col-md-3 mb-4">
            <div class="card border-0 bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">المستخدمين</h6>
                            <h2 class="mb-0">{{ $stats['users'] }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="#" class="text-white text-decoration-none small">عرض التفاصيل <i class="bi bi-arrow-left"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card border-0 bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">الدورات</h6>
                            <h2 class="mb-0">{{ $stats['courses'] }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="bi bi-mortarboard"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="#" class="text-white text-decoration-none small">عرض التفاصيل <i class="bi bi-arrow-left"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card border-0 bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">القضايا</h6>
                            <h2 class="mb-0">{{ $stats['cases'] }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="bi bi-briefcase"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="#" class="text-white text-decoration-none small">عرض التفاصيل <i class="bi bi-arrow-left"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card border-0 bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">الشهادات</h6>
                            <h2 class="mb-0">{{ $stats['certificates'] }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="bi bi-award"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="#" class="text-white text-decoration-none small">عرض التفاصيل <i class="bi bi-arrow-left"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- الوصول السريع والنشاط الأخير -->
    <div class="row">
        <!-- الوصول السريع -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>الوصول السريع</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- صف أول -->
                        <div class="col-6 col-md-3">
                            <a href="{{ route('admin.users.create') }}" class="btn btn-light border d-flex flex-column align-items-center p-3 h-100 text-decoration-none">
                                <i class="bi bi-person-plus fs-3 mb-2"></i>
                                <span>إضافة مستخدم</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('admin.courses.create') }}" class="btn btn-light border d-flex flex-column align-items-center p-3 h-100 text-decoration-none">
                                <i class="bi bi-mortarboard-fill fs-3 mb-2"></i>
                                <span>إضافة دورة</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('admin.cases.create') }}" class="btn btn-light border d-flex flex-column align-items-center p-3 h-100 text-decoration-none">
                                <i class="bi bi-briefcase-fill fs-3 mb-2"></i>
                                <span>إضافة قضية</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('admin.sessions.create') }}" class="btn btn-light border d-flex flex-column align-items-center p-3 h-100 text-decoration-none">
                                <i class="bi bi-calendar-plus-fill fs-3 mb-2"></i>
                                <span>إضافة جلسة</span>
                            </a>
                        </div>
                        
                        <!-- صف ثاني -->
                        <div class="col-6 col-md-3">
                            <a href="{{ route('admin.assessments.create') }}" class="btn btn-light border d-flex flex-column align-items-center p-3 h-100 text-decoration-none">
                                <i class="bi bi-clipboard-check-fill fs-3 mb-2"></i>
                                <span>إضافة تقييم</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('admin.judgments.index') }}" class="btn btn-light border d-flex flex-column align-items-center p-3 h-100 text-decoration-none">
                                <i class="bi bi-journal-text fs-3 mb-2"></i>
                                <span>الأحكام القضائية</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('admin.certificates.create') }}" class="btn btn-light border d-flex flex-column align-items-center p-3 h-100 text-decoration-none">
                                <i class="bi bi-award-fill fs-3 mb-2"></i>
                                <span>إصدار شهادة</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('admin.settings.general') }}" class="btn btn-light border d-flex flex-column align-items-center p-3 h-100 text-decoration-none">
                                <i class="bi bi-gear-fill fs-3 mb-2"></i>
                                <span>الإعدادات</span>
                            </a>
                        </div>
                        
                        <!-- صف ثالث - البطاقات الإضافية -->
                        <div class="col-6 col-md-3">
                            <a href="{{ route('admin.defense-entries.index') }}" class="btn btn-light border d-flex flex-column align-items-center p-3 h-100 text-decoration-none">
                                <i class="bi bi-file-earmark-text-fill fs-3 mb-2"></i>
                                <span>مذكرات الدفاع</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-light border d-flex flex-column align-items-center p-3 h-100 text-decoration-none">
                                <i class="bi bi-bar-chart-fill fs-3 mb-2"></i>
                                <span>الإحصائيات</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('admin.assessments.index') }}" class="btn btn-light border d-flex flex-column align-items-center p-3 h-100 text-decoration-none">
                                <i class="bi bi-question-circle-fill fs-3 mb-2"></i>
                                <span>إدارة التقييمات</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('admin.settings.general') }}" class="btn btn-light border d-flex flex-column align-items-center p-3 h-100 text-decoration-none">
                                <i class="bi bi-cloud-arrow-up-fill fs-3 mb-2"></i>
                                <span>نسخة احتياطية</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- النشاط الأخير -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center bg-white">
                    <span class="fw-bold"><i class="bi bi-activity me-2"></i>النشاط الأخير</span>
                    <a href="#" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                </div>
                <div class="card-body p-0 activity-list">
                    <ul class="list-group list-group-flush">
                        @foreach($recentActivities as $activity)
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3 border-start-0 border-end-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-{{ $activity['color'] }} rounded-circle d-flex align-items-center justify-content-center me-3 text-white activity-icon" style="width: 40px; height: 40px; min-width: 40px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                    <i class="bi {{ $activity['icon'] }} fs-5"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-medium">{{ $activity['title'] }}</p>
                                    <small class="text-muted">{{ $activity['subject'] }}</small>
                                </div>
                            </div>
                            <small class="text-muted bg-light py-1 px-2 rounded-pill">{{ $activity['time'] }}</small>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- الجلسات القادمة والدورات النشطة -->
    <div class="row">
        <!-- الجلسات القادمة -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>الجلسات القادمة</span>
                    <a href="{{ route('admin.sessions.index') }}" class="btn btn-sm btn-outline-secondary">عرض الكل</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">رقم القضية</th>
                                    <th scope="col">التاريخ</th>
                                    <th scope="col">الوقت</th>
                                    <th scope="col">النوع</th>
                                    <th scope="col">الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcomingSessions as $session)
                                <tr>
                                    <td>#{{ $session->caseModel->id ?? 'N/A' }}</td>
                                    <td>{{ $session->date->format('Y-m-d') }}</td>
                                    <td>{{ $session->time }}</td>
                                    <td>{{ $session->sessionType->name ?? 'غير محدد' }}</td>
                                    <td><span class="badge bg-{{ $session->sessionStatus->color ?? 'warning' }}">{{ $session->sessionStatus->name ?? 'قيد الانتظار' }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">لا توجد جلسات قادمة</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- الدورات النشطة -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>الدورات النشطة</span>
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-outline-secondary">عرض الكل</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">اسم الدورة</th>
                                    <th scope="col">التصنيف</th>
                                    <th scope="col">الطلاب</th>
                                    <th scope="col">التقدم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeCourses as $course)
                                <tr>
                                    <td>{{ $course->title }}</td>
                                    <td>{{ $course->category_name }}</td>
                                    <td>{{ $course->students_count }}</td>
                                    <td>
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $course->progress }}%;" aria-valuenow="{{ $course->progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">لا توجد دورات نشطة</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 