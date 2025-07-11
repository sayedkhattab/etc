@include('student.partials.student-head')
<body class="g-sidenav-show rtl bg-gray-200">
    @include('student.partials.student-sidebar')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg overflow-x-hidden">
        @include('student.partials.student-navbar')
        <div class="container-fluid py-4">
            <div class="container mt-5">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="row">
                            <div class="col-md-2 text-center">
                                <h4 class="mb-0">رقم القضية</h4>
                                <p class="mb-0" style="margin-top:20px;">{{ $case->id }}</p>
                            </div>
                            <div class="col-md-2 text-center">
                                <h4 class="mb-0">نوع القضية</h4>
                                <p class="mb-0" style="margin-top:20px;">{{ $case->case_type }}</p>
                            </div>
                            <div class="col-md-2 text-center">
                                <h4 class="mb-0">الصفة</h4>
                                <p class="mb-0" style="margin-top:20px;">{{ $case->plaintiff_role }}</p>
                            </div>
                            <div class="col-md-2 text-center">
                                <h4 class="mb-0">المدعي</h4>
                                <p class="mb-0" style="margin-top:20px;">{{ $case->student ? $case->student->name : 'لم يتم التعيين بعد' }}</p>
                            </div>
                            <div class="col-md-2 text-center">
                                <h4 class="mb-0">المدعي عليه</h4>
                                <p class="mb-0" style="margin-top:20px;">{{ $case->defendant ? $case->defendant->name : 'لم يتم التعيين بعد' }}</p>
                            </div>
                            <div class="col-md-2 text-center">
                                <h4 class="mb-0">القاضي</h4>
                                <p class="mb-0" style="margin-top:20px;">{{ $case->judge ? $case->judge->name : 'لم يتم التعيين بعد' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="direction: ltr;">
                        <div class="row">
                            <div class="col-md-9" style="direction: rtl;">
                                <h5 style="color: #597445; text-align: center;">الجلسات</h5>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>موعد الجلسة الأولى:</th>
                                            <td>
                                                {{ $case->schedule_date ? $case->schedule_date . ' ' . $case->schedule_time : 'لم يتم التحديد بعد' }}
                                                @if($case->current_session_date && ($case->current_session_date != $case->schedule_date || $case->current_session_time != $case->schedule_time))
                                                    <span class="badge bg-danger">تم انتهاء الجلسة</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>موعد الجلسة الحالية:</th>
                                            <td>{{ $case->current_session_date ? $case->current_session_date . ' ' . $case->current_session_time : 'لم يتم التحديد بعد' }}</td>
                                        </tr>
                                        <tr>
                                            <th>رابط زووم:</th>
                                            <td>
                                                @if($case->zoom_link)
                                                    <a href="{{ $case->zoom_link }}" target="_blank" class="btn btn-primary mt-3">الذهاب إلى الاجتماع</a>
                                                @else
                                                    لم يتم التحديد بعد
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-3" style="direction: rtl;">
                                <div class="list-group">
                                    <a href="{{ route('student.cases.show', $case->id) }}" class="list-group-item list-group-item-action active">موضوع الدعوى</a>
                                    <a href="{{ route('student.case_details.first_defense', $case->id) }}" class="list-group-item list-group-item-action">مذكرة الدفاع الأولى</a>
                                    <a href="{{ route('student.case_details.parties', $case->id) }}" class="list-group-item list-group-item-action">أطراف الدعوى</a>
                                    <a href="{{ route('student.case_details.sessions', $case->id) }}" class="list-group-item list-group-item-action">الجلسات</a>
                                    <a href="{{ route('student.case_details.judgments', $case->id) }}" class="list-group-item list-group-item-action">الأحكام</a>
                                    <a href="{{ route('student.case_details.requests', $case->id) }}" class="list-group-item list-group-item-action">الطلبات</a>
                                    <a href="{{ route('student.case_details.procedures', $case->id) }}" class="list-group-item list-group-item-action">الإجراءات</a>
                                    <a href="{{ route('student.case_details.decisions', $case->id) }}" class="list-group-item list-group-item-action">القرارات</a>
                                    <a href="{{ route('student.case_details.judicial_costs', $case->id) }}" class="list-group-item list-group-item-action">التكاليف القضائية</a>
                                    <a href="{{ route('student.case_details.attachments', $case->id) }}" class="list-group-item list-group-item-action">المرفقات</a>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('student.cases.index') }}" class="btn btn-primary mt-3">العودة إلى القضايا</a>
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
