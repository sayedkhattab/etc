@extends('admin.layouts.app')

@section('title', 'إضافة تقييم جديد')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">إضافة تقييم جديد</h3>
                    <a href="{{ request()->has('course_id') ? route('admin.assessments.index', ['course_id' => request()->course_id]) : route('admin.assessments.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-right"></i> العودة للقائمة</a>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.assessments.store', request()->has('course_id') ? ['course_id' => request()->course_id] : []) }}" method="POST">
                        @csrf
                        
                        @if(request()->has('course_id'))
                            <input type="hidden" name="course_id" value="{{ request()->course_id }}">
                        @endif
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="level_id">المستوى <span class="text-danger">*</span></label>
                                <select name="level_id" id="level_id" class="form-select @error('level_id') is-invalid @enderror" required>
                                    <option value="">-- اختر المستوى --</option>
                                    @foreach($levels as $level)
                                        <option value="{{ $level->id }}" {{ old('level_id', request('level_id')) == $level->id ? 'selected' : '' }}>
                                            {{ $level->course?->title }} - {{ $level->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="assessment_type_id">نوع التقييم <span class="text-danger">*</span></label>
                                <select name="assessment_type_id" id="assessment_type_id" class="form-select @error('assessment_type_id') is-invalid @enderror" required>
                                    <option value="">-- اختر النوع --</option>
                                    @foreach($assessmentTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('assessment_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="title">عنوان التقييم <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="passing_score">درجة النجاح (%) <span class="text-danger">*</span></label>
                                <input type="number" name="passing_score" id="passing_score" class="form-control @error('passing_score') is-invalid @enderror" value="{{ old('passing_score', 60) }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="attempts_allowed">عدد المحاولات <span class="text-danger">*</span></label>
                                <input type="number" name="attempts_allowed" id="attempts_allowed" class="form-control @error('attempts_allowed') is-invalid @enderror" value="{{ old('attempts_allowed', 1) }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="status">الحالة <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">نشط</label>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="hidden" name="is_pretest" value="0">
                            <input type="checkbox" class="form-check-input" id="is_pretest" name="is_pretest" value="1" {{ old('is_pretest') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_pretest">اختبار تحديد مستوى</label>
                            <small class="form-text text-muted d-block">حدد هذا الخيار إذا كان هذا التقييم هو اختبار تحديد مستوى يجب على الطالب إكماله قبل الوصول إلى محتوى المستوى.</small>
                        </div>
                        
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="submit" class="btn btn-primary px-4">إنشاء التقييم</button>
                            <a href="{{ request()->has('course_id') ? route('admin.assessments.index', ['course_id' => request()->course_id]) : route('admin.assessments.index') }}" class="btn btn-secondary px-4">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 