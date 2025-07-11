@extends('admin.layouts.app')

@section('title', 'تفاصيل الحكم')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title mb-0">تفاصيل الحكم</h3>
          <a href="{{ route('admin.judgments.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-right"></i> العودة للقائمة</a>
        </div>
        <div class="card-body">
          <p><strong>القضية:</strong> {{ $judgment->case?->case_number }}</p>
          <p><strong>العنوان:</strong> {{ $judgment->title }}</p>
          <p><strong>النوع:</strong> {{ $judgment->judgmentType?->name }}</p>
          <p><strong>التاريخ:</strong> {{ $judgment->judgment_date?->format('Y-m-d') }}</p>
          <p><strong>الحالة:</strong> {{ $judgment->status }}</p>
          <p><strong>النص:</strong></p>
          <div class="border p-3">{!! nl2br(e($judgment->content)) !!}</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection 