@extends('admin.layouts.app')

@section('title', 'إضافة حكم جديد')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">إضافة حكم جديد</h3>
                    <a href="{{ route('admin.judgments.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-right"></i> العودة للقائمة</a>
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

                    <form action="{{ route('admin.judgments.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="case_id">القضية <span class="text-danger">*</span></label>
                                <select name="case_id" id="case_id" class="form-select @error('case_id') is-invalid @enderror" required>
                                    <option value="">-- اختر القضية --</option>
                                    @foreach($cases as $case)
                                        <option value="{{ $case->id }}" {{ old('case_id') == $case->id ? 'selected' : '' }}>{{ $case->case_number }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="judgment_type_id">نوع الحكم <span class="text-danger">*</span></label>
                                <select name="judgment_type_id" id="judgment_type_id" class="form-select @error('judgment_type_id') is-invalid @enderror" required>
                                    <option value="">-- اختر النوع --</option>
                                    @foreach($judgmentTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('judgment_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="title">عنوان الحكم <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="content">نص الحكم <span class="text-danger">*</span></label>
                            <textarea name="content" id="content" rows="5" class="form-control @error('content') is-invalid @enderror" required>{{ old('content') }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="judgment_date">تاريخ الحكم <span class="text-danger">*</span></label>
                                <input type="date" name="judgment_date" id="judgment_date" class="form-control @error('judgment_date') is-invalid @enderror" value="{{ old('judgment_date', date('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="status">الحالة <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>منشور</option>
                                    <option value="final" {{ old('status') == 'final' ? 'selected' : '' }}>نهائي</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> حفظ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 