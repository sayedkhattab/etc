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
              <h6>إضافة قاضي جديد</h6>
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

                <form method="POST" action="{{ route('admin.judges.store') }}">
                  @csrf
                  <div class="mb-4 form-group">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">اسم القاضي</label>
                    <input id="name" type="text" class="form-control border" name="name" style="padding-right: 20px;" value="{{ old('name') }}" required />
                  </div>

                  <div class="mb-4 form-group">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">البريد الإلكتروني</label>
                    <input id="email" type="email" class="form-control border" name="email" style="padding-left: 20px; text-align: left;" value="{{ old('email') }}" required />
                  </div>

                  <div class="mb-4 form-group">
                    <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">رقم الهاتف</label>
                    <input id="phone" type="text" class="form-control border" name="phone" style="padding-left: 20px; text-align: left;" value="{{ old('phone') }}" required />
                  </div>

                  <div class="mb-4 form-group">
                    <label for="degree" class="block text-gray-700 text-sm font-bold mb-2">الدرجة</label>
                    <select id="degree" name="degree" class="form-control border" style="padding-right: 20px;">
                      <option value="First Degree" {{ old('degree') == 'First Degree' ? 'selected' : '' }}>الدرجة الأولى</option>
                      <option value="Second Degree" {{ old('degree') == 'Second Degree' ? 'selected' : '' }}>الدرجة الثانية</option>
                    </select>
                  </div>

                  <div class="mb-4 form-group">
                    <label for="specialization" class="block text-gray-700 text-sm font-bold mb-2">التخصص</label>
                    <select id="specialization" name="specialization" class="form-control border" style="padding-right: 20px;">
                      <option value="General Courts" {{ old('specialization') == 'General Courts' ? 'selected' : '' }}>المحاكم العامة</option>
                      <option value="Criminal Courts" {{ old('specialization') == 'Criminal Courts' ? 'selected' : '' }}>المحاكم الجزائية</option>
                      <option value="Personal Status Courts" {{ old('specialization') == 'Personal Status Courts' ? 'selected' : '' }}>محاكم الأحوال الشخصية</option>
                      <option value="Commercial Courts" {{ old('specialization') == 'Commercial Courts' ? 'selected' : '' }}>المحاكم التجارية</option>
                      <option value="Labor Courts" {{ old('specialization') == 'Labor Courts' ? 'selected' : '' }}>المحاكم العمالية</option>
                      <option value="Appeal Court" {{ old('specialization') == 'Appeal Court' ? 'selected' : '' }}>محكمة الاستئناف</option>
                    </select>
                  </div>

                  <div class="mb-4 form-group">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">كلمة المرور</label>
                    <input id="password" type="password" class="form-control border" name="password" style="padding-left: 20px; text-align: left;" required />
                  </div>

                  <div class="mb-4 form-group">
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">تأكيد كلمة المرور</label>
                    <input id="password_confirmation" type="password" class="form-control border" name="password_confirmation" style="padding-left: 20px; text-align: left;" required />
                  </div>

                  <div class="flex items-center justify-between">
                    <button type="submit" class="btn btn-primary">
                      إضافة قاضي
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
