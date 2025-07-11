@extends('layouts.app')

@section('title', 'لوحة التحكم - إثبات')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">لوحة التحكم</h2>
    
    <div class="row">
        <!-- إحصائيات سريعة -->
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">الدورات المسجلة</h6>
                            <h2 class="mb-0 mt-2">{{ $enrolledCoursesCount ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('courses.catalog') }}" class="text-white stretched-link">عرض التفاصيل</a>
                    <i class="fas fa-angle-left text-white"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">الشهادات</h6>
                            <h2 class="mb-0 mt-2">{{ $certificatesCount ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-certificate fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('student.certificates') }}" class="text-white stretched-link">عرض التفاصيل</a>
                    <i class="fas fa-angle-left text-white"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">القضايا النشطة</h6>
                            <h2 class="mb-0 mt-2">{{ $activeCasesCount ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-gavel fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('cases.index') }}" class="text-white stretched-link">عرض التفاصيل</a>
                    <i class="fas fa-angle-left text-white"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">الجلسات القادمة</h6>
                            <h2 class="mb-0 mt-2">{{ $upcomingSessionsCount ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('sessions.upcoming') }}" class="text-white stretched-link">عرض التفاصيل</a>
                    <i class="fas fa-angle-left text-white"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- الدورات التعليمية -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">الدورات التعليمية</h5>
                    <a href="{{ route('courses.catalog') }}" class="btn btn-sm btn-primary">عرض الكل</a>
                </div>
                <div class="card-body">
                    @if(isset($enrolledCourses) && $enrolledCourses->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($enrolledCourses as $course)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $course->title }}</h6>
                                        <div class="progress" style="height: 5px; width: 200px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $course->pivot->progress_percentage }}%"></div>
                                        </div>
                                        <small class="text-muted">التقدم: {{ $course->pivot->progress_percentage }}%</small>
                                    </div>
                                    <a href="#" class="btn btn-sm btn-outline-primary">متابعة</a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                            <p>لم تسجل في أي دورة بعد</p>
                            <a href="{{ route('courses.catalog') }}" class="btn btn-primary">استعرض الدورات المتاحة</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- القضايا النشطة -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">القضايا النشطة</h5>
                    <a href="{{ route('cases.index') }}" class="btn btn-sm btn-primary">عرض الكل</a>
                </div>
                <div class="card-body">
                    @if(isset($activeCases) && $activeCases->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($activeCases as $case)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $case->title }}</h6>
                                        <span class="badge bg-{{ $case->status->color ?? 'secondary' }}">{{ $case->status->name ?? 'غير محدد' }}</span>
                                        <small class="text-muted">آخر تحديث: {{ $case->updated_at->diffForHumans() }}</small>
                                    </div>
                                    <a href="{{ route('cases.show', $case->id) }}" class="btn btn-sm btn-outline-primary">عرض</a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-gavel fa-3x text-muted mb-3"></i>
                            <p>لا توجد قضايا نشطة حاليًا</p>
                            <a href="{{ route('cases.index') }}" class="btn btn-primary">عرض جميع القضايا</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- الجلسات القادمة -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">الجلسات القادمة</h5>
                    <a href="{{ route('sessions.upcoming') }}" class="btn btn-sm btn-primary">عرض الكل</a>
                </div>
                <div class="card-body">
                    @if(isset($upcomingSessions) && $upcomingSessions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>القضية</th>
                                        <th>نوع الجلسة</th>
                                        <th>التاريخ</th>
                                        <th>الوقت</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingSessions as $session)
                                        <tr>
                                            <td>{{ $session->case->title }}</td>
                                            <td>{{ $session->type->name }}</td>
                                            <td>{{ $session->date->format('Y-m-d') }}</td>
                                            <td>{{ $session->time->format('H:i') }}</td>
                                            <td><span class="badge bg-{{ $session->status->color }}">{{ $session->status->name }}</span></td>
                                            <td>
                                                <a href="{{ route('cases.sessions.show', [$session->case_id, $session->id]) }}" class="btn btn-sm btn-primary">عرض</a>
                                                @if($session->is_online && $session->status->name === 'قادمة')
                                                    <a href="{{ route('cases.sessions.attend', [$session->case_id, $session->id]) }}" class="btn btn-sm btn-success">حضور</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                            <p>لا توجد جلسات قادمة حاليًا</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 