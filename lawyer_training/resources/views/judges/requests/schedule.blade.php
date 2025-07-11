@include('judges.partials.judge-head')

<body class="g-sidenav-show rtl bg-gray-200">
    @include('judges.partials.judge-sidebar')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg overflow-x-hidden">
        @include('judges.partials.judge-navbar')
        <div class="container-fluid py-4">
            <div class="container mt-5">
                <h1>تحديد موعد الجلسة</h1>
                <div class="card">
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

                        <form id="schedule-form" action="{{ route('judge.requests.storeSchedule', $request->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="date" class="block text-gray-700 dark:text-gray-400 text-sm font-bold mb-2">تاريخ الجلسة</label>
                                <input type="date" id="date" name="date" class="form-control" required>
                            </div>
                            <div class="mb-4">
                                <label for="time" class="block text-gray-700 dark:text-gray-400 text-sm font-bold mb-2">وقت الجلسة</label>
                                <div class="d-flex">
                                    <select id="hour" name="hour" class="form-select mx-2" required>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <select id="minute" name="minute" class="form-select mx-2" required>
                                        @for ($i = 0; $i < 60; $i += 5)
                                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                        @endfor
                                    </select>
                                    <select id="ampm" name="ampm" class="form-select mx-2" required>
                                        <option value="AM">صباحًا</option>
                                        <option value="PM">مساءً</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">تحديد الموعد</button>
                        </form>
                    </div>
                </div>
                <a href="{{ route('judge.requests.index') }}" class="btn btn-primary mt-3">العودة إلى الطلبات</a>
            </div>
            @include('judges.partials.judge-footer')
        </div>
    </main>
    <script src="{{ asset('admin-assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/material-dashboard.min.js?v=3.0.0') }}"></script>
</body>
</html>
