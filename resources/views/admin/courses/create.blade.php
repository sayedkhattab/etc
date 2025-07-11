@extends('admin.layouts.app')

@section('title', 'إضافة دورة جديدة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">إضافة دورة جديدة</h3>
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-right"></i> العودة للقائمة
                    </a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title">عنوان الدورة <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="category_id">تصنيف الدورة <span class="text-danger">*</span></label>
                                    <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                        <option value="">-- اختر --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="price">السعر (ر.س) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.1" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', 0) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="duration">المدة (بالساعات)</label>
                                    <input type="number" name="duration" id="duration" class="form-control @error('duration') is-invalid @enderror" value="{{ old('duration') }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">الوصف</label>
                            <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="thumbnail">صورة مصغرة</label>
                            <input type="file" name="thumbnail" id="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="status">الحالة <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشطة</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشطة</option>
                                        <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>مؤرشفة</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="visibility">الظهور <span class="text-danger">*</span></label>
                                    <select name="visibility" id="visibility" class="form-select @error('visibility') is-invalid @enderror" required>
                                        <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>عام</option>
                                        <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>خاص</option>
                                        <option value="password_protected" {{ old('visibility') == 'password_protected' ? 'selected' : '' }}>محمي بكلمة مرور</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3" id="passwordField" style="display: none;">
                            <label for="access_password">كلمة المرور</label>
                            <input type="text" name="access_password" id="access_password" class="form-control @error('access_password') is-invalid @enderror" value="{{ old('access_password') }}">
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="featured" id="featured" value="1" {{ old('featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="featured">تمييز الدورة</label>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> حفظ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const visibilityField = document.getElementById('visibility');
            const passwordField = document.getElementById('passwordField');

            function togglePassword() {
                if (visibilityField.value === 'password_protected') {
                    passwordField.style.display = 'block';
                } else {
                    passwordField.style.display = 'none';
                }
            }

            visibilityField.addEventListener('change', togglePassword);
            togglePassword();
        });
    </script>
@endpush 