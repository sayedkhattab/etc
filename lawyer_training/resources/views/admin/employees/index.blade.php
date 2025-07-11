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
              <h6>جميع الموظفين</h6>
            </div>
            <div class="card-body">
              @if (session('status'))
                <div class="alert alert-success">
                  {{ session('status') }}
                </div>
              @endif

              <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">إضافة موظف جديد</a>
              </div>

              <div class="table-responsive">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">اسم الموظف</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">البريد الالكتروني</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">رقم الهاتف</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">المنصب</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">الإجراءات</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($employees as $employee)
                      <tr>
                        <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm">
                          {{ $employee->name }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm">
                          {{ $employee->email }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm">
                          {{ $employee->phone }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm">
                          {{ $employee->position }}
                        </td>
                        <td class="align-middle text-center">
                          <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                          <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">حذف</button>
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
      @include('admin.partials.footer')
    </div>
  </div>
</main>
<script src="{{ asset('admin-assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/chartjs.min.js') }}"></script>
