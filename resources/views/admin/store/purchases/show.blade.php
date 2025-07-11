@extends('admin.layouts.app')

@section('title', 'تفاصيل عملية الشراء')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1>تفاصيل عملية الشراء #{{ $purchase->id }}</h1>
    <div>
        <a href="{{ route('admin.store.purchases.edit', $purchase->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> تعديل
        </a>
        <a href="{{ route('admin.store.purchases.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right"></i> العودة للقائمة
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                معلومات عملية الشراء
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <tr>
                        <th style="width: 200px;">رقم عملية الشراء</th>
                        <td>{{ $purchase->id }}</td>
                    </tr>
                    <tr>
                        <th>المستخدم</th>
                        <td>
                            @if($purchase->user)
                                <div class="d-flex align-items-center">
                                    @if($purchase->user->avatar)
                                        <img src="{{ asset('storage/' . $purchase->user->avatar) }}" class="rounded-circle me-2" width="40" height="40" alt="{{ $purchase->user->name }}">
                                    @else
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                            <i class="bi bi-person text-white"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div>{{ $purchase->user->name }}</div>
                                        <div class="text-muted small">{{ $purchase->user->email }}</div>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted">مستخدم محذوف</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>ملف القضية</th>
                        <td>
                            @if($purchase->caseFile)
                                <a href="{{ route('admin.store.case-files.show', $purchase->caseFile->id) }}">
                                    {{ $purchase->caseFile->title }}
                                </a>
                            @else
                                <span class="text-muted">ملف محذوف</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>الدور</th>
                        <td>
                            @if($purchase->role == 'مدعي')
                                <span class="badge bg-info">{{ $purchase->role }}</span>
                            @else
                                <span class="badge bg-warning text-dark">{{ $purchase->role }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>تاريخ الشراء</th>
                        <td>{{ $purchase->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>آخر تحديث</th>
                        <td>{{ $purchase->updated_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>الحالة</th>
                        <td>
                            @if($purchase->is_active)
                                <span class="badge bg-success">نشط</span>
                            @else
                                <span class="badge bg-secondary">غير نشط</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>ملاحظات</th>
                        <td>
                            @if($purchase->notes)
                                {{ $purchase->notes }}
                            @else
                                <span class="text-muted">لا توجد ملاحظات</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        @if($purchase->caseFile)
        <div class="card mb-4">
            <div class="card-header">
                معلومات ملف القضية
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        @if($purchase->caseFile->thumbnail)
                            <img src="{{ asset('storage/' . $purchase->caseFile->thumbnail) }}" alt="{{ $purchase->caseFile->title }}" class="img-fluid rounded mb-3">
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h5>{{ $purchase->caseFile->title }}</h5>
                        <p class="text-muted">{{ $purchase->caseFile->summary }}</p>
                        
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge bg-secondary">{{ $purchase->caseFile->category->name }}</span>
                            <span class="badge bg-info">{{ $purchase->caseFile->difficulty }}</span>
                            <span class="badge bg-primary">{{ number_format($purchase->caseFile->price, 2) }} ريال</span>
                        </div>
                        
                        <a href="{{ route('admin.store.case-files.show', $purchase->caseFile->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> عرض تفاصيل القضية
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <div class="col-md-4">
        @if($purchase->is_activated_in_court)
        <div class="card mb-4 border-primary">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-check-circle-fill"></i> القضية مفعلة في المحكمة الافتراضية
            </div>
            <div class="card-body">
                <p>تم تفعيل هذه القضية في المحكمة الافتراضية بنجاح.</p>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.active-cases.show', $purchase->active_case_id) }}" class="btn btn-primary">
                        <i class="bi bi-eye"></i> عرض القضية النشطة
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="card mb-4">
            <div class="card-header">
                حالة التفعيل في المحكمة الافتراضية
            </div>
            <div class="card-body">
                @if($purchase->caseFile && $purchase->caseFile->canActivateInCourt())
                    <div class="alert alert-success">
                        <p><i class="bi bi-check-circle"></i> يمكن تفعيل هذه القضية في المحكمة الافتراضية!</p>
                        <p>
                            <strong>المدعي:</strong> {{ $purchase->caseFile->getPurchaseByRole('مدعي')->user->name }}<br>
                            <strong>المدعى عليه:</strong> {{ $purchase->caseFile->getPurchaseByRole('مدعى عليه')->user->name }}
                        </p>
                    </div>
                    
                    <form action="{{ route('admin.store.case-files.activate', $purchase->caseFile->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-play-circle"></i> تفعيل القضية في المحكمة الافتراضية
                        </button>
                    </form>
                @else
                    <div class="alert alert-warning">
                        <p><i class="bi bi-exclamation-triangle"></i> لا يمكن تفعيل هذه القضية في المحكمة الافتراضية حالياً.</p>
                        <p>يجب أن يتم شراء القضية من قبل مستخدمين مختلفين بدوري المدعي والمدعى عليه.</p>
                    </div>
                    
                    @if($purchase->caseFile)
                        <table class="table table-sm">
                            <tr>
                                <th>المدعي</th>
                                <td>
                                    @php $plaintiffPurchase = $purchase->caseFile->getPurchaseByRole('مدعي'); @endphp
                                    @if($plaintiffPurchase)
                                        <span class="text-success">
                                            <i class="bi bi-check-circle"></i> {{ $plaintiffPurchase->user->name }}
                                        </span>
                                    @else
                                        <span class="text-danger">
                                            <i class="bi bi-x-circle"></i> غير متوفر
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>المدعى عليه</th>
                                <td>
                                    @php $defendantPurchase = $purchase->caseFile->getPurchaseByRole('مدعى عليه'); @endphp
                                    @if($defendantPurchase)
                                        <span class="text-success">
                                            <i class="bi bi-check-circle"></i> {{ $defendantPurchase->user->name }}
                                        </span>
                                    @else
                                        <span class="text-danger">
                                            <i class="bi bi-x-circle"></i> غير متوفر
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    @endif
                @endif
            </div>
        </div>
        @endif
        
        <div class="card mb-4">
            <div class="card-header">
                إجراءات
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.store.purchases.edit', $purchase->id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> تعديل عملية الشراء
                    </a>
                    
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bi bi-trash"></i> حذف عملية الشراء
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">تأكيد الحذف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من حذف عملية الشراء هذه؟</p>
                @if($purchase->is_activated_in_court)
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i> تحذير: هذه القضية مفعلة في المحكمة الافتراضية. حذف عملية الشراء قد يؤثر على القضية النشطة.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form action="{{ route('admin.store.purchases.destroy', $purchase->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">تأكيد الحذف</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 