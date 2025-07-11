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
              <h6>طلبات الطلاب</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                @if (session('status'))
                  <div class="alert alert-success mt-3 mx-3">
                    {{ session('status') }}
                  </div>
                @endif
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">رقم الطلب</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">عنوان الطلب</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">تاريخ الطلب</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">الحالة</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">الإجراءات</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($requests as $request)
                      <tr>
                        <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm">
                          {{ $request->id }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm">
                          {{ $request->title }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm">
                          {{ $request->created_at->format('Y-m-d') }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm">
                          {{ $request->status }}
                        </td>
                        <td class="align-middle text-center">
                          @if($request->status == 'approved')
                            <a href="{{ route('admin.requests.show', $request->id) }}" class="btn btn-info btn-sm">ملف القضية</a>
                            <a href="{{ route('admin.requests.assignUsers', $request->id) }}" class="btn btn-info btn-sm">تعيين المستخدمين</a>
                          @else
                            <a href="{{ route('admin.requests.show', $request->id) }}" class="btn btn-info btn-sm">عرض</a>
                            <form action="{{ route('admin.requests.approve', $request->id) }}" method="POST" style="display:inline;">
                              @csrf
                              @method('PATCH')
                              <button type="submit" class="btn btn-success btn-sm">موافقة</button>
                            </form>
                            <form action="{{ route('admin.requests.reject', $request->id) }}" method="POST" style="display:inline;">
                              @csrf
                              @method('PATCH')
                              <button type="submit" class="btn btn-danger btn-sm">رفض</button>
                            </form>
                          @endif
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="5" class="text-center">لا توجد طلبات</td>
                      </tr>
                    @endforelse
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
