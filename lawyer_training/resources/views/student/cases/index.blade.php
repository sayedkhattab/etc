@include('student.partials.student-head')
<body class="g-sidenav-show rtl bg-gray-200">
    @include('student.partials.student-sidebar')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg overflow-x-hidden">
        @include('student.partials.student-navbar')
        <div class="container-fluid py-4">
            <div class="container mt-5">
                <ul class="nav nav-tabs" id="caseTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="plaintiff-cases-tab" data-bs-toggle="tab" href="#plaintiff-cases" role="tab" aria-controls="plaintiff-cases" aria-selected="true">القضايا (مدعي)</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="defendant-cases-tab" data-bs-toggle="tab" href="#defendant-cases" role="tab" aria-controls="defendant-cases" aria-selected="false">القضايا (مدعي عليه)</a>
                    </li>
                </ul>
                <div class="tab-content" id="caseTabsContent">
                    <!-- القضايا التي يكون فيها الطالب مدعياً -->
                    <div class="tab-pane fade show active" id="plaintiff-cases" role="tabpanel" aria-labelledby="plaintiff-cases-tab">
                        @forelse($plaintiffCases as $case)
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
                                                <td>{{ $case->case_type }}</td>
                                                <td>{{ $case->plaintiff_role }}</td>
                                                <td>{{ $case->student ? $case->student->name : 'لم يتم التعيين بعد' }}</td>
                                                <td>{{ $case->defendant ? $case->defendant->name : 'لم يتم التعيين بعد' }}</td>
                                                <td>{{ $case->status }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p><strong>موعد الجلسة:</strong> {{ $case->schedule_date ? $case->schedule_date . ' ' . $case->schedule_time : 'لم يتم التحديد بعد' }}</p>
                                        </div>
                                        <div>
                                            <a href="{{ route('student.cases.show', $case->id) }}" class="btn btn-info btn-sm">ملف القضية</a>
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
                    
                    <!-- القضايا التي يكون فيها الطالب مدعى عليه -->
                    <div class="tab-pane fade" id="defendant-cases" role="tabpanel" aria-labelledby="defendant-cases-tab">
                        @forelse($defendantCases as $case)
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
                                                <td>{{ $case->case_type }}</td>
                                                <td>{{ $case->plaintiff_role }}</td>
                                                <td>{{ $case->student ? $case->student->name : 'لم يتم التعيين بعد' }}</td>
                                                <td>{{ $case->defendant ? $case->defendant->name : 'لم يتم التعيين بعد' }}</td>
                                                <td>{{ $case->status }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p><strong>موعد الجلسة:</strong> {{ $case->schedule_date ? $case->schedule_date . ' ' . $case->schedule_time : 'لم يتم التحديد بعد' }}</p>
                                        </div>
                                        <div>
                                            <a href="{{ route('student.cases.show', $case->id) }}" class="btn btn-info btn-sm">ملف القضية</a>
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
            </div>
            @include('student.partials.student-footer')
        </div>
    </main>
    <script src="{{ asset('admin-assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/material-dashboard.min.js?v=3.0.0') }}"></script>
</body>
</html>
