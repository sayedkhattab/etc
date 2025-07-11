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
              <h6>تفاصيل الطلب</h6>
            </div>
            <div class="card-body">
              <h2>{{ $request->title }}</h2>
              <div class="row mt-4">
                <div class="col-md-6">
                  <p><strong>الوصف:</strong> {{ $request->description }}</p>
                  <p><strong>تاريخ الإرسال:</strong> {{ $request->created_at->format('Y-m-d') }}</p>
                  <p><strong>الحالة:</strong> {{ $request->status }}</p>
                </div>
                <div class="col-md-6">
                  @if($request->defendant)
                    <p><strong>المدعي عليه:</strong> {{ $request->defendant->name }}</p>
                  @endif
                  @if($request->judge)
                    <p><strong>القاضي:</strong> {{ $request->judge->name }}</p>
                  @endif
                </div>
              </div>
              @if($request->witnesses && $request->witnesses->count() > 0)
                <h5>الشهود:</h5>
                <ul>
                  @foreach ($request->witnesses as $witness)
                    <li>{{ $witness->name }}</li>
                  @endforeach
                </ul>
              @else
                <p>لا يوجد شهود</p>
              @endif

              <h5>المرفقات:</h5>
@if($request->attachments && $request->attachments->count() > 0)
    <ul>
        @foreach ($request->attachments as $attachment)
            @php
                $filename = basename($attachment->file_path);
            @endphp
            <li><a href="{{ route('admin.download.attachment', ['filename' => $filename]) }}" target="_blank">تحميل المرفق</a></li>
        @endforeach
    </ul>
@else
    <p>لا توجد مرفقات</p>
@endif

            </div>
            <div class="card-footer">
              @if($request->status == 'pending')
                <form action="{{ route('admin.requests.approve', $request->id) }}" method="POST" style="display: inline-block;">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="btn btn-success">موافقة</button>
                </form>
                <form action="{{ route('admin.requests.reject', $request->id) }}" method="POST" style="display: inline-block;">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="btn btn-danger">رفض</button>
                </form>
              @endif
            </div>
          </div>
          <a href="{{ route('admin.requests.index') }}" class="btn btn-primary mt-3">العودة إلى الطلبات</a>
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
