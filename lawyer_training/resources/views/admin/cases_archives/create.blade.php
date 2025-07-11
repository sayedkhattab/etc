@include('admin.partials.header')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg overflow-x-hidden">
  @include('admin.partials.sidebar')
  <div class="main-content">
    @include('admin.partials.navbar')
    <div class="container-fluid py-4">
      <!-- محتوى الصفحة الرئيسي -->
      <div class="row">
        <div class="col-lg-12 mb-lg-0 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <h6>إضافة أرشيف جديد</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="p-6 text-gray-900">
                @if ($errors->any())
                  <div class="alert alert-danger mt-3 mx-3">
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif

                <form action="{{ route('admin.case-archives.store') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="form-group">
                    <label for="court_type">نوع المحكمة</label>
                    <select id="court_type" name="court_type" class="form-control" required>
                      <option value="عامة">عامة</option>
                      <option value="تجارية">تجارية</option>
                      <option value="أحوال شخصية">أحوال شخصية</option>
                      <option value="أخرى">أخرى</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="case_number">رقم القضية</label>
                    <input type="text" id="case_number" name="case_number" class="form-control" required>
                  </div>
                  <div class="form-group">
                    <label for="files">ملفات الأرشيف</label>
                    <input type="file" id="files" name="files[]" class="form-control" multiple required>
                  </div>
                  <div class="form-group">
                    <label for="file_types">نوع الملف</label>
                    <select id="file_types" name="file_types[]" class="form-control" multiple required>
                      <option value="مدعي">مدعي</option>
                      <option value="مدعي عليه">مدعي عليه</option>
                      <option value="قاضي">قاضي</option>
                    </select>
                  </div>
                  <button type="submit" class="btn btn-success">إضافة</button>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
      @include('admin.partials.footer')
    </div>
  </div>
</main>
<script src="{{ asset('admin-assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/chartjs.min.js') }}"></script>
