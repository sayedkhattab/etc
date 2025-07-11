@extends('admin.layouts.app')

@section('title', 'إضافة جلسة جديدة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">إضافة جلسة جديدة</h3>
                    <a href="{{ route('admin.sessions.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-right"></i> العودة للقائمة</a>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.sessions.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="case_id">القضية <span class="text-danger">*</span></label>
                                <select name="case_id" id="case_id" class="form-select @error('case_id') is-invalid @enderror" required>
                                    <option value="">-- اختر القضية --</option>
                                    @foreach($cases as $c)
                                        <option value="{{ $c->id }}" {{ old('case_id') == $c->id ? 'selected' : '' }}>{{ $c->case_number }} - القاضي: {{ $c->judge?->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="session_type_id">نوع الجلسة <span class="text-danger">*</span></label>
                                <select name="session_type_id" id="session_type_id" class="form-select @error('session_type_id') is-invalid @enderror" required>
                                    <option value="">-- اختر النوع --</option>
                                    @foreach($sessionTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('session_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="title">عنوان الجلسة <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="date_time">تاريخ ووقت الجلسة <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="date_time" id="date_time" class="form-control @error('date_time') is-invalid @enderror" value="{{ old('date_time') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="duration">المدة (دقيقة)</label>
                                <input type="number" name="duration" id="duration" class="form-control @error('duration') is-invalid @enderror" value="{{ old('duration') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="location">المكان</label>
                                <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="status_id">الحالة <span class="text-danger">*</span></label>
                                <select name="status_id" id="status_id" class="form-select @error('status_id') is-invalid @enderror" required>
                                    <option value="">-- اختر الحالة --</option>
                                    @foreach($sessionStatus as $stat)
                                        <option value="{{ $stat->id }}" {{ old('status_id') == $stat->id ? 'selected' : '' }}>{{ $stat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> حفظ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 