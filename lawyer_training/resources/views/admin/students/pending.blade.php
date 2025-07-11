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
              <h6>طلاب بانتظار الموافقة</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="p-6 text-gray-900">
                @if (session('success'))
                  <div class="alert alert-success mt-3 mx-3">
                    {{ session('success') }}
                  </div>
                @endif

                <div class="table-responsive p-4">
                  <table class="table align-items-center mb-0">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">اسم الطالب</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">البريد الإلكتروني</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">رقم الهاتف</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">حالة الطالب</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($students as $student)
                        <tr>
                          <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $student->name }}
                          </td>
                          <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $student->email }}
                          </td>
                          <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $student->phone }}
                          </td>
                          <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <form action="{{ route('admin.students.approve', $student->id) }}" method="POST" style="display:inline;">
                              @csrf
                              <button type="submit" class="btn btn-success btn-sm">مقبول</button>
                            </form>
                            <form action="{{ route('admin.students.reject', $student->id) }}" method="POST" style="display:inline;">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger btn-sm">مرفوض</button>
                            </form>
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
