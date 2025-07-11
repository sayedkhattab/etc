@extends('admin.layouts.app')

@section('title', 'الشهادات')

@section('content')
<div class="container-fluid">
 <div class="row">
  <div class="col-12">
   <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
     <h3 class="card-title mb-0">قائمة الشهادات</h3>
     <a href="{{ route('admin.certificates.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus"></i> إصدار شهادة</a>
    </div>
    <div class="card-body">
     <table class="table table-bordered table-hover">
      <thead><tr><th>#</th><th>الشهادة</th><th>الطالب</th><th>الدورة</th><th>تاريخ الإصدار</th><th>الإجراءات</th></tr></thead>
      <tbody>
       @foreach($certificates as $cert)
        <tr>
         <td>{{ $loop->iteration }}</td>
         <td>{{ $cert->certificate_number }}</td>
         <td>{{ $cert->student?->name }}</td>
         <td>{{ $cert->course?->title }}</td>
         <td>{{ $cert->issue_date?->format('Y-m-d') }}</td>
         <td>
          <a href="{{ route('admin.certificates.show', $cert) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
          <form action="{{ route('admin.certificates.destroy', $cert) }}" method="POST" class="d-inline-block" onsubmit="return confirm('حذف؟');">
           @csrf @method('DELETE')
           <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
          </form>
         </td>
        </tr>
       @endforeach
      </tbody>
     </table>
     {{ $certificates->links() }}
    </div>
   </div>
  </div>
 </div>
</div>
@endsection 