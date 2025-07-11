@extends('admin.layouts.app')

@section('title', $caseFile->title)

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1>{{ $caseFile->title }}</h1>
    <div>
        <a href="{{ route('admin.store.case-files.edit', $caseFile->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> تعديل
        </a>
        <a href="{{ route('admin.store.case-files.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right"></i> العودة للقائمة
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                معلومات القضية
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h5>وصف تفصيلي</h5>
                    <div class="border p-3 rounded">
                        {!! $caseFile->description !!}
                    </div>
                </div>
                
                @if($caseFile->facts)
                <div class="mb-4">
                    <h5>وقائع القضية</h5>
                    <div class="border p-3 rounded">
                        {!! $caseFile->facts !!}
                    </div>
                </div>
                @endif
                
                @if($caseFile->legal_articles)
                <div class="mb-4">
                    <h5>المواد القانونية</h5>
                    <div class="border p-3 rounded">
                        {!! $caseFile->legal_articles !!}
                    </div>
                </div>
                @endif
                
                @if($caseFile->learning_outcomes)
                <div class="mb-4">
                    <h5>مخرجات التعلم</h5>
                    <div class="border p-3 rounded">
                        {!! $caseFile->learning_outcomes !!}
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                مرفقات القضية ({{ $caseFile->attachments->count() }})
            </div>
            <div class="card-body">
                @if($caseFile->attachments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم الملف</th>
                                    <th>النوع</th>
                                    <th>الحجم</th>
                                    <th>تاريخ الإضافة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($caseFile->attachments as $attachment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $attachment->original_filename }}</td>
                                        <td>{{ $attachment->file_type }}</td>
                                        <td>{{ number_format($attachment->file_size / 1024, 2) }} KB</td>
                                        <td>{{ $attachment->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ asset('storage/' . $attachment->file_path) }}" class="btn btn-sm btn-info" target="_blank">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.store.case-files.attachments.download', $attachment->id) }}" class="btn btn-sm btn-success">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        لا توجد مرفقات لهذه القضية
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                المشتريات ({{ $caseFile->purchases->count() }})
            </div>
            <div class="card-body">
                @if($caseFile->purchases->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>المستخدم</th>
                                    <th>الدور</th>
                                    <th>تاريخ الشراء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($caseFile->purchases as $purchase)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $purchase->user->name }}</td>
                                        <td>{{ $purchase->role }}</td>
                                        <td>{{ $purchase->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.store.purchases.show', $purchase->id) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> عرض التفاصيل
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        لم يتم شراء هذه القضية بعد
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                معلومات أساسية
            </div>
            <div class="card-body">
                @if($caseFile->thumbnail)
                    <div class="text-center mb-3">
                        <img src="{{ asset('storage/' . $caseFile->thumbnail) }}" alt="{{ $caseFile->title }}" class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                @endif
                
                <table class="table">
                    <tr>
                        <th>رقم القضية</th>
                        <td>{{ $caseFile->case_number ?: 'غير محدد' }}</td>
                    </tr>
                    <tr>
                        <th>نوع القضية</th>
                        <td>
                            @if($caseFile->case_type == 'مدعي')
                                <span class="badge bg-primary">مدعي</span>
                            @elseif($caseFile->case_type == 'مدعى عليه')
                                <span class="badge bg-info">مدعى عليه</span>
                            @else
                                <span class="badge bg-secondary">غير محدد</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>الفئة</th>
                        <td>
                            <a href="{{ route('admin.store.categories.edit', $caseFile->category->id) }}">
                                {{ $caseFile->category->name }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>السعر</th>
                        <td>{{ number_format($caseFile->price, 2) }} ريال</td>
                    </tr>
                    <tr>
                        <th>مستوى الصعوبة</th>
                        <td>{{ $caseFile->difficulty }}</td>
                    </tr>
                    <tr>
                        <th>الحالة</th>
                        <td>
                            @if($caseFile->is_active)
                                <span class="badge bg-success">نشط</span>
                            @else
                                <span class="badge bg-secondary">غير نشط</span>
                            @endif
                            
                            @if($caseFile->is_featured)
                                <span class="badge bg-warning">مميز</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>عدد المشتريات</th>
                        <td>{{ $caseFile->purchases_count }}</td>
                    </tr>
                    <tr>
                        <th>تاريخ الإنشاء</th>
                        <td>{{ $caseFile->created_at->format('Y-m-d') }}</td>
                    </tr>
                    <tr>
                        <th>آخر تحديث</th>
                        <td>{{ $caseFile->updated_at->format('Y-m-d') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                تفعيل القضية في المحكمة الافتراضية
            </div>
            <div class="card-body">
                @php
                    $canActivate = $caseFile->canActivateInCourt();
                    $plaintiffPurchase = $caseFile->getPurchaseByRole('مدعي');
                    $defendantPurchase = $caseFile->getPurchaseByRole('مدعى عليه');
                @endphp
                
                @if($canActivate)
                    <div class="alert alert-success">
                        <p>يمكن تفعيل هذه القضية في المحكمة الافتراضية!</p>
                        <p>
                            <strong>المدعي:</strong> {{ $plaintiffPurchase->user->name }}<br>
                            <strong>المدعى عليه:</strong> {{ $defendantPurchase->user->name }}
                        </p>
                    </div>
                    
                    <form action="{{ route('admin.store.case-files.activate', $caseFile->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-play-circle"></i> تفعيل القضية في المحكمة الافتراضية
                        </button>
                    </form>
                @else
                    <div class="alert alert-warning">
                        <p>لا يمكن تفعيل هذه القضية في المحكمة الافتراضية حالياً.</p>
                        <p>يجب أن يتم شراء القضية من قبل مستخدمين مختلفين بدوري المدعي والمدعى عليه.</p>
                    </div>
                    
                    <table class="table table-sm">
                        <tr>
                            <th>المدعي</th>
                            <td>
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
            </div>
        </div>
    </div>
</div>
@endsection 