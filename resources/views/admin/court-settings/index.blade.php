@extends('admin.layouts.app')

@section('title','إعدادات المحكمة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">جميع الإعدادات</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>المجموعة</th>
                                    <th>المفتاح</th>
                                    <th>القيمة</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($settings as $setting)
                                <tr>
                                    <td>{{ $setting->group }}</td>
                                    <td>{{ $setting->key }}</td>
                                    <td>{{ Str::limit($setting->value, 50) }}</td>
                                    <td>
                                        <a href="{{ route('admin.court-settings.edit', $setting->id) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">لا توجد إعدادات حتى الآن</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $settings->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 