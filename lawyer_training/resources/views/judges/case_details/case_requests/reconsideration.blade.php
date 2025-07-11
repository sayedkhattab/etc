@include('judges.partials.judge-head')
<body class="g-sidenav-show rtl bg-gray-200">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
@include('judges.partials.judge-sidebar')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg overflow-x-hidden">
    @include('judges.partials.judge-navbar')
    <div class="container-fluid py-4">
        <div class="container mt-5">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">طلبات الالتماس</h4>
                </div>
                <div class="card-body" style="direction: rtl;">
                    <!-- إضافة زر الرجوع -->
                    <div class="mb-3 text-start">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">الرجوع</a>
                    </div>
                    <!-- نهاية إضافة زر الرجوع -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>رقم الطلب</th>
                                    <th>اسم مقدم الطلب</th>
                                    <th>تاريخ الطلب</th>
                                    <th>الحكم المعترض عليه</th>
                                    <th>تاريخ العلم بالسبب</th>
                                    <th>نص الالتماس</th>
                                    <th>المرفقات</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
    @foreach ($reconsiderationRequests as $request)
        <tr>
            <td>{{ $request->id }}</td>
            <td>{{ $request->student->name }}</td>
            <td>{{ $request->created_at->format('Y-m-d') }}</td>
            <td>{{ $request->judgment->content }}</td>
            <td>{{ $request->awareness_date }}</td>
            <td>{{ $request->appeal_text }}</td>
            <td>
                @if($request->file_path)
                    @php
                        $filename = basename($request->file_path);
                    @endphp
                    <ul>
                    <li><a href="{{ route('judge.download.reconsideration_attachment', ['caseId' => $request->case->id, 'filename' => $filename]) }}" target="_blank">تحميل المرفق</a></li>
                    @endif
                </td>
            <td>{{ $request->status }}</td>
            <td>
                @if($request->status == 'pending')
                    <form action="{{ route('judges.reconsideration_requests.accept', $request->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">قبول</button>
                    </form>
                    <form action="{{ route('judges.reconsideration_requests.reject', $request->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">رفض</button>
                    </form>
                @endif
            </td>
        </tr>
    @endforeach
</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('judges.partials.judge-footer')
</main>
<script src="{{ asset('admin-assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/smooth-scrollbar.min.js') }}"></script>

@if(session('success'))
    <script>
        Swal.fire({
            title: 'نجاح',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'موافق'
        });
    </script>
@endif
</body>
</html>
