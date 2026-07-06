{{-- ── Sidebar ── --}}
<div class="sidebar">

  <div class="scrollable scrollbar-macosx">
    <div class="sidebar__cont">

      <div class="sidebar__menu">
        <div class="sidebar__title">Menu Utama</div>
        <ul class="nav nav-menu">
          <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" wire:navigate>
              <div class="nav-menu__ico"><i class="fa fa-fw fa-home"></i></div>
              <div class="nav-menu__text"><span>Dashboard</span></div>
            </a>
          </li>
          <li class="{{ request()->routeIs('admin.informasi') ? 'active' : '' }}">
            <a href="{{ route('admin.informasi') }}" wire:navigate>
              <div class="nav-menu__ico"><i class="fa fa-fw fa-info-circle"></i></div>
              <div class="nav-menu__text"><span>Kelola Informasi Umum</span></div>
            </a>
          </li>
          <li class="{{ request()->routeIs('admin.kajian') ? 'active' : '' }}">
            <a href="{{ route('admin.kajian') }}" wire:navigate>
              <div class="nav-menu__ico"><i class="fa fa-fw fa-calendar"></i></div>
              <div class="nav-menu__text"><span>Kelola Kajian</span></div>
            </a>
          </li>
          <li class="{{ request()->routeIs('admin.acara') ? 'active' : '' }}">
            <a href="{{ route('admin.acara') }}" wire:navigate>
              <div class="nav-menu__ico"><i class="fa fa-fw fa-play"></i></div>
              <div class="nav-menu__text"><span>Kelola Acara</span></div>
            </a>
          </li>
          <li class="{{ request()->routeIs('admin.narasumber') ? 'active' : '' }}">
            <a href="{{ route('admin.narasumber') }}" wire:navigate>
              <div class="nav-menu__ico"><i class="fa fa-fw fa-user"></i></div>
              <div class="nav-menu__text"><span>Kelola Narasumber</span></div>
            </a>
          </li>
          <li class="{{ request()->routeIs('admin.tempat') ? 'active' : '' }}">
            <a href="{{ route('admin.tempat') }}" wire:navigate>
              <div class="nav-menu__ico"><i class="fa fa-fw fa-map-marker"></i></div>
              <div class="nav-menu__text"><span>Kelola Tempat</span></div>
            </a>
          </li>
          <li class="{{ request()->routeIs('admin.kontak') ? 'active' : '' }}">
            <a href="{{ route('admin.kontak') }}" wire:navigate>
              <div class="nav-menu__ico"><i class="fa fa-fw fa-phone"></i></div>
              <div class="nav-menu__text"><span>Kelola Kontak</span></div>
            </a>
          </li>
          <li class="{{ request()->routeIs('admin.logo') ? 'active' : '' }}">
            <a href="{{ route('admin.logo') }}" wire:navigate>
              <div class="nav-menu__ico"><i class="fa fa-fw fa-image"></i></div>
              <div class="nav-menu__text"><span>Logo</span></div>
            </a>
          </li>
          <li class="{{ request()->routeIs('admin.hijri') ? 'active' : '' }}">
            <a href="{{ route('admin.hijri') }}" wire:navigate>
              <div class="nav-menu__ico"><i class="fa fa-fw fa-clock-o"></i></div>
              <div class="nav-menu__text"><span>Pewaktuan Hijriah</span></div>
            </a>
          </li>
          <li>
          </li>
        </ul>
      </div>

    </div>
  </div>
</div>
{{-- ── End Sidebar ── --}}
