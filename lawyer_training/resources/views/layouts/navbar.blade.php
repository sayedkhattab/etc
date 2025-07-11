<nav>
  <ul>
    <!-- روابط الزائر -->
    <li><a href="{{ url('/') }}">Home</a></li>
    <li><a href="{{ url('/projects') }}">Projects</a></li>
    @guest
      <li><a href="{{ url('/login') }}">Login</a></li>
      <li><a href="{{ url('/register') }}">Register</a></li>
      <li><a href="{{ url('/register/developer') }}">Register as Developer</a></li>
      <li><a href="{{ url('/register/investor') }}">Register as Investor</a></li>
    @else
      @if(Auth::user()->role == 'developer')
        <!-- روابط المطور -->
        <li><a href="{{ url('/developer/dashboard') }}">Dashboard</a></li>
        <li><a href="{{ url('/developer/projects') }}">My Projects</a></li>
      @elseif(Auth::user()->role == 'investor')
        <!-- روابط المستثمر -->
        <li><a href="{{ url('/investor/dashboard') }}">Dashboard</a></li>
        <li><a href="{{ url('/investor/investments') }}">My Investments</a></li>
      @endif
      <li><a href="{{ url('/logout') }}">Logout</a></li>
    @endguest
  </ul>
</nav>
