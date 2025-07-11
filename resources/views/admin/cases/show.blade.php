@extends('admin.layouts.app')

@section('title', 'تفاصيل القضية')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">تفاصيل القضية</h3>
                    <a href="{{ route('admin.cases.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-right"></i> العودة للقائمة</a>
                </div>
                <div class="card-body">
                    <div class="mb-3"><strong>رقم القضية:</strong> {{ $case->case_number }}</div>
                    <div class="mb-3"><strong>القاضي:</strong> {{ $case->judge?->name }}</div>
                    <div class="mb-3"><strong>المحكمة:</strong> {{ $case->courtType?->name }}</div>
                    <div class="mb-3"><strong>الحالة:</strong> {{ $case->status?->name }}</div>
                    <div class="mb-3"><strong>تاريخ البدء:</strong> {{ $case->start_date?->format('Y-m-d') }}</div>
                    @if($case->close_date)
                    <div class="mb-3"><strong>تاريخ الإغلاق:</strong> {{ $case->close_date?->format('Y-m-d') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 