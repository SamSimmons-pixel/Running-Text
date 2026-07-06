<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard — Jadwal Kajian</title>
    <meta name="description" content="Panel admin untuk mengelola jadwal kajian.">
    <link rel="icon" type="image/png" href="{{ asset('admin-template/img/favicon.png') }}">
    @include('partials.assets')

    <style>
      :root {
        --accent:   #6d28d9;
        --accent-h: #7c3aed;
        --green:    #10b981;
        --red:      #ef4444;
        --red-h:    #dc2626;
      }

      /* ── Stat cards ── */
      .stat-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
      }
      .stat-card {
        flex: 1 1 160px;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: border-color 0.15s;
      }
      .stat-card:hover { border-color: var(--accent); }
      .stat-card__icon {
        width: 44px; height: 44px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
      }
      .stat-card__value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        color: #e2e8f0;
      }
      .stat-card__label {
        font-size: 1rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
      }

      /* ── Upcoming table ── */
      .upcoming-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 99px;
        font-size: 1.1rem;
        font-weight: 600;
      }
      .badge-on  { background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.3); }
      .badge-off { background: rgba(148,163,184,0.1);  color: #64748b;  border: 1px solid rgba(148,163,184,0.2); }

      .empty-state {
        padding: 3rem;
        text-align: center;
        color: #64748b;
        font-size: 0.9rem;
      }

      .section-count {
        font-size: 0.78rem;
        color: #64748b;
      }
    </style>
    @livewireStyles
  </head>
  <body class="framed main-scrollable">
    <div class="wrapper">

      {{-- ── Top Navbar ── --}}
      <nav class="navbar navbar-static-top header-navbar">
        {{-- Mobile header --}}
        <div class="header-navbar-mobile">
          <div class="header-navbar-mobile__menu">
            <button class="btn" type="button"><i class="fa fa-bars"></i></button>
          </div>
          <div class="header-navbar-mobile__title"><span>Jadwal Kajian</span></div>
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
          <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
            <div class="logo text-nowrap">
              <div class="logo__img"><i class="fa fa-book"></i></div>
              <span class="logo__text">Jadwal Kajian</span>
            </div>
          </a>
        </div>

        {{-- Desktop nav --}}
        <div class="topnavbar">
          <ul class="nav navbar-nav navbar-left">
            <li class="active"><a href="{{ route('admin.dashboard') }}"><span>Dashboard</span></a></li>
            <li>
              <a href="{{ url('/') }}" target="_blank">
                <span><i class="fa fa-external-link" style="font-size:0.75em;"></i> MainPage</span>
              </a>
            </li>
          </ul>
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

      {{-- ── Dashboard Wrapper ── --}}
      <div class="dashboard">

        @include('partials.sidebar')
        {{-- ── End Sidebar ── --}}

        {{-- ── Main Content ── --}}
        <div class="main">
          <div class="main__scroll scrollbar-macosx">
            <div class="main__cont">

              {{-- Page Heading --}}
              <div class="main-heading">
                <div class="main-title">
                  <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                    <li class="active">Dashboard</li>
                  </ol>
                </div>
              </div>

              <div class="container-fluid half-padding">

                {{-- ── Stat Cards ── --}}
                @php
                  $totalKajian      = $kajian->count();
                  $activeKajian     = $kajian->where('Tampilkan', 1)->count();
                  $hiddenKajian     = $totalKajian - $activeKajian;
                  $upcoming         = $kajian->where('Tanggal', '>=', now())->sortBy('Tanggal')->take(5);
                  $pastActiveKajian = $kajian->filter(fn($item) => $item->Tampilkan && \Carbon\Carbon::parse($item->Tanggal)->isPast())->count();
                @endphp

                <div class="stat-row">
                  <div class="stat-card">
                    <div class="stat-card__icon" style="background:rgba(109,40,217,0.15); color:#a78bfa;">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <div>
                      <div class="stat-card__value">{{ $totalKajian }}</div>
                      <div class="stat-card__label">Total Kajian</div>
                    </div>
                  </div>
                  <div class="stat-card">
                    <div class="stat-card__icon" style="background:rgba(16,185,129,0.15); color:#34d399;">
                      <i class="fa fa-eye"></i>
                    </div>
                    <div>
                      <div class="stat-card__value" style="color:#34d399;">{{ $activeKajian }}</div>
                      <div class="stat-card__label">Ditampilkan</div>
                    </div>
                  </div>
                  <div class="stat-card">
                    <div class="stat-card__icon" style="background:rgba(148,163,184,0.1); color:#64748b;">
                      <i class="fa fa-eye-slash"></i>
                    </div>
                    <div>
                      <div class="stat-card__value" style="color:#64748b;">{{ $hiddenKajian }}</div>
                      <div class="stat-card__label">Disembunyikan</div>
                    </div>
                  </div>
                  <div class="stat-card">
                    <div class="stat-card__icon" style="background:rgba(251,191,36,0.12); color:#fbbf24;">
                      <i class="fa fa-user"></i>
                    </div>
                    <div>
                      <div class="stat-card__value" style="color:#fbbf24;">{{ $narasumberList->count() }}</div>
                      <div class="stat-card__label">Narasumber</div>
                    </div>
                  </div>
                  <div class="stat-card">
                    <div class="stat-card__icon" style="background:rgba(96,165,250,0.12); color:#60a5fa;">
                      <i class="fa fa-map-marker"></i>
                    </div>
                    <div>
                      <div class="stat-card__value" style="color:#60a5fa;">{{ $tempatList->count() }}</div>
                      <div class="stat-card__label">Lokasi</div>
                    </div>
                  </div>
                  <div class="stat-card">
                    <div class="stat-card__icon" style="background:rgba(251,113,133,0.12); color:#fb7185;">
                      <i class="fa fa-phone"></i>
                    </div>
                    <div>
                      <div class="stat-card__value" style="color:#fb7185;">{{ $kontakList->count() }}</div>
                      <div class="stat-card__label">Kontak</div>
                    </div>
                  </div>
                  <div class="stat-card">
                    <div class="stat-card__icon" style="background:rgba(239,68,68,0.12); color:#fca5a5;">
                      <i class="fa fa-calendar-times-o"></i>
                    </div>
                    <div>
                      <div class="stat-card__value" style="color:#fca5a5;">{{ $pastActiveKajian }}</div>
                      <div class="stat-card__label">Kajian terlewat yang di tampilkan</div>
                    </div>
                  </div>
                </div>

                
                {{-- ── Upcoming Kajian ── --}}
                <div class="panel panel-default">
                  <div class="panel-heading" style="display:flex; align-items:center; justify-content:space-between;">
                    <h3 class="panel-title"><i class="fa fa-clock-o"></i> Kajian Mendatang</h3>
                    <a href="{{ route('admin.kajian') }}" class="btn btn-xs btn-default" wire:navigate>
                      <i class="fa fa-list"></i> Lihat Semua
                    </a>
                  </div>
                  <div class="panel-body" style="padding:0;">
                    @if ($upcoming->isEmpty())
                      <div class="empty-state">
                        <i class="fa fa-calendar-check-o" style="font-size:2rem; margin-bottom:0.75rem; display:block; opacity:0.3;"></i>
                        Tidak ada kajian mendatang.
                      </div>
                    @else
                      <div class="table-responsive">
                        <table class="table table-hover" style="margin-bottom:0;">
                          <thead>
                            <tr>
                              <th>Judul &amp; Narasumber</th>
                              <th>Tanggal</th>
                              <th>Tempat</th>
                              <th style="text-align:center;">Tampilkan Json</th>
                              <th style="text-align:center;">Status</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($upcoming as $item)
                            <tr>
                              <td>
                                <strong>{{ $item->Judul }}</strong>
                                <small style="display:block; color:#D0DDF2; font-size:1.1rem; margin-top:2px;">{{ $item->Narasumber }}</small>
                              </td>
                              <td style="white-space:nowrap; color:#94a3b8;">
                                {{ \Carbon\Carbon::parse($item->Tanggal)->locale('id')->isoFormat('ddd, D MMM Y') }}<br>
                                <small>{{ \Carbon\Carbon::parse($item->Tanggal)->format('H:i') }}</small>
                              </td>
                              <td style="color:#94a3b8;">{{ $item->Tempat }}</td>
                              <td style="text-align:center;">
                                @if ($item->Tampilkan)
                                  <span class="upcoming-badge badge-on">Tampil</span>
                                @else
                                  <span class="upcoming-badge badge-off">Tersembunyi</span>
                                @endif
                              </td>
                              <td style="text-align:center;">
                                @if(\Carbon\Carbon::parse($item->Tanggal)->isPast())
                                  <span class="label label-danger" style="border-radius: 99px; padding: 3px 8px; font-size: 1.25rem; background-color: rgba(239, 68, 68, 0.15) !important; color: #fb7185 !important; border: 1px solid rgba(239, 68, 68, 0.3) !important;">terlewat</span>
                                @else
                                  <span class="label label-success" style="border-radius: 99px; padding: 3px 8px; font-size: 1.25rem; background-color: rgba(34, 197, 94, 0.15) !important; color: #34d399 !important; border: 1px solid rgba(34, 197, 94, 0.3) !important;">terjadwal</span>
                                @endif
                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    @endif
                  </div>
                </div>

                {{-- ── Quick Links ── --}}
                <div class="row" style="margin-top:1rem;">
                  <div class="col-sm-6 col-md-2" style="margin-bottom:1rem;">
                    <a href="{{ route('admin.informasi') }}" class="btn btn-block btn-default" wire:navigate>
                      <i class="fa fa-info-circle"></i> Informasi Umum
                    </a>
                  </div>
                  <div class="col-sm-6 col-md-2" style="margin-bottom:1rem;">
                    <a href="{{ route('admin.kajian') }}" class="btn btn-block btn-default" wire:navigate>
                      <i class="fa fa-calendar"></i> Kelola Kajian
                    </a>
                  </div>
                  <div class="col-sm-6 col-md-2" style="margin-bottom:1rem;">
                    <a href="{{ route('admin.narasumber') }}" class="btn btn-block btn-default" wire:navigate>
                      <i class="fa fa-user"></i> Kelola Narasumber
                    </a>
                  </div>
                  <div class="col-sm-6 col-md-2" style="margin-bottom:1rem;">
                    <a href="{{ route('admin.tempat') }}" class="btn btn-block btn-default" wire:navigate>
                      <i class="fa fa-map-marker"></i> Kelola Tempat
                    </a>
                  </div>
                  <div class="col-sm-6 col-md-2" style="margin-bottom:1rem;">
                    <a href="{{ route('admin.kontak') }}" class="btn btn-block btn-default" wire:navigate>
                      <i class="fa fa-phone"></i> Kelola Kontak
                    </a>
                  </div>
                  <div class="col-sm-6 col-md-2" style="margin-bottom:1rem;">
                    <a href="{{ route('admin.acara') }}" class="btn btn-block btn-default" wire:navigate>
                      <i class="fa fa-flag"></i> Kelola Acara
                    </a>
                  </div>
                </div>

              </div>{{-- end container-fluid --}}
            </div>{{-- end main__cont --}}
          </div>{{-- end main__scroll --}}
        </div>{{-- end main --}}

      </div>{{-- end dashboard --}}
    </div>{{-- end wrapper --}}

    <script src="{{ asset('admin-template/js/main.js') }}"></script>
    @livewireScripts
    <script>
      document.addEventListener('livewire:navigated', function() {
        if (window.jQuery) {
          var $ = window.jQuery;
          $('body.main-scrollable .main__scroll').scrollbar();
          $('.scrollable').scrollbar({'disableBodyScroll' : true});
          
          $('.header-navbar-mobile__menu button').off('click').on('click', function() {
            $('.dashboard').toggleClass('dashboard_menu');
          });
        }
      });
    </script>
  </body>
</html>
