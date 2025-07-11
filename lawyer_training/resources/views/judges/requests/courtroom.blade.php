@include('judges.partials.judge-head')

<div class="container mt-5">
    <h1>قاعة الجلسة</h1>
    <div class="card">
        <div class="card-body">
            <h2>{{ $request->title }}</h2>
            <p><strong>تاريخ الجلسة:</strong> {{ $request->schedule_date }}</p>
            <p><strong>وقت الجلسة:</strong> {{ $request->schedule_time }}</p>
            <a href="#" class="btn btn-primary">رابط الجلسة (Zoom)</a> <!-- يمكنك تعديل هذا الرابط -->
        </div>
    </div>
    <a href="{{ route('judge.requests.index') }}" class="btn btn-primary mt-3">العودة إلى الطلبات</a>
</div>
