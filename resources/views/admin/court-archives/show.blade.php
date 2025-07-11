@extends('admin.layouts.app')

@section('title', $caseFile->title)

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1>{{ $caseFile->title }}</h1>
    <div>
        <a href="{{ route('admin.court-archives.edit', $caseFile->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> تعديل
        </a>
        <a href="{{ route('admin.court-archives.index') }}" class="btn btn-secondary">
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
                    <h5>وصف القضية</h5>
                    <div class="border p-3 rounded">
                        {!! $caseFile->description !!}
                    </div>
                </div>
                
                @if($caseFile->judgment_summary)
                <div class="mb-4">
                    <h5>ملخص الحكم</h5>
                    <div class="border p-3 rounded">
                        {!! $caseFile->judgment_summary !!}
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
                                                <a href="{{ route('admin.case-files.attachments.download', $attachment->id) }}" class="btn btn-sm btn-success">
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
                القضايا النشطة المرتبطة ({{ $caseFile->activeCases->count() }})
            </div>
            <div class="card-body">
                @if($caseFile->activeCases->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>رقم القضية</th>
                                    <th>المدعي</th>
                                    <th>المدعى عليه</th>
                                    <th>الحالة</th>
                                    <th>تاريخ البدء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($caseFile->activeCases as $activeCase)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $activeCase->case_number }}</td>
                                        <td>{{ $activeCase->plaintiff->name }}</td>
                                        <td>{{ $activeCase->defendant->name }}</td>
                                        <td>
                                            @if($activeCase->status == 'pending')
                                                <span class="badge bg-warning">قيد الانتظار</span>
                                            @elseif($activeCase->status == 'in_progress')
                                                <span class="badge bg-primary">جارية</span>
                                            @elseif($activeCase->status == 'completed')
                                                <span class="badge bg-success">مكتملة</span>
                                            @elseif($activeCase->status == 'canceled')
                                                <span class="badge bg-danger">ملغية</span>
                                            @endif
                                        </td>
                                        <td>{{ $activeCase->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('admin.active-cases.show', $activeCase->id) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> عرض
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        لا توجد قضايا نشطة مرتبطة بهذه القضية
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
                <table class="table">
                    <tr>
                        <th>رقم القضية</th>
                        <td>{{ $caseFile->case_number }}</td>
                    </tr>
                    <tr>
                        <th>نوع القضية</th>
                        <td>{{ $caseFile->case_type }}</td>
                    </tr>
                    <tr>
                        <th>المدعي</th>
                        <td>{{ $caseFile->plaintiff }}</td>
                    </tr>
                    <tr>
                        <th>المدعى عليه</th>
                        <td>{{ $caseFile->defendant }}</td>
                    </tr>
                    <tr>
                        <th>المحكمة</th>
                        <td>{{ $caseFile->court_name }}</td>
                    </tr>
                    <tr>
                        <th>القاضي</th>
                        <td>{{ $caseFile->judge_name ?: 'غير محدد' }}</td>
                    </tr>
                    <tr>
                        <th>الحالة</th>
                        <td>
                            @if($caseFile->status == 'جاري')
                                <span class="badge bg-primary">جاري</span>
                            @elseif($caseFile->status == 'مكتمل')
                                <span class="badge bg-success">مكتمل</span>
                            @elseif($caseFile->status == 'مؤجل')
                                <span class="badge bg-warning">مؤجل</span>
                            @elseif($caseFile->status == 'مغلق')
                                <span class="badge bg-secondary">مغلق</span>
                            @endif
                        </td>
                    </tr>
                    @if($caseFile->judgment_date)
                    <tr>
                        <th>تاريخ الحكم</th>
                        <td>{{ \Carbon\Carbon::parse($caseFile->judgment_date)->format('Y-m-d') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>تاريخ الإنشاء</th>
                        <td>{{ $caseFile->created_at->format('Y-m-d') }}</td>
                    </tr>
                    <tr>
                        <th>آخر تحديث</th>
                        <td>{{ $caseFile->updated_at->format('Y-m-d') }}</td>
                    </tr>
                    <tr>
                        <th>أضيف بواسطة</th>
                        <td>{{ $caseFile->admin->name ?? 'غير معروف' }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                إضافة مرفقات
            </div>
            <div class="card-body">
                <form action="{{ route('admin.case-files.attachments.store', $caseFile->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="new_attachments" class="form-label">اختر الملفات</label>
                        <input type="file" class="form-control @error('attachments') is-invalid @enderror" id="new_attachments" name="attachments[]" multiple>
                        <small class="form-text text-muted">يمكنك تحميل عدة ملفات في نفس الوقت. الحجم الأقصى للملف: 10MB</small>
                        @error('attachments')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-upload"></i> رفع المرفقات
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 