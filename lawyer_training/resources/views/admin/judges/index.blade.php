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
              <h6>جميع القضاة</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="p-6 text-gray-900">
                @if (session('status'))
                  <div class="alert alert-success mt-3 mx-3">
                    {{ session('status') }}
                  </div>
                @endif

                <div class="d-flex justify-content-end mb-3">
                  <a href="{{ route('admin.judges.create') }}" class="btn btn-primary">إضافة قاضي جديد</a>
                </div>

                <div class="table-responsive p-4">
                  <table class="table align-items-center mb-0">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">اسم القاضي</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">البريد الإلكتروني</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">رقم الهاتف</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">الدرجة</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">التخصص</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">الإجراءات</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($judges as $judge)
                        <tr>
                          <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $judge->name }}
                          </td>
                          <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $judge->email }}
                          </td>
                          <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $judge->phone }}
                          </td>
                          <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $judge->degree == 'First Degree' ? 'الدرجة الأولى' : 'الدرجة الثانية' }}
                          </td>
                          <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            @switch($judge->specialization)
                              @case('General Courts')
                                المحاكم العامة
                                @break
                              @case('Criminal Courts')
                                المحاكم الجزائية
                                @break
                              @case('Personal Status Courts')
                                محاكم الأحوال الشخصية
                                @break
                              @case('Commercial Courts')
                                المحاكم التجارية
                                @break
                              @case('Labor Courts')
                                المحاكم العمالية
                                @break
                              @case('Appeal Court')
                                محكمة الاستئناف
                                @break
                            @endswitch
                          </td>
                          <td>
                            <a href="{{ route('admin.judges.edit', $judge->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                            <form action="{{ route('admin.judges.destroy', $judge->id) }}" method="POST" style="display:inline;">
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
