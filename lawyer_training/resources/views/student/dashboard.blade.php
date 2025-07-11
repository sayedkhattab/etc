<!-- resources/views/student/dashboard.blade.php -->
@include('student.partials.student-head')

<body class="g-sidenav-show rtl bg-gray-200">
  @include('student.partials.student-sidebar')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg overflow-x-hidden">
    @include('student.partials.student-navbar')
    <div class="container-fluid py-4">
      <div class="row mt-4">
        <div class="col-lg-4 col-md-6 mt-4 mb-4">
          <div class="card z-index-2 ">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
              <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                <div class="chart">
                </div>
              </div>
            </div>
            <div class="card-body">
              <h6 class="mb-0 ">لوحة تحكم الطالب</h6>
              <p class="text-sm ">نص تعريفي لوحة تحكم الطالب</p>
              <hr class="dark horizontal">
            </div>
          </div>
        </div>
        <!-- يمكنك إضافة المزيد من البطاقات هنا -->
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
