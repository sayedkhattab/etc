@extends('admin.layouts.app')

@section('title', 'إضافة ملف قضية جديد')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="page-header">
    <h1>إضافة ملف قضية جديد</h1>
</div>

<div class="card">
    <div class="card-header">
        بيانات ملف القضية
    </div>
    <div class="card-body">
        <form action="{{ route('admin.store.case-files.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="title" class="form-label">عنوان القضية <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="case_number" class="form-label">رقم القضية</label>
                        <input type="text" class="form-control @error('case_number') is-invalid @enderror" id="case_number" name="case_number" value="{{ old('case_number') }}" placeholder="">
                        @error('case_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">وصف تفصيلي للقضية <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote @error('description') is-invalid @enderror" id="description" name="description" rows="10">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="case_type" class="form-label">نوع القضية <span class="text-danger">*</span></label>
                        <select class="form-select @error('case_type') is-invalid @enderror" id="case_type" name="case_type" required>
                            <option value="">-- اختر نوع القضية --</option>
                            <option value="مدعي" {{ old('case_type') == 'مدعي' ? 'selected' : '' }}>مدعي</option>
                            <option value="مدعى عليه" {{ old('case_type') == 'مدعى عليه' ? 'selected' : '' }}>مدعى عليه</option>
                        </select>
                        <small class="form-text text-muted">حدد ما إذا كانت هذه القضية مخصصة لدور المدعي أو المدعى عليه</small>
                        @error('case_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="facts" class="form-label">وقائع القضية</label>
                        <textarea class="form-control summernote @error('facts') is-invalid @enderror" id="facts" name="facts" rows="5">{{ old('facts') }}</textarea>
                        @error('facts')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="legal_articles" class="form-label">المواد القانونية</label>
                        <textarea class="form-control summernote @error('legal_articles') is-invalid @enderror" id="legal_articles" name="legal_articles" rows="5">{{ old('legal_articles') }}</textarea>
                        @error('legal_articles')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">فئة القضية <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="">-- اختر الفئة --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="price" class="form-label">السعر (ريال) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', 0) }}" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="difficulty" class="form-label">مستوى الصعوبة <span class="text-danger">*</span></label>
                        <select class="form-select @error('difficulty') is-invalid @enderror" id="difficulty" name="difficulty" required>
                            <option value="سهل" {{ old('difficulty') == 'سهل' ? 'selected' : '' }}>سهل</option>
                            <option value="متوسط" {{ old('difficulty') == 'متوسط' ? 'selected' : '' }}>متوسط</option>
                            <option value="صعب" {{ old('difficulty') == 'صعب' ? 'selected' : '' }}>صعب</option>
                        </select>
                        @error('difficulty')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="estimated_duration_days" class="form-label">المدة المقدرة (أيام) <span class="text-danger">*</span></label>
                        <input type="number" min="1" class="form-control @error('estimated_duration_days') is-invalid @enderror" id="estimated_duration_days" name="estimated_duration_days" value="{{ old('estimated_duration_days', 7) }}" required>
                        @error('estimated_duration_days')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">صورة مصغرة للقضية</label>
                        <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" id="thumbnail" name="thumbnail" accept="image/*">
                        <small class="form-text text-muted">الصيغ المسموحة: JPG, PNG, GIF. الحجم الأقصى: 2MB</small>
                        @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">خيارات إضافية</label>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">نشط</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">مميز</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr>
            
            <h4>مرفقات القضية</h4>
            <div class="mb-3">
                <label for="attachments" class="form-label">إضافة مرفقات (ملفات PDF، Word، صور، إلخ)</label>
                <input type="file" class="form-control @error('attachments.*') is-invalid @enderror" id="attachments" name="attachments[]" multiple>
                <small class="form-text text-muted">يمكنك تحميل عدة ملفات في نفس الوقت. الحجم الأقصى للملف: 10MB</small>
                @error('attachments.*')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> حفظ
                </button>
                <a href="{{ route('admin.store.case-files.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onImageUpload: function(files) {
                    // منع تحميل الصور مباشرة في المحرر
                    alert('يرجى تحميل الصور كمرفقات منفصلة');
                }
            }
        });
    });
</script>
@endsection 