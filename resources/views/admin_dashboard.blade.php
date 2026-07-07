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

      @include('partials.navbar')

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
                            @php
                              $kajianStart  = \Carbon\Carbon::parse($item->Tanggal, 'Asia/Jakarta');
                              $kajianEnd    = $kajianStart->copy()->addHour();
                              $kajianOnAir  = now('Asia/Jakarta')->between($kajianStart, $kajianEnd);
                            @endphp
                            <tr class="{{ $kajianOnAir ? 'row-on-air' : '' }}">
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
                                @if($kajianOnAir)
                                  <span class="badge-live">LIVE</span>
                                @elseif(\Carbon\Carbon::parse($item->Tanggal)->isPast())
                                  <span class="label label-danger" style="border-radius: 99px; padding: 3px 8px; font-size: 1.25rem; background-color: rgba(239, 68, 68, 0.15) !important; color: #fb7185 !important; border: 1px solid rgba(239, 68, 68, 0.3) !important;">terlewat</span>
                                @else
                                  <span class="label label-success" style="border-radius: 99px; padding: 3px 8px; font-size: 1.25rem; background-color: rgba(255, 240, 58, 0.15) !important; color: #efc424ff !important; border: 1px solid rgba(255, 231, 38, 0.3) !important;">terjadwal</span>
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

                {{-- ── Acara Mendatang ── --}}
                <div class="panel panel-default" style="margin-top:1.25rem;">
                  <div class="panel-heading" style="display:flex; align-items:center; justify-content:space-between;">
                    <h3 class="panel-title"><i class="fa fa-play"></i> Acara Mendatang</h3>
                    <a href="{{ route('admin.acara') }}" class="btn btn-xs btn-default" wire:navigate>
                      <i class="fa fa-list"></i> Lihat Semua
                    </a>
                  </div>
                  <div class="panel-body" style="padding:0;">
                    @if ($acaraList->isEmpty())
                      <div class="empty-state">
                        <i class="fa fa-play-circle-o" style="font-size:2rem; margin-bottom:0.75rem; display:block; opacity:0.3;"></i>
                        Tidak ada acara terdaftar.
                      </div>
                    @else
                      @php
                        $nowJkt  = now('Asia/Jakarta');
                        $hariIndo = [
                          'Sunday'    => 'Ahad',
                          'Monday'    => 'Senin',
                          'Tuesday'   => 'Selasa',
                          'Wednesday' => 'Rabu',
                          'Thursday'  => 'Kamis',
                          'Friday'    => 'Jumat',
                          'Saturday'  => 'Sabtu',
                        ];
                        $hariIni = $hariIndo[$nowJkt->format('l')];
                        $jamNow  = $nowJkt->format('H:i:s');
                      @endphp
                      <div class="table-responsive">
                        <table class="table table-hover" style="margin-bottom:0;">
                          <thead>
                            <tr>
                              <th>Judul & Narasumber</th>
                              <th>Hari</th>
                              <th>Jam</th>
                              <th>Tempat</th>
                              <th style="text-align:center;">Status</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($acaraList as $acItem)
                            @php
                              $acOnAir = ($acItem->hari === $hariIni)
                                      && ($jamNow >= $acItem->jam_mulai)
                                      && ($jamNow <= $acItem->jam_selesai);
                            @endphp
                            <tr class="{{ $acOnAir ? 'row-on-air' : '' }}">
                              <td>
                                <strong>{{ $acItem->judul }}</strong>
                                <small style="display:block; color:#D0DDF2; font-size:1.1rem; margin-top:2px;">{{ $acItem->narasumber }}</small>
                              </td>
                              <td style="color:#94a3b8; white-space:nowrap;">{{ $acItem->hari }}</td>
                              <td style="color:#94a3b8; white-space:nowrap;">
                                {{ \Carbon\Carbon::parse($acItem->jam_mulai)->format('H:i') }}
                                <span style="opacity:0.5;">–</span>
                                {{ \Carbon\Carbon::parse($acItem->jam_selesai)->format('H:i') }}
                              </td>
                              <td style="color:#94a3b8;">{{ $acItem->tempat }}</td>
                              <td style="text-align:center; vertical-align:middle;">
                                @if($acOnAir)
                                  <span class="badge-live">LIVE</span>
                                @elseif($acItem->status)
                                  <span class="label label-info" style="border-radius:99px; padding:3px 8px; font-size:1.1rem; background-color:rgba(99,179,237,0.15)!important; color:#63b3ed!important; border:1px solid rgba(99,179,237,0.3)!important;">
                                    {{ $acItem->status }}
                                  </span>
                                @else
                                  <span style="color:#475569; font-size:0.85rem;">—</span>
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
                      <i class="fa fa-play"></i> Kelola Acara
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
