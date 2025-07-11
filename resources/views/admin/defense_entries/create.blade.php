@extends('admin.layouts.app')

@section('title','إضافة مذكرة دفاعية')

@section('content')
<div class="container-fluid"><div class="row"><div class="col-12"><div class="card">
<div class="card-header d-flex justify-content-between align-items-center"><h3 class="card-title mb-0">إضافة مذكرة دفاعية</h3><a href="{{ route('admin.defense-entries.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-right"></i> العودة</a></div>
<div class="card-body">
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul></div>@endif
<form action="{{ route('admin.defense-entries.store') }}" method="POST" enctype="multipart/form-data">@csrf
<div class="row"><div class="col-md-6 mb-3"><label class="form-label" for="case_id">القضية <span class="text-danger">*</span></label><select name="case_id" id="case_id" class="form-select" required><option value="">-- اختر --</option>@foreach($cases as $case)<option value="{{ $case->id }}" {{ old('case_id')==$case->id?'selected':'' }}>{{ $case->case_number }}</option>@endforeach</select></div>
<div class="col-md-6 mb-3"><label class="form-label" for="student_id">الطالب <span class="text-danger">*</span></label><select name="student_id" id="student_id" class="form-select" required><option value="">-- اختر --</option>@foreach($students as $stu)<option value="{{ $stu->id }}" {{ old('student_id')==$stu->id?'selected':'' }}>{{ $stu->name }}</option>@endforeach</select></div></div>
<div class="mb-3"><label class="form-label" for="title">العنوان <span class="text-danger">*</span></label><input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required></div>
<div class="mb-3"><label class="form-label" for="content">المحتوى <span class="text-danger">*</span></label><textarea name="content" id="content" rows="5" class="form-control" required>{{ old('content') }}</textarea></div>
<div class="mb-3"><label class="form-label" for="status">الحالة</label><select name="status" id="status" class="form-select"><option value="draft" {{ old('status')=='draft'?'selected':'' }}>مسودة</option><option value="submitted" {{ old('status')=='submitted'?'selected':'' }}>مقدمة</option></select></div>
<div class="mb-3"><label class="form-label" for="attachments">المرفقات</label><input type="file" name="attachments[]" multiple class="form-control"></div>
<button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> حفظ</button></form>
</div></div></div></div></div>
@endsection 