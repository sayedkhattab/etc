@include('judges.partials.judge-head')
<body class="g-sidenav-show rtl bg-gray-200">
    @include('judges.partials.judge-sidebar')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg overflow-x-hidden">
        @include('judges.partials.judge-navbar')
        <div class="container-fluid py-4">
            <div class="container mt-5">
                <h4>الطلبات الموكلة</h4>
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>رقم الطلب</th>
                                    <th>عنوان الطلب</th>
                                    <th>نوع القضية</th> <!-- إضافة عمود نوع القضية -->
                                    <th>صفة المدعي</th> <!-- إضافة عمود صفة المدعي -->
                                    <th>تاريخ الطلب</th>
                                    <th>الحالة</th>
                                    <th>موعد الجلسة</th>
                                    <th>إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests as $request)
                                    <tr>
                                        <td>{{ $request->id }}</td>
                                        <td>{{ $request->title }}</td>
                                        <td>{{ $request->case_type }}</td> <!-- عرض نوع القضية -->
                                        <td>{{ $request->plaintiff_role }}</td> <!-- عرض صفة المدعي -->
                                        <td>{{ $request->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            @if($request->status == 'approved')
                                                موافق عليها
                                            @elseif($request->status == 'moved_to_cases')
                                                حولت إلى قضية
                                            @else
                                                {{ $request->status }}
                                            @endif
                                        </td>
                                        <td>{{ $request->schedule_date ? $request->schedule_date . ' ' . $request->schedule_time : 'لم يتم التحديد بعد' }}</td>
                                        <td>
                                            <a href="{{ route('judge.requests.show', $request->id) }}" class="btn btn-info btn-sm">مشاهدة القضية</a>
                                            @if($request->schedule_date)
                                                <form action="{{ route('judge.requests.moveToCases', $request->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-primary btn-sm">نقل إلى القضايا</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">لا توجد طلبات</td> <!-- تعديل colspan ليعكس العمود الجديد -->
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @include('judges.partials.judge-footer')
        </div>
    </main>
    <script src="{{ asset('admin-assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/material-dashboard.min.js?v=3.0.0') }}"></script>
</body>
</html>
