@extends('admin.layouts.app')

@section('title', 'إضافة محتوى')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1>إضافة محتوى للمستوى: {{ $level->title }}</h1>
    <a href="{{ route('admin.courses.levels.contents.index', [$course, $level]) }}" class="btn btn-secondary"><i class="bi bi-arrow-right"></i> العودة</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.courses.levels.contents.store', [$course, $level]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">نوع المحتوى <span class="text-danger">*</span></label>
                <select name="content_type_id" class="form-select @error('content_type_id') is-invalid @enderror" required>
                    <option value="">اختر</option>
                    @foreach($contentTypes as $type)
                        <option value="{{ $type->id }}" {{ old('content_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
                @error('content_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">العنوان <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">الوصف</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">رابط المحتوى (اختياري إذا كان ملف)</label>
                <input type="text" name="content_url" class="form-control @error('content_url') is-invalid @enderror" value="{{ old('content_url') }}">
                @error('content_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">ملف المحتوى (اختياري)</label>
                <input type="file" name="content_file" class="form-control @error('content_file') is-invalid @enderror">
                @error('content_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">المدة بالدقائق</label>
                <input type="number" name="duration" min="1" class="form-control @error('duration') is-invalid @enderror" value="{{ old('duration') }}">
                @error('duration')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">ترتيب العرض <span class="text-danger">*</span></label>
                <input type="number" name="order" class="form-control @error('order') is-invalid @enderror" min="0" value="{{ old('order', 0) }}" required>
                @error('order')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_required" id="is_required" value="1" {{ old('is_required') ? 'checked' : '' }}>
                <label class="form-check-label" for="is_required">هل المحتوى مطلوب؟</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> حفظ</button>
                <a href="{{ route('admin.courses.levels.contents.index', [$course, $level]) }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection 