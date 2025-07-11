@include('student.partials.student-head')

<body class="g-sidenav-show rtl bg-gray-200">
  @include('student.partials.student-sidebar')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg overflow-x-hidden">
    @include('student.partials.student-navbar')
    <div class="container-fluid py-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>جميع الطلبات</h4>
        <a href="{{ route('student.requests.create') }}" class="btn btn-primary">إضافة طلب جديد</a>
      </div>
      @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif
      <table class="table table-striped">
        <thead>
          <tr>
            <th>رقم الطلب</th>
            <th>عنوان الطلب</th>
            <th>نوع القضية</th> <!-- إضافة نوع القضية -->
            <th>صفة المدعي</th> <!-- إضافة صفة المدعي -->
            <th>تاريخ الطلب</th>
            <th>الحالة</th>
            <th>موعد الجلسة</th> <!-- إضافة عمود موعد الجلسة -->
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
              <td>{{ $request->status }}</td>
              <td>
                {{ $request->schedule_date ? $request->schedule_date . ' ' . $request->schedule_time : 'لم يتم التحديد بعد' }}
              </td> <!-- عرض موعد الجلسة -->
              <td>
                <a href="{{ route('student.requests.show', $request->id) }}" class="btn btn-info btn-sm">عرض</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center">لا توجد طلبات</td> <!-- تعديل colspan ليعكس العمود الجديد -->
            </tr>
          @endforelse
        </tbody>
      </table>
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
