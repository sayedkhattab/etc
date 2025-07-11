@extends('admin.layouts.app')

@section('title', 'إضافة قضية جديدة إلى الأرشيف')

@section('content')
<div class="page-header">
    <h1>إضافة قضية جديدة إلى الأرشيف</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.court-archives.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="case_number" class="form-label">رقم القضية <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('case_number') is-invalid @enderror" id="case_number" name="case_number" value="{{ old('case_number') }}" required>
                        @error('case_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">عنوان القضية <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="case_type" class="form-label">نوع القضية <span class="text-danger">*</span></label>
                        <select class="form-select @error('case_type') is-invalid @enderror" id="case_type" name="case_type" required>
                            <option value="">-- اختر نوع القضية --</option>
                            <option value="مدني" {{ old('case_type') == 'مدني' ? 'selected' : '' }}>مدني</option>
                            <option value="جنائي" {{ old('case_type') == 'جنائي' ? 'selected' : '' }}>جنائي</option>
                            <option value="تجاري" {{ old('case_type') == 'تجاري' ? 'selected' : '' }}>تجاري</option>
                            <option value="إداري" {{ old('case_type') == 'إداري' ? 'selected' : '' }}>إداري</option>
                            <option value="أحوال شخصية" {{ old('case_type') == 'أحوال شخصية' ? 'selected' : '' }}>أحوال شخصية</option>
                            <option value="عمالي" {{ old('case_type') == 'عمالي' ? 'selected' : '' }}>عمالي</option>
                        </select>
                        @error('case_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="plaintiff" class="form-label">المدعي <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('plaintiff') is-invalid @enderror" id="plaintiff" name="plaintiff" value="{{ old('plaintiff') }}" required>
                        @error('plaintiff')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="defendant" class="form-label">المدعى عليه <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('defendant') is-invalid @enderror" id="defendant" name="defendant" value="{{ old('defendant') }}" required>
                        @error('defendant')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="court_name" class="form-label">اسم المحكمة <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('court_name') is-invalid @enderror" id="court_name" name="court_name" value="{{ old('court_name') }}" required>
                        @error('court_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="judge_name" class="form-label">اسم القاضي</label>
                        <input type="text" class="form-control @error('judge_name') is-invalid @enderror" id="judge_name" name="judge_name" value="{{ old('judge_name') }}">
                        @error('judge_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">حالة القضية <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="">-- اختر حالة القضية --</option>
                            <option value="جاري" {{ old('status') == 'جاري' ? 'selected' : '' }}>جاري</option>
                            <option value="مكتمل" {{ old('status') == 'مكتمل' ? 'selected' : '' }}>مكتمل</option>
                            <option value="مؤجل" {{ old('status') == 'مؤجل' ? 'selected' : '' }}>مؤجل</option>
                            <option value="مغلق" {{ old('status') == 'مغلق' ? 'selected' : '' }}>مغلق</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="judgment_date" class="form-label">تاريخ الحكم</label>
                        <input type="date" class="form-control @error('judgment_date') is-invalid @enderror" id="judgment_date" name="judgment_date" value="{{ old('judgment_date') }}">
                        @error('judgment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="mb-3">
                        <label for="description" class="form-label">وصف القضية <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="judgment_summary" class="form-label">ملخص الحكم</label>
                        <textarea class="form-control @error('judgment_summary') is-invalid @enderror" id="judgment_summary" name="judgment_summary" rows="4">{{ old('judgment_summary') }}</textarea>
                        @error('judgment_summary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="attachments" class="form-label">مرفقات القضية</label>
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
                    <i class="bi bi-save"></i> حفظ
                </button>
                <a href="{{ route('admin.court-archives.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
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