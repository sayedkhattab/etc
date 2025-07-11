@extends('admin.layouts.app')

@section('title', 'تعديل فئة قضايا')

@section('content')
<div class="page-header">
    <h1>تعديل فئة قضايا: {{ $category->name }}</h1>
</div>

<div class="card">
    <div class="card-header">
        بيانات الفئة
    </div>
    <div class="card-body">
        <form action="{{ route('admin.store.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="name" class="form-label">اسم الفئة <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">وصف الفئة</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="icon" class="form-label">أيقونة الفئة</label>
                @if($category->icon)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $category->icon) }}" alt="{{ $category->name }}" width="100" height="100" class="img-thumbnail">
                    </div>
                @endif
                <input type="file" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" accept="image/*">
                <small class="form-text text-muted">الصيغ المسموحة: JPG, PNG, GIF. الحجم الأقصى: 2MB</small>
                @error('icon')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="sort_order" class="form-label">ترتيب العرض</label>
                <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0">
                @error('sort_order')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">فئة نشطة</label>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> حفظ التغييرات
                </button>
                <a href="{{ route('admin.store.categories.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

@if($category->caseFiles->count() > 0)
<div class="card mt-4">
    <div class="card-header">
        ملفات القضايا في هذه الفئة ({{ $category->caseFiles->count() }})
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>العنوان</th>
                        <th>السعر</th>
                        <th>الصعوبة</th>
                        <th>الحالة</th>
                        <th>عدد المشتريات</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($category->caseFiles as $caseFile)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($caseFile->thumbnail)
                                    <img src="{{ asset('storage/' . $caseFile->thumbnail) }}" alt="{{ $caseFile->title }}" width="30" height="30" class="me-2">
                                @endif
                                {{ $caseFile->title }}
                            </td>
                            <td>{{ number_format($caseFile->price, 2) }} ريال</td>
                            <td>{{ $caseFile->difficulty }}</td>
                            <td>
                                @if($caseFile->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-secondary">غير نشط</span>
                                @endif
                            </td>
                            <td>{{ $caseFile->purchases_count }}</td>
                            <td>
                                <a href="{{ route('admin.store.case-files.edit', $caseFile->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i> تعديل
                                </a>
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