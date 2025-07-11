@extends('admin.layouts.app')

@section('title', 'إضافة مستوى جديد')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1>إضافة مستوى لدورة: {{ $course->title }}</h1>
    <a href="{{ route('admin.courses.levels.index', $course) }}" class="btn btn-secondary"><i class="bi bi-arrow-right"></i> العودة</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.courses.levels.store', $course) }}" method="POST">
            @csrf
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
                <label class="form-label">الترتيب <span class="text-danger">*</span></label>
                <input type="number" name="order" class="form-control @error('order') is-invalid @enderror" min="0" value="{{ old('order', 0) }}" required>
                @error('order')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">الحالة</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ old('status', 'inactive') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">المتطلبات السابقة (اختياري)</label>
                <select name="prerequisites[]" multiple class="form-select @error('prerequisites') is-invalid @enderror">
                    @foreach($existingLevels as $lvl)
                        <option value="{{ $lvl->id }}" {{ in_array($lvl->id, old('prerequisites', [])) ? 'selected' : '' }}>{{ $lvl->title }}</option>
                    @endforeach
                </select>
                @error('prerequisites')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> حفظ</button>
                <a href="{{ route('admin.courses.levels.index', $course) }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection 