@extends('admin.layouts.app')

@section('title','تفاصيل المذكرة')

@section('content')
<div class="container-fluid"><div class="row"><div class="col-12"><div class="card">
<div class="card-header d-flex justify-content-between align-items-center"><h3 class="card-title mb-0">تفاصيل المذكرة</h3><a href="{{ route('admin.defense-entries.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-right"></i> العودة</a></div>
<div class="card-body">
<p><strong>القضية:</strong> {{ $entry->case?->case_number }}</p>
<p><strong>الطالب:</strong> {{ $entry->student?->name }}</p>
<p><strong>العنوان:</strong> {{ $entry->title }}</p>
<p><strong>الحالة:</strong> {{ $entry->status }}</p>
<p><strong>المحتوى:</strong></p><div class="border p-3">{!! nl2br(e($entry->content)) !!}</div>
@if($entry->attachments->count())
<hr><h5>المرفقات</h5>
<ul>@foreach($entry->attachments as $att)<li><a href="{{ asset('storage/'.$att->file_path) }}" target="_blank">{{ $att->file_name }}</a></li>@endforeach</ul>
@endif
</div></div></div></div></div>
@endsection 