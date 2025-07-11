@include('student.partials.student-head')

<body class="g-sidenav-show rtl bg-gray-200">
  @include('student.partials.student-sidebar')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg overflow-x-hidden">
    @include('student.partials.student-navbar')

    <div class="container mt-4">
      <div class="card shadow-sm border-light">
        <div class="card-header bg-gradient-primary text-white">
          <h4 class="mb-0" style="color: white; text-align: center;">طلب جديد</h4>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('student.requests.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
              <label for="title" class="form-label">عنوان الطلب</label>
              <input type="text" class="form-control border border-light" id="title" name="title" style="padding-right: 20px;" required>
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">وصف الطلب</label>
              <textarea class="form-control border border-light" id="description" name="description" rows="4" style="padding-right: 20px;" required></textarea>
            </div>
            <div class="mb-3">
              <label for="case_type" class="form-label">نوع القضية</label>
              <select class="form-control border border-light" id="case_type" name="case_type" style="padding-right: 20px;" required>
                <option value="">اختر نوع القضية</option>
                <option value="عمالية">عمالية</option>
                <option value="شخصية">شخصية</option>
                <option value="تجارية">تجارية</option>
                <option value="عامة">عامة</option>
                <option value="أحوال شخصية">أحوال شخصية</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="plaintiff_role" class="form-label">صفة المدعي</label>
              <select class="form-control border border-light" id="plaintiff_role" name="plaintiff_role" style="padding-right: 20px;" required>
                <option value="">اختر صفة المدعي</option>
                <option value="أصيل">أصيل</option>
                <option value="وكيل">وكيل</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="attachments" class="form-label">المرفقات</label>
              <input type="file" class="form-control-file" id="attachments" name="attachments[]" multiple>
            </div>
            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-success btn-block">إرسال الطلب</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    @include('student.partials.student-footer')
  </main>

  <!--   Core JS Files   -->
  <script src="{{ asset('admin-assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('admin-assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('admin-assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('admin-assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script src="{{ asset('admin-assets/js/material-dashboard.min.js?v=3.0.0') }}"></script>
</body>
</html>
