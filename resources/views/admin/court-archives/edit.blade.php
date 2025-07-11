@extends('admin.layouts.app')

@section('title', 'تعديل قضية: ' . $caseFile->title)

@section('content')
<div class="page-header">
    <h1>تعديل قضية: {{ $caseFile->title }}</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.court-archives.update', $caseFile->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="case_number" class="form-label">رقم القضية <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('case_number') is-invalid @enderror" id="case_number" name="case_number" value="{{ old('case_number', $caseFile->case_number) }}" required>
                        @error('case_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">عنوان القضية <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $caseFile->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="case_type" class="form-label">نوع القضية <span class="text-danger">*</span></label>
                        <select class="form-select @error('case_type') is-invalid @enderror" id="case_type" name="case_type" required>
                            <option value="">-- اختر نوع القضية --</option>
                            <option value="مدني" {{ old('case_type', $caseFile->case_type) == 'مدني' ? 'selected' : '' }}>مدني</option>
                            <option value="جنائي" {{ old('case_type', $caseFile->case_type) == 'جنائي' ? 'selected' : '' }}>جنائي</option>
                            <option value="تجاري" {{ old('case_type', $caseFile->case_type) == 'تجاري' ? 'selected' : '' }}>تجاري</option>
                            <option value="إداري" {{ old('case_type', $caseFile->case_type) == 'إداري' ? 'selected' : '' }}>إداري</option>
                            <option value="أحوال شخصية" {{ old('case_type', $caseFile->case_type) == 'أحوال شخصية' ? 'selected' : '' }}>أحوال شخصية</option>
                            <option value="عمالي" {{ old('case_type', $caseFile->case_type) == 'عمالي' ? 'selected' : '' }}>عمالي</option>
                        </select>
                        @error('case_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="plaintiff" class="form-label">المدعي <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('plaintiff') is-invalid @enderror" id="plaintiff" name="plaintiff" value="{{ old('plaintiff', $caseFile->plaintiff) }}" required>
                        @error('plaintiff')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="defendant" class="form-label">المدعى عليه <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('defendant') is-invalid @enderror" id="defendant" name="defendant" value="{{ old('defendant', $caseFile->defendant) }}" required>
                        @error('defendant')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="court_name" class="form-label">اسم المحكمة <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('court_name') is-invalid @enderror" id="court_name" name="court_name" value="{{ old('court_name', $caseFile->court_name) }}" required>
                        @error('court_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="judge_name" class="form-label">اسم القاضي</label>
                        <input type="text" class="form-control @error('judge_name') is-invalid @enderror" id="judge_name" name="judge_name" value="{{ old('judge_name', $caseFile->judge_name) }}">
                        @error('judge_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">حالة القضية <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="">-- اختر حالة القضية --</option>
                            <option value="جاري" {{ old('status', $caseFile->status) == 'جاري' ? 'selected' : '' }}>جاري</option>
                            <option value="مكتمل" {{ old('status', $caseFile->status) == 'مكتمل' ? 'selected' : '' }}>مكتمل</option>
                            <option value="مؤجل" {{ old('status', $caseFile->status) == 'مؤجل' ? 'selected' : '' }}>مؤجل</option>
                            <option value="مغلق" {{ old('status', $caseFile->status) == 'مغلق' ? 'selected' : '' }}>مغلق</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="judgment_date" class="form-label">تاريخ الحكم</label>
                        <input type="date" class="form-control @error('judgment_date') is-invalid @enderror" id="judgment_date" name="judgment_date" value="{{ old('judgment_date', $caseFile->judgment_date ? \Carbon\Carbon::parse($caseFile->judgment_date)->format('Y-m-d') : '') }}">
                        @error('judgment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="mb-3">
                        <label for="description" class="form-label">وصف القضية <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $caseFile->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="judgment_summary" class="form-label">ملخص الحكم</label>
                        <textarea class="form-control @error('judgment_summary') is-invalid @enderror" id="judgment_summary" name="judgment_summary" rows="4">{{ old('judgment_summary', $caseFile->judgment_summary) }}</textarea>
                        @error('judgment_summary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="attachments" class="form-label">إضافة مرفقات جديدة</label>
                        <input type="file" class="form-control @error('attachments.*') is-invalid @enderror" id="attachments" name="attachments[]" multiple>
                        <small class="form-text text-muted">يمكنك تحميل عدة ملفات في نفس الوقت. الحجم الأقصى للملف: 10MB</small>
                        @error('attachments.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> حفظ التغييرات
                </button>
                <a href="{{ route('admin.court-archives.show', $caseFile->id) }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

@if($caseFile->attachments->count() > 0)
<div class="card mt-4">
    <div class="card-header">
        المرفقات الحالية
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم الملف</th>
                        <th>النوع</th>
                        <th>الحجم</th>
                        <th>تاريخ الإضافة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($caseFile->attachments as $attachment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $attachment->original_filename }}</td>
                            <td>{{ $attachment->file_type }}</td>
                            <td>{{ number_format($attachment->file_size / 1024, 2) }} KB</td>
                            <td>{{ $attachment->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ asset('storage/' . $attachment->file_path) }}" class="btn btn-sm btn-info" target="_blank">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.case-files.attachments.download', $attachment->id) }}" class="btn btn-sm btn-success">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <form action="{{ route('admin.case-files.attachments.destroy', $attachment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المرفق؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // إضافة محرر نصوص متقدم لحقول النص الطويل إذا كان متاحاً
        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace('description');
            CKEDITOR.replace('judgment_summary');
        }
    });
</script>
@endsection 