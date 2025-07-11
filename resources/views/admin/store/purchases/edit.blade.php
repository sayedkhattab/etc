@extends('admin.layouts.app')

@section('title', 'تعديل عملية شراء')

@section('content')
<div class="page-header">
    <h1>تعديل عملية شراء #{{ $purchase->id }}</h1>
</div>

<div class="card">
    <div class="card-header">
        بيانات عملية الشراء
    </div>
    <div class="card-body">
        <form action="{{ route('admin.store.purchases.update', $purchase->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">المستخدم <span class="text-danger">*</span></label>
                        <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                            <option value="">-- اختر المستخدم --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $purchase->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="case_file_id" class="form-label">ملف القضية <span class="text-danger">*</span></label>
                        <select class="form-select @error('case_file_id') is-invalid @enderror" id="case_file_id" name="case_file_id" required>
                            <option value="">-- اختر ملف القضية --</option>
                            @foreach($caseFiles as $caseFile)
                                <option value="{{ $caseFile->id }}" {{ old('case_file_id', $purchase->case_file_id) == $caseFile->id ? 'selected' : '' }}>
                                    {{ $caseFile->title }} ({{ number_format($caseFile->price, 2) }} ريال)
                                </option>
                            @endforeach
                        </select>
                        @error('case_file_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="role" class="form-label">الدور في القضية <span class="text-danger">*</span></label>
                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                    <option value="">-- اختر الدور --</option>
                    <option value="مدعي" {{ old('role', $purchase->role) == 'مدعي' ? 'selected' : '' }}>مدعي</option>
                    <option value="مدعى عليه" {{ old('role', $purchase->role) == 'مدعى عليه' ? 'selected' : '' }}>مدعى عليه</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text" id="role-availability"></div>
            </div>
            
            <div class="mb-3">
                <label for="notes" class="form-label">ملاحظات</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $purchase->notes) }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $purchase->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">عملية شراء نشطة</label>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> حفظ التغييرات
                </button>
                <a href="{{ route('admin.store.purchases.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

@if($purchase->is_activated_in_court)
<div class="card mt-4">
    <div class="card-header bg-primary text-white">
        القضية مفعلة في المحكمة الافتراضية
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <p>تم تفعيل هذه القضية في المحكمة الافتراضية. أي تغييرات في بيانات الشراء قد تؤثر على القضية النشطة.</p>
        </div>
        
        <a href="{{ route('admin.active-cases.show', $purchase->active_case_id) }}" class="btn btn-primary">
            <i class="bi bi-eye"></i> عرض القضية النشطة
        </a>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const caseFileSelect = document.getElementById('case_file_id');
        const roleSelect = document.getElementById('role');
        const roleAvailability = document.getElementById('role-availability');
        const currentCaseFileId = {{ $purchase->case_file_id }};
        const currentRole = "{{ $purchase->role }}";
        
        // التحقق من توفر الأدوار عند تغيير ملف القضية أو الدور
        function checkRoleAvailability() {
            const caseFileId = caseFileSelect.value;
            const role = roleSelect.value;
            
            // لا نحتاج للتحقق إذا لم يتم تغيير البيانات
            if (caseFileId == currentCaseFileId && role == currentRole) {
                roleAvailability.innerHTML = '';
                return;
            }
            
            if (caseFileId && role) {
                roleAvailability.innerHTML = '<div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">جاري التحميل...</span></div> جاري التحقق من توفر الدور...';
                
                fetch(`{{ route('admin.store.purchases.check-role') }}?case_file_id=${caseFileId}&role=${role}&exclude_id={{ $purchase->id }}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.available) {
                            roleAvailability.innerHTML = '<span class="text-success"><i class="bi bi-check-circle"></i> الدور متاح</span>';
                        } else {
                            roleAvailability.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-triangle"></i> الدور غير متاح. تم اختياره بالفعل من قبل مستخدم آخر.</span>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        roleAvailability.innerHTML = '<span class="text-danger">حدث خطأ أثناء التحقق من توفر الدور</span>';
                    });
            } else {
                roleAvailability.innerHTML = '';
            }
        }
        
        caseFileSelect.addEventListener('change', checkRoleAvailability);
        roleSelect.addEventListener('change', checkRoleAvailability);
    });
</script>
@endsection 