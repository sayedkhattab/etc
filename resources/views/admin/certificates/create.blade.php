@extends('admin.layouts.app')

@section('title', 'إصدار شهادة')

@section('content')
<div class="container-fluid">
 <div class="row">
  <div class="col-12">
   <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
     <h3 class="card-title mb-0">إصدار شهادة جديدة</h3>
     <a href="{{ route('admin.certificates.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-right"></i> العودة للقائمة</a>
    </div>
    <div class="card-body">
     @if($errors->any())
      <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
     @endif

     <form action="{{ route('admin.certificates.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="row">
       <div class="col-md-6 mb-3">
        <label class="form-label" for="student_id">الطالب <span class="text-danger">*</span></label>
        <select name="student_id" id="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
         <option value="">-- اختر الطالب --</option>
         @foreach($students as $student)
          <option value="{{ $student->id }}" {{ old('student_id')==$student->id?'selected':'' }}>{{ $student->name }}</option>
         @endforeach
        </select>
       </div>
       <div class="col-md-6 mb-3">
        <label class="form-label" for="course_id">الدورة <span class="text-danger">*</span></label>
        <select name="course_id" id="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
         <option value="">-- اختر الدورة --</option>
         @foreach($courses as $course)
          <option value="{{ $course->id }}" {{ old('course_id')==$course->id?'selected':'' }}>{{ $course->title }}</option>
         @endforeach
        </select>
       </div>
      </div>
      <div class="row">
       <div class="col-md-6 mb-3">
        <label class="form-label" for="issue_date">تاريخ الإصدار <span class="text-danger">*</span></label>
        <input type="date" name="issue_date" id="issue_date" class="form-control @error('issue_date') is-invalid @enderror" value="{{ old('issue_date', date('Y-m-d')) }}" required>
       </div>
       <div class="col-md-6 mb-3">
        <label class="form-label" for="expiry_date">تاريخ الانتهاء</label>
        <input type="date" name="expiry_date" id="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror" value="{{ old('expiry_date') }}">
       </div>
      </div>
      <div class="mb-3">
       <label class="form-label" for="certificate_file">ملف الشهادة (PDF أو صورة)</label>
       <input type="file" name="certificate_file" id="certificate_file" class="form-control @error('certificate_file') is-invalid @enderror">
      </div>
      <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> إصدار</button>
     </form>
    </div>
   </div>
  </div>
 </div>
</div>
@endsection 