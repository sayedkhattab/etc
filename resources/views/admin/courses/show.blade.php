@extends('admin.layouts.app')

@section('title', $course->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">تفاصيل الدورة</h3>
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-right"></i> العودة للقائمة
                    </a>
                </div>
                <div class="card-body">
                    <h3 class="mb-3">{{ $course->title }}</h3>

                    <table class="table table-bordered mb-4">
                        <tbody>
                            <tr>
                                <th style="width: 150px;">التصنيف:</th>
                                <td class="text-start">{{ $course->category?->name }}</td>
                            </tr>
                            <tr>
                                <th>الوصف:</th>
                                <td class="text-start">{{ $course->description ?: '-' }}</td>
                            </tr>
                            <tr>
                                <th>السعر:</th>
                                <td class="text-start">{{ $course->price }} ر.س</td>
                            </tr>
                            <tr>
                                <th>الحالة:</th>
                                <td class="text-start"><span class="badge bg-{{ $course->status === 'active' ? 'success' : 'secondary' }}">{{ $course->status }}</span></td>
                            </tr>
                            <tr>
                                <th>الظهور:</th>
                                <td class="text-start text-capitalize">{{ $course->visibility }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.courses.levels.index', $course) }}" class="btn btn-info">
                            <i class="bi bi-diagram-3"></i> إدارة المستويات
                        </a>
                        <a href="{{ route('admin.courses.levels.create', $course) }}" class="btn btn-success">
                            <i class="bi bi-plus-circle"></i> إضافة مستوى جديد
                        </a>
                        <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> تعديل
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 