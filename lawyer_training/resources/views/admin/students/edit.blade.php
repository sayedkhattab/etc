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
              <h6>تعديل الطالب</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="p-6 text-gray-900">
                @if ($errors->any())
                  <div class="alert alert-danger">
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif

                <form method="POST" action="{{ route('admin.students.update', $student->id) }}" class="p-4">
                  @csrf
                  @method('PUT')
                  <div class="mb-4 form-group">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">اسم الطالب</label>
                    <input id="name" type="text" class="form-control border" name="name" style="padding-right: 20px;" value="{{ old('name', $student->name) }}" required />
                  </div>

                  <div class="mb-4 form-group">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">البريد الإلكتروني</label>
                    <input id="email" type="email" class="form-control border" name="email" style="padding-right: 20px;" value="{{ old('email', $student->email) }}" required />
                  </div>

                  <div class="mb-4 form-group">
                    <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">رقم الهاتف</label>
                    <input id="phone" type="text" class="form-control border" name="phone" style="padding-right: 20px;" value="{{ old('phone', $student->phone) }}" required />
                  </div>

                  <div class="flex items-center justify-between">
                    <button type="submit" class="btn btn-primary">
                      تحديث الطالب
                    </button>
                  </div>
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
