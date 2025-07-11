@extends('layouts.app')

@section('title', $case->title ?? 'تفاصيل القضية' . ' - إثبات')

@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cases.index') }}">القضايا</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $case->title ?? 'تفاصيل القضية' }}</li>
        </ol>
    </nav>
    
    <div class="row">
        <!-- تفاصيل القضية -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">تفاصيل القضية</h4>
                    <span class="badge bg-{{ $case->status->color ?? 'secondary' }}">
                        {{ $case->status->name ?? 'غير محدد' }}
                    </span>
                </div>
                <div class="card-body">
                    <h3>{{ $case->title }}</h3>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>رقم القضية:</strong> {{ $case->case_number }}</p>
                            <p><strong>المحكمة:</strong> {{ $case->court_type->name ?? 'غير محدد' }}</p>
                            <p><strong>القاضي:</strong> {{ $case->judge->name ?? 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>تاريخ الإنشاء:</strong> {{ $case->created_at->format('Y-m-d') }}</p>
                            <p><strong>آخر تحديث:</strong> {{ $case->updated_at->format('Y-m-d') }}</p>
                            <p><strong>منشئ القضية:</strong> {{ $case->creator->name ?? 'غير محدد' }}</p>
                        </div>
                    </div>
                    
                    <h5 class="mb-3">وصف القضية</h5>
                    <div class="mb-4">
                        {!! nl2br(e($case->description)) !!}
                    </div>
                    
                    <h5 class="mb-3">الوقائع</h5>
                    <div class="mb-4">
                        {!! nl2br(e($case->facts)) !!}
                    </div>
                    
                    <h5 class="mb-3">المرفقات</h5>
                    <div class="mb-4">
                        @if($case->attachments && $case->attachments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>اسم الملف</th>
                                            <th>النوع</th>
                                            <th>الحجم</th>
                                            <th>تاريخ الرفع</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($case->attachments as $attachment)
                                            <tr>
                                                <td>{{ $attachment->title }}</td>
                                                <td>{{ $attachment->file_type }}</td>
                                                <td>{{ number_format($attachment->file_size / 1024, 2) }} KB</td>
                                                <td>{{ $attachment->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('cases.attachments.download', [$case->id, $attachment->id]) }}" class="btn btn-sm btn-primary" title="تحميل">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                        @if(Auth::user()->hasRole(['admin', 'judge']) || (Auth::user()->id == $case->created_by))
                                                            <form action="{{ route('cases.attachments.destroy', [$case->id, $attachment->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المرفق؟')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                لا توجد مرفقات لهذه القضية.
                            </div>
                        @endif
                        
                        @if(Auth::user()->hasRole(['admin', 'judge', 'lawyer']) || (Auth::user()->id == $case->created_by))
                            <form action="{{ route('cases.attachments.store', $case->id) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <input type="text" name="title" class="form-control" placeholder="عنوان المرفق" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="file" name="file" class="form-control" required>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100">رفع</button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                    
                    <!-- المشاركون في القضية -->
                    <h5 class="mb-3">المشاركون في القضية</h5>
                    <div class="mb-4">
                        @if($case->participants && $case->participants->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>الاسم</th>
                                            <th>الدور</th>
                                            <th>تاريخ الانضمام</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($case->participants as $participant)
                                            <tr>
                                                <td>{{ $participant->name }}</td>
                                                <td>{{ $participant->pivot->role_name ?? 'مشارك' }}</td>
                                                <td>{{ $participant->pivot->joined_at->format('Y-m-d') }}</td>
                                                <td>
                                                    @if(Auth::user()->hasRole(['admin', 'judge']) || (Auth::user()->id == $case->created_by))
                                                        <form action="{{ route('cases.participants.destroy', [$case->id, $participant->pivot->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من إزالة هذا المشارك؟')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="إزالة">
                                                                <i class="fas fa-user-minus"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                لا يوجد مشاركون في هذه القضية.
                            </div>
                        @endif
                        
                        @if(Auth::user()->hasRole(['admin', 'judge']) || (Auth::user()->id == $case->created_by))
                            <form action="{{ route('cases.participants.store', $case->id) }}" method="POST" class="mt-3">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <select name="user_id" class="form-select" required>
                                            <option value="">اختر المستخدم</option>
                                            @foreach($users ?? [] as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="role_name" class="form-control" placeholder="الدور (مثال: مدعي، مدعى عليه، شاهد)" required>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100">إضافة</button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- المذكرات الدفاعية -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">المذكرات الدفاعية</h4>
                    <a href="{{ route('cases.defense_entries.create', $case->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> إضافة مذكرة
                    </a>
                </div>
                <div class="card-body">
                    @if($case->defenseEntries && $case->defenseEntries->count() > 0)
                        <div class="accordion" id="accordionDefenseEntries">
                            @foreach($case->defenseEntries as $entry)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading-entry-{{ $entry->id }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-entry-{{ $entry->id }}" aria-expanded="false" aria-controls="collapse-entry-{{ $entry->id }}">
                                            <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                                <span>{{ $entry->title }} - {{ $entry->student->name }}</span>
                                                <span class="badge bg-{{ $entry->is_reviewed ? 'success' : 'warning' }}">
                                                    {{ $entry->is_reviewed ? 'تمت المراجعة' : 'بانتظار المراجعة' }}
                                                </span>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse-entry-{{ $entry->id }}" class="accordion-collapse collapse" aria-labelledby="heading-entry-{{ $entry->id }}" data-bs-parent="#accordionDefenseEntries">
                                        <div class="accordion-body">
                                            <p><strong>تاريخ التقديم:</strong> {{ $entry->created_at->format('Y-m-d') }}</p>
                                            <div class="mb-3">
                                                {!! nl2br(e($entry->content)) !!}
                                            </div>
                                            
                                            @if($entry->attachments && $entry->attachments->count() > 0)
                                                <h6>مرفقات المذكرة:</h6>
                                                <ul class="list-group mb-3">
                                                    @foreach($entry->attachments as $attachment)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            {{ $attachment->title }}
                                                            <div>
                                                                <a href="{{ route('cases.defense_entries.attachments.download', [$case->id, $entry->id, $attachment->id]) }}" class="btn btn-sm btn-primary me-1">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                                @if(Auth::user()->id == $entry->student_id || Auth::user()->hasRole(['admin', 'judge']))
                                                                    <form action="{{ route('cases.defense_entries.attachments.destroy', [$case->id, $entry->id, $attachment->id]) }}" method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                            
                                            @if($entry->feedback)
                                                <div class="card bg-light mb-3">
                                                    <div class="card-body">
                                                        <h6>ملاحظات المراجعة:</h6>
                                                        <p>{{ $entry->feedback }}</p>
                                                        <p class="mb-0"><strong>التقييم:</strong> {{ $entry->rating }}/10</p>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if(Auth::user()->hasRole(['admin', 'judge', 'instructor']) && !$entry->is_reviewed)
                                                <form action="{{ route('cases.defense_entries.review', [$case->id, $entry->id]) }}" method="POST" class="mt-3">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label for="feedback" class="form-label">ملاحظات المراجعة</label>
                                                        <textarea name="feedback" id="feedback" class="form-control" rows="3" required></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="rating" class="form-label">التقييم (من 1 إلى 10)</label>
                                                        <input type="number" name="rating" id="rating" class="form-control" min="1" max="10" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">إرسال المراجعة</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            لا توجد مذكرات دفاعية لهذه القضية.
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- الأحكام القضائية -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">الأحكام القضائية</h4>
                    @if(Auth::user()->hasRole(['admin', 'judge']))
                        <a href="{{ route('cases.judgments.create', $case->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> إضافة حكم
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($case->judgments && $case->judgments->count() > 0)
                        <div class="accordion" id="accordionJudgments">
                            @foreach($case->judgments as $judgment)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading-judgment-{{ $judgment->id }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-judgment-{{ $judgment->id }}" aria-expanded="false" aria-controls="collapse-judgment-{{ $judgment->id }}">
                                            <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                                <span>{{ $judgment->title }}</span>
                                                <span class="badge bg-info">{{ $judgment->type->name ?? 'حكم' }}</span>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse-judgment-{{ $judgment->id }}" class="accordion-collapse collapse" aria-labelledby="heading-judgment-{{ $judgment->id }}" data-bs-parent="#accordionJudgments">
                                        <div class="accordion-body">
                                            <p><strong>تاريخ الحكم:</strong> {{ $judgment->judgment_date->format('Y-m-d') }}</p>
                                            <p><strong>القاضي:</strong> {{ $judgment->judge->name ?? 'غير محدد' }}</p>
                                            
                                            <h6 class="mb-2">منطوق الحكم:</h6>
                                            <div class="mb-3">
                                                {!! nl2br(e($judgment->ruling)) !!}
                                            </div>
                                            
                                            <h6 class="mb-2">حيثيات الحكم:</h6>
                                            <div class="mb-3">
                                                {!! nl2br(e($judgment->reasoning)) !!}
                                            </div>
                                            
                                            @if($judgment->attachments && $judgment->attachments->count() > 0)
                                                <h6>مرفقات الحكم:</h6>
                                                <ul class="list-group mb-3">
                                                    @foreach($judgment->attachments as $attachment)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            {{ $attachment->title }}
                                                            <div>
                                                                <a href="{{ route('cases.judgments.attachments.download', [$case->id, $judgment->id, $attachment->id]) }}" class="btn btn-sm btn-primary me-1">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                                @if(Auth::user()->hasRole(['admin', 'judge']))
                                                                    <form action="{{ route('cases.judgments.attachments.destroy', [$case->id, $judgment->id, $attachment->id]) }}" method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            لا توجد أحكام قضائية لهذه القضية.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- الجلسات والإجراءات -->
        <div class="col-lg-4">
            <!-- الجلسات -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">جلسات المحكمة</h5>
                    @if(Auth::user()->hasRole(['admin', 'judge']))
                        <a href="{{ route('cases.sessions.create', $case->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> إضافة جلسة
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($case->sessions && $case->sessions->count() > 0)
                        <div class="list-group">
                            @foreach($case->sessions as $session)
                                <a href="{{ route('cases.sessions.show', [$case->id, $session->id]) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $session->title }}</h6>
                                        <span class="badge bg-{{ $session->status->color ?? 'secondary' }}">{{ $session->status->name ?? 'غير محدد' }}</span>
                                    </div>
                                    <p class="mb-1">{{ $session->type->name ?? 'جلسة' }}</p>
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i> {{ $session->date->format('Y-m-d') }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i> {{ $session->time->format('H:i') }}
                                        </small>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            لا توجد جلسات لهذه القضية.
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- الإجراءات -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">الإجراءات</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('cases.show', $case->id) }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-info-circle me-2"></i> عرض تفاصيل القضية
                        </a>
                        @if(Auth::user()->hasRole(['admin', 'judge']) || (Auth::user()->id == $case->created_by))
                            <a href="{{ route('cases.edit', $case->id) }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-edit me-2"></i> تعديل القضية
                            </a>
                        @endif
                        <a href="{{ route('cases.defense_entries.create', $case->id) }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-file-alt me-2"></i> إضافة مذكرة دفاعية
                        </a>
                        @if(Auth::user()->hasRole(['admin', 'judge']))
                            <a href="{{ route('cases.judgments.create', $case->id) }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-gavel me-2"></i> إضافة حكم قضائي
                            </a>
                            <a href="{{ route('cases.sessions.create', $case->id) }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-calendar-plus me-2"></i> جدولة جلسة جديدة
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 