{{-- ── Top Navbar ── --}}
<nav class="navbar navbar-static-top header-navbar">
  {{-- Mobile header --}}
  <div class="header-navbar-mobile">
    <div class="header-navbar-mobile__menu">
      <button class="btn" type="button"><i class="fa fa-bars"></i></button>
    </div>
    <div class="header-navbar-mobile__title"><span>Admin Panel</span></div>
    <div class="header-navbar-mobile__settings dropdown">
      <a class="btn dropdown-toggle" href="" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-power-off"></i>
      </a>
      <ul class="dropdown-menu dropdown-menu-right">
        <li>
          <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
            <i class="fa fa-sign-out"></i> Logout
          </a>
          <form id="logout-form-mobile" method="POST" action="{{ route('logout') }}" style="display:none;">@csrf</form>
        </li>
      </ul>
    </div>
  </div>

  {{-- Brand --}}
  <div class="navbar-header">
    <a class="navbar-brand" href="{{ route('admin.dashboard') }}" wire:navigate>
      <div class="logo text-nowrap">
        <div class="logo__img"><i class="fa fa-book"></i></div>
        <span class="logo__text">Admin Panel</span>
      </div>
    </a>
  </div>

  {{-- Desktop nav --}}
  <div class="topnavbar">
    <ul class="nav navbar-nav navbar-left">
      <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a href="{{ route('admin.dashboard') }}" wire:navigate><span>Dashboard</span></a>
      </li>
      {{-- <li>
        <a href="{{ url('/mainpage') }}" target="_blank">
          <span><i class="fa fa-external-link" style="font-size:0.75em;"></i> MainPage</span>
        </a>
      </li> --}}
    </ul>
    
    @persist('notification')
    {{-- Kontainer Notifikasi Persisten untuk Livewire 3 --}}
    <div id="toast-container" wire:persist="notifications"></div>
    @endpersist

    <ul class="userbar nav navbar-nav">
      <li class="dropdown">
        <a class="userbar__settings dropdown-toggle" href="" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-power-off"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-right">
          <li>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-top').submit();">
              <i class="fa fa-sign-out"></i> Logout
            </a>
            <form id="logout-form-top" method="POST" action="{{ route('logout') }}" style="display:none;">@csrf</form>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
