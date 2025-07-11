@extends('admin.layouts.app')

@section('title','تقارير المحكمة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header"><h3 class="card-title mb-0">الإحصائيات العامة للمحكمة</h3></div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-6 col-md-3">
                            <div class="border rounded p-3 h-100">
                                <i class="bi bi-briefcase-fill fs-2 text-primary mb-2"></i>
                                <h6 class="mb-1">إجمالي القضايا النشطة</h6>
                                <span class="fw-bold fs-4">{{ $stats['active_cases_total'] }}</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="border rounded p-3 h-100">
                                <i class="bi bi-check2-square fs-2 text-success mb-2"></i>
                                <h6 class="mb-1">القضايا المكتملة</h6>
                                <span class="fw-bold fs-4">{{ $stats['active_cases_completed'] }}</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="border rounded p-3 h-100">
                                <i class="bi bi-calendar-event fs-2 text-warning mb-2"></i>
                                <h6 class="mb-1">الجلسات القادمة</h6>
                                <span class="fw-bold fs-4">{{ $stats['upcoming_sessions'] }}</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="border rounded p-3 h-100">
                                <i class="bi bi-journal-text fs-2 text-info mb-2"></i>
                                <h6 class="mb-1">الأحكام الصادرة</h6>
                                <span class="fw-bold fs-4">{{ $stats['judgments_total'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 