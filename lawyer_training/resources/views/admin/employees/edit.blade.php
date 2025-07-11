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
              <h6>تعديل الموظف</h6>
            </div>
            <div class="card-body">
              <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                  <label for="name" class="form-label">اسم الموظف</label>
                  <input type="text" class="form-control border border-light px-2" id="name" name="name" value="{{ $employee->name }}" required>
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">البريد الالكتروني</label>
                  <input type="email" class="form-control border border-light px-2" id="email" name="email" style="text-align: left;" value="{{ $employee->email }}" required>
                </div>
                <div class="mb-3">
                  <label for="phone" class="form-label">رقم الهاتف</label>
                  <input type="text" class="form-control border border-light px-2" id="phone" name="phone" style="text-align: left;" value="{{ $employee->phone }}" required>
                </div>
                <div class="mb-3">
                  <label for="position" class="form-label">المنصب</label>
                  <input type="text" class="form-control border border-light px-2" id="position" name="position" value="{{ $employee->position }}" required>
                </div>
                <button type="submit" class="btn btn-success">تحديث</button>
              </form>
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
