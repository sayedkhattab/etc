@extends('admin.layouts.app')

@section('title', 'تفاصيل الشهادة')

@section('content')
<div class="container-fluid">
  <div class="row"><div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">تفاصيل الشهادة</h3>
        <a href="{{ route('admin.certificates.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-right"></i> العودة</a>
      </div>
      <div class="card-body">
        <p><strong>رقم الشهادة:</strong> {{ $certificate->certificate_number }}</p>
        <p><strong>الطالب:</strong> {{ $certificate->student?->name }}</p>
        <p><strong>الدورة:</strong> {{ $certificate->course?->title }}</p>
        <p><strong>تاريخ الإصدار:</strong> {{ $certificate->issue_date?->format('Y-m-d') }}</p>
        @if($certificate->expiry_date)
        <p><strong>تاريخ الانتهاء:</strong> {{ $certificate->expiry_date?->format('Y-m-d') }}</p>
        @endif
        @if($certificate->certificate_file)
        <a class="btn btn-success" href="{{ asset('storage/'.$certificate->certificate_file) }}" target="_blank">تحميل الشهادة</a>
        @endif
      </div>
    </div>
  </div></div>
</div>
@endsection 