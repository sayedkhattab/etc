@extends('admin.layouts.app')

@section('title', 'فئات القضايا')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1>فئات القضايا</h1>
    <a href="{{ route('admin.store.categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> إضافة فئة جديدة
    </a>
</div>

<div class="card">
    <div class="card-header">
        قائمة فئات القضايا
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الوصف</th>
                        <th>الترتيب</th>
                        <th>الحالة</th>
                        <th>عدد القضايا</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="categories-list">
                    @forelse($categories as $category)
                        <tr data-id="{{ $category->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($category->icon)
                                    <img src="{{ asset('storage/' . $category->icon) }}" alt="{{ $category->name }}" width="30" height="30" class="me-2">
                                @else
                                    <i class="bi bi-folder me-2"></i>
                                @endif
                                {{ $category->name }}
                            </td>
                            <td>{{ Str::limit($category->description, 50) }}</td>
                            <td>
                                <input type="number" class="form-control form-control-sm sort-order" value="{{ $category->sort_order }}" min="0" style="width: 70px">
                            </td>
                            <td>
                                @if($category->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-secondary">غير نشط</span>
                                @endif
                            </td>
                            <td>{{ $category->caseFiles->count() }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.store.categories.edit', $category->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.store.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الفئة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">لا توجد فئات حالياً</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <button id="save-order" class="btn btn-success">
            <i class="bi bi-save"></i> حفظ الترتيب
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // حفظ ترتيب الفئات
        document.getElementById('save-order').addEventListener('click', function() {
            const categories = [];
            document.querySelectorAll('#categories-list tr').forEach(row => {
                const id = row.dataset.id;
                const order = row.querySelector('.sort-order').value;
                
                if (id && order) {
                    categories.push({
                        id: id,
                        order: order
                    });
                }
            });
            
            // إرسال البيانات إلى الخادم
            fetch('{{ route("admin.store.categories.order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ categories: categories })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('تم حفظ الترتيب بنجاح');
                    location.reload();
                } else {
                    alert('حدث خطأ أثناء حفظ الترتيب');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء حفظ الترتيب');
            });
        });
    });
</script>
@endsection 