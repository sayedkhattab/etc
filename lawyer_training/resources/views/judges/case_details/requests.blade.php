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
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="card border-success" style="border: 1px solid #ddd;">
                                            <div class="card-body text-center">
                                                <a href="{{ route('judge.case_requests.reconsideration', $case->id) }}" class="text-decoration-none">
                                                    <i class="fa fa-gavel fa-2x mb-2" style="color: #597445;"></i>
                                                        <h5 class="card-title" style="color: #000000;">إلتماس/إعادة نظر</h5>
                                                    <p class="card-text">وصف الطلب</p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <div class="card border-success" style="border: 1px solid #ddd;">
                                            <div class="card-body text-center">
                                                <i class="fa fa-times-circle fa-2x mb-2" style="color: #597445;"></i>
                                                    <h5 class="card-title" style="color: #000000;">الاعتراض على الحكم</h5>
                                                <p class="card-text">وصف الطلب</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card border-success" style="border: 1px solid #ddd;">
                                            <div class="card-body text-center">
                                                <i class="fa fa-user-plus fa-2x mb-2" style="color: #597445;"></i>
                                                    <h5 class="card-title" style="color: #000000;">طلب إضافة ممثل</h5>
                                                <p class="card-text">وصف الطلب</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Add more cards for each request as needed -->
                                    <div class="col-md-4 mb-3">
                                        <div class="card border-success" style="border: 1px solid #ddd;">
                                            <div class="card-body text-center">
                                                <i class="fa fa-plus-circle fa-2x mb-2" style="color: #597445;"></i>
                                                    <h5 class="card-title" style="color: #000000;">طلب إدخال</h5>
                                                <p class="card-text">وصف الطلب</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card border-success" style="border: 1px solid #ddd;">
                                            <div class="card-body text-center">
                                                <i class="fa fa-eye fa-2x mb-2" style="color: #597445;"></i>
                                                <h5 class="card-title" style="color: #000000;">إعادة النظر</h5>
                                                <p class="card-text">وصف الطلب</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card border-success" style="border: 1px solid #ddd;">
                                            <div class="card-body text-center">
                                                <i class="fa fa-file-alt fa-2x mb-2" style="color: #597445;"></i>
                                                <h5 class="card-title" style="color: #000000;">طلب إبداء مذكرة</h5>
                                                <p class="card-text">وصف الطلب</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card border-success" style="border: 1px solid #ddd;">
                                            <div class="card-body text-center">
                                                <i class="fa fa-edit fa-2x mb-2" style="color: #597445;"></i>
                                                <h5 class="card-title" style="color: #000000;">تصحيح الطلب الأصلي</h5>
                                                <p class="card-text">وصف الطلب</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card border-success" style="border: 1px solid #ddd;">
                                            <div class="card-body text-center">
                                                <i class="fa fa-handshake fa-2x mb-2" style="color: #597445;"></i>
                                                <h5 class="card-title" style="color: #000000;">طلب ترك الخصومة</h5>
                                                <p class="card-text">وصف الطلب</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card border-success" style="border: 1px solid #ddd;">
                                            <div class="card-body text-center">
                                                <i class="fa fa-ban fa-2x mb-2" style="color: #597445;"></i>
                                                <h5 class="card-title" style="color: #000000;">طلب رد قاضي</h5>
                                                <p class="card-text">وصف الطلب</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card border-success" style="border: 1px solid #ddd;">
                                            <div class="card-body text-center">
                                                <i class="fa fa-play-circle fa-2x mb-2" style="color: #597445;"></i>
                                                <h5 class="card-title" style="color: #000000;">استمرار السير في الدعوى</h5>
                                                <p class="card-text">وصف الطلب</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card border-success" style="border: 1px solid #ddd;">
                                            <div class="card-body text-center">
                                                <i class="fa fa-bell fa-2x mb-2" style="color: #597445;"></i>
                                                <h5 class="card-title" style="color: #000000;">رفع التبليغ بالمراجعة</h5>
                                                <p class="card-text">وصف الطلب</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card border-success" style="border: 1px solid #ddd;">
                                            <div class="card-body text-center">
                                                <i class="fa fa-check-circle fa-2x mb-2" style="color: #597445;"></i>
                                                <h5 class="card-title" style="color: #000000;">إقرار القناعة بالحكم</h5>
                                                <p class="card-text">وصف الطلب</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Continue adding cards as needed -->
                                </div>
                            </div>
                            <div class="col-md-3" style="direction: rtl;">
                                <div class="list-group">
                                    <a href="{{ route('student.cases.show', $case->id) }}" class="list-group-item list-group-item-action active">
                                        موضوع الدعوى
                                    </a>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</body>
</html>
