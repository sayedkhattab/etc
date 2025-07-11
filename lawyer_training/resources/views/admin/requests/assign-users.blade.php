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
              <h6>تعيين المستخدمين للقضية</h6>
            </div>
            <div class="card-body">
              @if ($errors->any())
                <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              <form action="{{ route('admin.requests.storeAssignments', $request->id) }}" method="POST">
                @csrf

                <div class="mb-4 form-group">
                  <label for="defendant_id" class="form-label">المدعي عليه</label>
                  <select id="defendant_id" name="defendant_id" class="form-select" required>
                    @foreach ($students as $student)
                      <option value="{{ $student->id }}">{{ $student->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="mb-4 form-group">
                  <label for="judge_id" class="form-label">القاضي</label>
                  <select id="judge_id" name="judge_id" class="form-select" required>
                    @foreach ($judges as $judge)
                      <option value="{{ $judge->id }}">{{ $judge->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="mb-4 form-group">
                  <label class="form-label">الشهود</label>
                  @foreach ($employees as $employee)
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="witness_ids[]" value="{{ $employee->id }}" id="witness_{{ $employee->id }}">
                      <label class="form-check-label" for="witness_{{ $employee->id }}">
                        {{ $employee->name }}
                      </label>
                    </div>
                  @endforeach
                </div>

                <div class="d-flex justify-content-between">
                  <button type="submit" class="btn btn-primary">تعيين</button>
                </div>
              </form>
            </div>
            <div class="card-footer">
              <a href="{{ route('admin.requests.index') }}" class="btn btn-primary">العودة إلى الطلبات</a>
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
