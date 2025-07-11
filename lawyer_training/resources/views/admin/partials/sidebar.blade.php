<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-end me-3 rotate-caret bg-gradient-dark" id="sidenav-main" style="border-radius: 5px !important;">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute start-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="navbar-brand m-0" href="/admin/dashboard">
      <img src="{{ asset('admin-assets/img/logo-white.png') }}" class="navbar-brand-img h-100" alt="main_logo">
    </a>
  </div>
  <hr class="horizontal light mt-0 mb-2">
  <div class="collapse navbar-collapse px-0 w-auto max-height-vh-100" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link active" href="{{ route('admin.dashboard') }}">
          <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
            <i class="material-icons-round opacity-10">home</i>
          </div>
          <span class="nav-link-text me-1">الرئيسية</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.students.index') }}">
          <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
            <i class="material-icons-round opacity-10">table_view</i>
          </div>
          <span class="nav-link-text me-1">جميع الطلاب</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.students.pending') }}">
          <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
            <i class="material-icons-round opacity-10">table_view</i>
          </div>
          <span class="nav-link-text me-1">طلاب مُعلقين</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.judges.index') }}">
          <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
            <i class="material-icons-round opacity-10">gavel</i>
          </div>
          <span class="nav-link-text me-1">جميع القضاة</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.requests.index') }}">
          <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
            <i class="material-icons-round opacity-10">view_in_ar</i>
          </div>
          <span class="nav-link-text me-1">جميع الطلبات</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.employees.index') }}">
          <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
            <i class="material-icons-round opacity-10">person</i>
          </div>
          <span class="nav-link-text me-1">جميع الموظفين</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.cases.index') }}">
          <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
            <i class="material-icons-round opacity-10">gavel</i>
          </div>
          <span class="nav-link-text me-1">جميع القضايا</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.case-archives.index') }}">
          <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
            <i class="material-icons-round opacity-10">book</i>
          </div>
          <span class="nav-link-text me-1">الأرشيف</span>
        </a>
      </li>




      <li class="nav-item">
        <a class="nav-link" href="#">
          <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">notifications</i>
          </div>
          <span class="nav-link-text me-1">شاهد الاشعارات</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
            <i class="material-icons-round opacity-10">assignment</i>
          </div>
          <span class="nav-link-text me-1">الإعدادات</span>
        </a>
      </li>
      <li class="nav-item">
          <a class="nav-link" href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
              <i class="material-icons-round opacity-10">logout</i>
            </div>
            <span class="nav-link-text me-1">تسجيل خروج</span>
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
      </li>



    </ul>
  </div>
</aside>
