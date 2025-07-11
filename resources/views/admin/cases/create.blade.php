@extends('admin.layouts.app')

@section('title', 'إضافة قضية جديدة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">إضافة قضية جديدة</h3>
                    <a href="{{ route('admin.cases.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-right"></i> العودة للقائمة
                    </a>
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

                    <form action="{{ route('admin.cases.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="judge_id" class="form-label">القاضي <span class="text-danger">*</span></label>
                                    <select name="judge_id" id="judge_id" class="form-select @error('judge_id') is-invalid @enderror" required>
                                        <option value="">-- اختر القاضي --</option>
                                        @foreach($judges as $judge)
                                            <option value="{{ $judge->id }}" {{ old('judge_id') == $judge->id ? 'selected' : '' }}>{{ $judge->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="court_type_id" class="form-label">نوع المحكمة <span class="text-danger">*</span></label>
                                    <select name="court_type_id" id="court_type_id" class="form-select @error('court_type_id') is-invalid @enderror" required>
                                        <option value="">-- اختر المحكمة --</option>
                                        @foreach($courtTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('court_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status_id" class="form-label">حالة القضية <span class="text-danger">*</span></label>
                                    <select name="status_id" id="status_id" class="form-select @error('status_id') is-invalid @enderror" required>
                                        <option value="">-- اختر الحالة --</option>
                                        @foreach($caseStatuses as $status)
                                            <option value="{{ $status->id }}" {{ old('status_id') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">تاريخ البدء <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', date('Y-m-d')) }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="close_date" class="form-label">تاريخ الإغلاق</label>
                                    <input type="date" name="close_date" id="close_date" class="form-control @error('close_date') is-invalid @enderror" value="{{ old('close_date') }}">
                                </div>
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