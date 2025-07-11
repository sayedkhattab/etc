<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true" style="margin-top: 15px !important; border-radius: 5px; margin-bottom: 15px !important; background-color: #597445 !important;">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb"></nav>
    <div class="collapse navbar-collapse mt-sm-0 mt-2 px-0" id="navbar">
      <div class="ms-md-auto pe-md-3 d-flex align-items-center">
        <div class="input-group input-group-outline">
          <h4 style="color: white; margin: 10px;">لوحة تحكم الطالب : <h6 style="color: white; margin-top: 15px;">{{ Auth::user()->name }}</h6></h4>
        </div>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" style="color: white;">
          {{ __('تسجيل خروج') }}
        </x-dropdown-link>
      </form>
    </div>
  </div>
</nav>
