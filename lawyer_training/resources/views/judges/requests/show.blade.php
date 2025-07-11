@include('judges.partials.judge-head')
<body class="g-sidenav-show rtl bg-gray-200">
    @include('judges.partials.judge-sidebar')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg overflow-x-hidden">
        @include('judges.partials.judge-navbar')
        <div class="container-fluid py-4">
            <div class="container mt-5">
                <div class="card">
                    <div class="card-header bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                        <h4>{{ $request->title }}</h4>
                    </div>
                    <div class="card-body bg-white dark:bg-gray-700">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>الوصف:</th>
                                    <td>{{ $request->description }}</td>
                                </tr>
                                <tr>
                                    <th>نوع القضية:</th>
                                    <td>{{ $request->case_type }}</td> <!-- عرض نوع القضية -->
                                </tr>
                                <tr>
                                    <th>صفة المدعي:</th>
                                    <td>{{ $request->plaintiff_role }}</td> <!-- عرض صفة المدعي -->
                                </tr>
                                <tr>
                                    <th>تاريخ الإرسال:</th>
                                    <td>{{ $request->created_at->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <th>الحالة:</th>
                                    <td>{{ $request->status }}</td>
                                </tr>
                                <tr>
                                    <th>المرفقات:</th>
                                    <td>
                                        <ul>
                                            @if ($request->attachments->isNotEmpty())
                                                @foreach ($request->attachments as $attachment)
                                                    <li><a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank">عرض المرفق</a></li>
                                                @endforeach
                                            @else
                                                <li>لا توجد مرفقات</li>
                                            @endif
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>القاضي:</th>
                                    <td>{{ $request->judge ? $request->judge->name : 'لم يتم التعيين بعد' }}</td>
                                </tr>
                                <tr>
                                    <th>المدعي عليه:</th>
                                    <td>{{ $request->defendant ? $request->defendant->name : 'لم يتم التعيين بعد' }}</td>
                                </tr>
                                <tr>
                                    <th>الشهود:</th>
                                    <td>
                                        <ul>
                                            @if ($request->witnesses->isNotEmpty())
                                                @foreach ($request->witnesses as $witness)
                                                    <li>{{ $witness->name }}</li>
                                                @endforeach
                                            @else
                                                <li>لم يتم تعيين شهود بعد</li>
                                            @endif
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                        <a href="{{ route('judge.requests.schedule', $request->id) }}" class="btn btn-secondary">تحديد موعد الجلسة</a>
                    </div>
                </div>
                <a href="{{ route('judge.requests.index') }}" class="btn btn-primary mt-3">العودة إلى الطلبات</a>
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
