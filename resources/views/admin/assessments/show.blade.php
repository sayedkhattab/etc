@extends('admin.layouts.app')

@section('title', 'تفاصيل التقييم')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">تفاصيل التقييم</h3>
                    <div>
                        <a href="{{ route('admin.assessments.edit', $assessment) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i> تعديل
                        </a>
                        <a href="{{ route('admin.assessments.index') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-right"></i> العودة للقائمة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <p><strong>العنوان:</strong> {{ $assessment->title }}</p>
                    <p><strong>الدورة / المستوى:</strong> {{ $assessment->level?->course?->title }} / {{ $assessment->level?->title }}</p>
                    <p><strong>نوع التقييم:</strong> {{ $assessment->assessmentType?->name }}</p>
                    <p><strong>درجة النجاح:</strong> {{ $assessment->passing_score }}%</p>
                    <p><strong>عدد المحاولات:</strong> {{ $assessment->attempts_allowed }}</p>
                    <p><strong>الحالة:</strong> {{ $assessment->status }}</p>
                    <p><strong>نشط:</strong> {{ $assessment->is_active ? 'نعم' : 'لا' }}</p>
                    <p><strong>اختبار تحديد مستوى:</strong> {{ $assessment->is_pretest ? 'نعم' : 'لا' }}</p>

                    <a href="{{ route('admin.questions.index', [], false) }}?assessment_id={{ $assessment->id }}" class="btn btn-info">
                        <i class="bi bi-question-circle"></i> إدارة الأسئلة
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 