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
          <li class="{{ request()->routeIs('admin.hijri') ? 'active' : '' }}">
            <a href="{{ route('admin.hijri') }}" wire:navigate>
              <div class="nav-menu__ico"><i class="fa fa-fw fa-clock-o"></i></div>
              <div class="nav-menu__text"><span>Pewaktuan Hijriah</span></div>
            </a>
          </li>
          @if(Auth::check() && Auth::user()->role === 'admin_operator')
          <li class="{{ request()->routeIs('admin.operator') ? 'active' : '' }}">
            <a href="{{ route('admin.operator') }}" wire:navigate>
              <div class="nav-menu__ico"><i class="fa fa-fw fa-users"></i></div>
              <div class="nav-menu__text"><span>Kelola Operator</span></div>
            </a>
          </li>
          @endif
          <li class="{{ request()->routeIs('api.vmix.ticker-combined') ? 'active' : '' }}">
            <a href="{{ route('api.vmix.ticker-combined') }}" target="_blank">
              <div class="nav-menu__ico"><i class="fa fa-fw fa-code"></i></div>
              <div class="nav-menu__text"><span>JSON Data</span></div>
            </a>
          </li>
        </ul>
      </div>

      <div style="padding: 15px; margin-top: 15px; border-top: 1px solid rgba(255,255,255,0.08);">
  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
    <span style="font-size: 1.1rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">
      <i class="fa fa-circle" style="color: #22c55e; font-size: 0.8rem; margin-right: 5px;"></i> Aktivitas Terbaru
    </span>
  </div>

  {{-- Box Scrollable persisten Livewire --}}
  <div id="activity-log-box" wire:persist="activity-log" style="height: 180px; overflow-y: auto; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 0px 10px 10px 10px; display: flex; flex-direction: column; overflow-y: hidden;">
    @php
      $logs = \App\Models\ActivityLog::latest()->take(10)->get()->reverse();
    @endphp
    <div style="">
    @foreach($logs as $log)
      <div class="log-item" style="font-size: 1.1rem; color: #cbd5e1; line-height: 1.4; word-break: break-word;">
        <span style="color: #64748b; font-size: 1.1rem; margin-right: 4px;">[{{ $log->created_at->format('H:i') }}]</span>
        <span>{{ $log->message }}</span>
      </div>
    @endforeach
    </div>
  </div>
</div>


    </div>
  </div>
</div>
{{-- ── End Sidebar ── --}}
