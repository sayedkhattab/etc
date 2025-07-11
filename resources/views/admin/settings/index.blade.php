@extends('admin.layouts.app')

@section('title', 'الإعدادات العامة')

@section('content')
<div class="container-fluid">
 <div class="row">
  <div class="col-12">
   <div class="card">
    <div class="card-header"><h3 class="card-title mb-0">الإعدادات العامة للموقع</h3></div>
    <div class="card-body">
     @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
     @endif

     @if($errors->any())
      <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
     @endif

     <form action="{{ route('admin.settings.update') }}" method="POST">
      @csrf
      <div class="mb-3">
       <label class="form-label" for="site_name">اسم الموقع <span class="text-danger">*</span></label>
       <input type="text" name="site_name" id="site_name" class="form-control @error('site_name') is-invalid @enderror" value="{{ old('site_name', $settings['site_name'] ?? '') }}" required>
      </div>
      <div class="mb-3">
       <label class="form-label" for="contact_email">البريد الإلكتروني للتواصل <span class="text-danger">*</span></label>
       <input type="email" name="contact_email" id="contact_email" class="form-control @error('contact_email') is-invalid @enderror" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" required>
      </div>
      <div class="mb-3">
       <label class="form-label" for="contact_phone">رقم الهاتف</label>
       <input type="text" name="contact_phone" id="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}">
      </div>
      <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> حفظ</button>
     </form>
    </div>
   </div>
  </div>
 </div>
</div>
@endsection 