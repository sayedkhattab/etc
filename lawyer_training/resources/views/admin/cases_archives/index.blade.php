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
              <h6>أرشيف القضايا</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="p-6 text-gray-900">
                @if (session('status'))
                  <div class="alert alert-success mt-3 mx-3">
                    {{ session('status') }}
                  </div>
                @endif

                <div class="d-flex justify-content-end mb-3">
                  <a href="{{ route('admin.case-archives.create') }}" class="btn btn-primary">إضافة أرشيف جديد</a>
                </div>

                <div class="table-responsive p-4">
                  <table class="table align-items-center mb-0">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">رقم القضية</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">نوع المحكمة</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">تاريخ الإنشاء</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($caseArchives as $archive)
                        <tr>
                          <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $archive->case_number }}
                          </td>
                          <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $archive->court_type }}
                          </td>
                          <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $archive->created_at }}
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
