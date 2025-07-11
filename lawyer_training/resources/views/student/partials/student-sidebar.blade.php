<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-end me-3 rotate-caret  bg-gradient-dark" id="sidenav-main" style="border-radius: 5px;">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute start-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="https://demos.creative-tim.com/material-dashboard/pages/dashboard" target="_blank">
        <img src="{{ asset('admin-assets/img/logo-white.png') }}" class="navbar-brand-img h-100" alt="main_logo">
      </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse px-0 w-auto max-height-vh-100" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" href="{{ route('student.dashboard') }}">
            <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
              <i class="material-icons-round opacity-10">home</i>
            </div>
            <span class="nav-link-text me-1">الرئيسية</span>
          </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('student.requests.index') }}">
                <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons-round opacity-10">table_view</i>
                </div>
                <span class="nav-link-text me-1">الطلبات</span>
            </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('student.cases.index') }}">
              <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
                  <i class="material-icons-round opacity-10">receipt_long</i>
              </div>
              <span class="nav-link-text me-1">القضايا</span>
          </a>
      </li>
        <li class="nav-item">
          <a class="nav-link " href="#">
            <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">notifications</i>
            </div>
            <span class="nav-link-text me-1">الاشعارات</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="#">
            <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
              <i class="material-icons-round opacity-10">person</i>
            </div>
            <span class="nav-link-text me-1">اعدادات الحساب</span>
          </a>
        </li>
        <li class="nav-item">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a class="nav-link " href="javascript:void(0)" onclick="event.preventDefault(); this.closest('form').submit();">
              <div class="text-white text-center ms-2 d-flex align-items-center justify-content-center">
                <i class="material-icons-round opacity-10">logout</i>
              </div>
              <span class="nav-link-text me-1">تسجيل خروج</span>
            </a>
          </form>
        </li>

      </ul>
    </div>
  </aside>
