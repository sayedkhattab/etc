@include('student.partials.student-head')

<body class="g-sidenav-show rtl bg-gray-200">
  @include('student.partials.student-sidebar')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg overflow-x-hidden">
    @include('student.partials.student-navbar')
    <div class="container mt-5">
      <div class="card">
        <div class="card-header bg-gradient-primary text-white">
          <h4 class="mb-0">{{ $request->title }}</h4>
        </div>
        <div class="card-body">
          <p><strong>الوصف:</strong> {{ $request->description }}</p>
          <p><strong>نوع القضية:</strong> {{ $request->case_type }}</p> <!-- عرض نوع القضية -->
          <p><strong>صفة المدعي:</strong> {{ $request->plaintiff_role }}</p> <!-- عرض صفة المدعي -->
          <p><strong>تاريخ الإرسال:</strong> {{ $request->created_at->format('Y-m-d') }}</p>
          <p><strong>الحالة:</strong> {{ $request->status }}</p>
          <h5>المرفقات:</h5>
          <ul>
            @if ($request->attachments->isNotEmpty())
              @foreach ($request->attachments as $attachment)
                <li><a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank">عرض المرفق</a></li>
              @endforeach
            @else
              <li>لا توجد مرفقات</li>
            @endif
          </ul>
          <h5>تفاصيل القضية:</h5>
          <p><strong>القاضي:</strong> {{ $request->judge ? $request->judge->name : 'لم يتم التعيين بعد' }}</p>
          <p><strong>المدعي عليه:</strong> {{ $request->defendant ? $request->defendant->name : 'لم يتم التعيين بعد' }}</p>
          <h5>الشهود:</h5>
          <ul>
            @if ($request->witnesses->isNotEmpty())
              @foreach ($request->witnesses as $witness)
                <li>{{ $witness->name }}</li>
              @endforeach
            @else
              <li>لم يتم تعيين شهود بعد</li>
            @endif
          </ul>
        </div>
        <div class="card-footer bg-gray-100 text-end">
          <a href="{{ route('student.requests.index') }}" class="btn btn-primary">العودة إلى الطلبات</a>
        </div>
      </div>
      @include('student.partials.student-footer')
    </div>
  </main>
  <script src="{{ asset('admin-assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('admin-assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('admin-assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('admin-assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script src="{{ asset('admin-assets/js/material-dashboard.min.js?v=3.0.0') }}"></script>
</body>
</html>
