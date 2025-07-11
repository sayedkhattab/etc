@extends('admin.layouts.app')

@section('title','المذكرات الدفاعية')

@section('content')
<div class="container-fluid"><div class="row"><div class="col-12"><div class="card">
<div class="card-header d-flex justify-content-between align-items-center"><h3 class="card-title mb-0">قائمة المذكرات الدفاعية</h3><a href="{{ route('admin.defense-entries.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus"></i> إضافة مذكرة</a></div>
<div class="card-body">
<table class="table table-bordered table-hover"><thead><tr><th>#</th><th>القضية</th><th>الطالب</th><th>العنوان</th><th>الحالة</th><th>الإجراءات</th></tr></thead><tbody>
@foreach($entries as $entry)
<tr><td>{{ $loop->iteration }}</td><td>{{ $entry->case?->case_number }}</td><td>{{ $entry->student?->name }}</td><td>{{ $entry->title }}</td><td>{{ $entry->status }}</td>
<td><a class="btn btn-sm btn-info" href="{{ route('admin.defense-entries.show',$entry) }}"><i class="bi bi-eye"></i></a>
<form class="d-inline-block" method="POST" action="{{ route('admin.defense-entries.destroy',$entry) }}" onsubmit="return confirm('حذف؟');">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form></td></tr>
@endforeach
</tbody></table>
{{ $entries->links() }}
</div></div></div></div></div>
@endsection 