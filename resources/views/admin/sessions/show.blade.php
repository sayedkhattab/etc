@extends('admin.layouts.app')

@section('title', 'تفاصيل الجلسة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">تفاصيل الجلسة</h3>
                    <a href="{{ route('admin.sessions.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-right"></i> العودة للقائمة</a>
                </div>
                <div class="card-body">
                    <p><strong>القضية:</strong> {{ $session->case?->case_number }}</p>
                    <p><strong>العنوان:</strong> {{ $session->title }}</p>
                    <p><strong>النوع:</strong> {{ $session->sessionType?->name }}</p>
                    <p><strong>التاريخ:</strong> {{ $session->date_time?->format('Y-m-d H:i') }}</p>
                    <p><strong>المدة:</strong> {{ $session->duration }} دقيقة</p>
                    <p><strong>المكان:</strong> {{ $session->location }}</p>
                    <p><strong>الحالة:</strong> {{ $session->status?->name }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 