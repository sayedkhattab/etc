@extends('admin.layouts.app')

@section('title','تعديل الإعداد')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-6 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">تعديل الإعداد: {{ $setting->key }}</h3>
                    <a href="{{ route('admin.court-settings.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-right"></i> العودة</a>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('admin.court-settings.update', $setting->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">القيمة</label>
                            <input type="text" name="value" value="{{ old('value', $setting->value) }}" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> حفظ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 