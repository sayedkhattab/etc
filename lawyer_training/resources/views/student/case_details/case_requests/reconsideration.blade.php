@include('student.partials.student-head')
<body class="g-sidenav-show rtl bg-gray-200">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
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
                                <h5 style="color: #597445; text-align: center;">طلب إلتماس</h5>
                                <form action="{{ route('student.case_requests.storeReconsideration', $case->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="judgment_id" class="form-label">اختر الحكم:</label>
                                        <select class="form-control" id="judgment_id" name="judgment_id" required>
                                            @foreach ($case->judgments as $judgment)
                                                <option value="{{ $judgment->id }}">{{ $judgment->content }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="awareness_date" class="form-label">تاريخ العلم بالسبب:</label>
                                        <input type="date" class="form-control" id="awareness_date" name="awareness_date" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="appeal_text" class="form-label">نص الالتماس:</label>
                                        <textarea class="form-control" style="border: solid 1px #dddddd;" id="appeal_text" name="appeal_text" rows="5" required></textarea>
                                    </div>
                                    <div class="mb-3 text-start">
                                        <label for="file" class="form-label">إرفاق ملف:</label>
                                        <input type="file" class="form-control" id="file" name="file">
                                    </div>
                                    <button type="submit" class="btn btn-success mt-3">إرسال الطلب</button>
                                </form>

                               
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

                        <div class="card-body" style="direction: rtl;">
                        <div class="row">

                        <h5 style="color: #597445; text-align: center;" class="mt-5">الطلبات المقدمة</h5>
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
                                                                <li><a href="{{ route('download.reconsideration_attachment', ['caseId' => $case->id, 'filename' => $filename]) }}" target="_blank">تحميل المرفق</a></li>
                                                            </ul>
                                                        @endif
                                                    </td>
                                                    <td>{{ $request->status }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
    
    @if(session('success'))
        <script>
            Swal.fire({
                title: 'تم تقديم الطلب',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'موافق'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('student.case_requests.reconsideration', $case->id) }}";
                }
            });
        </script>
    @endif
</body>
</html>
