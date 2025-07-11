@include('judges.partials.judge-head')
<body class="g-sidenav-show rtl bg-gray-200">
    @include('judges.partials.judge-sidebar')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg overflow-x-hidden">
        @include('judges.partials.judge-navbar')
        <div class="container-fluid py-4">
            <div class="container mt-5">
                @forelse($cases as $case)
                    <div class="card mb-4">
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>رقم القضية</th>
                                        <th>تاريخ القضية</th>
                                        <th>نوع القضية</th>
                                        <th>الصفة</th>
                                        <th>المدعي</th>
                                        <th>المدعي عليه</th>
                                        <th>الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $case->id }}</td>
                                        <td>{{ $case->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $case->case_type }}</td> <!-- عرض نوع القضية -->
                                        <td>{{ $case->plaintiff_role }}</td> <!-- عرض صفة المدعي -->
                                        <td>{{ $case->student ? $case->student->name : 'لم يتم التعيين بعد' }}</td>
                                        <td>{{ $case->defendant ? $case->defendant->name : 'لم يتم التعيين بعد' }}</td>
                                        <td>{{ $case->status == 'moved_to_cases' ? 'حولت لقضية' : $case->status }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p><strong>موعد الجلسة:</strong> {{ $case->schedule_date ? $case->schedule_date . ' ' . $case->schedule_time : 'لم يتم التحديد بعد' }}</p>
                                </div>
                                <div>
                                    <a href="{{ route('judge.cases.show', $case->id) }}" class="btn btn-info btn-sm">ملف القضية</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card">
                        <div class="card-body text-center">
                            <p>لا توجد قضايا</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </main>
    <script src="{{ asset('admin-assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/material-dashboard.min.js?v=3.0.0') }}"></script>
</body>
</html>
