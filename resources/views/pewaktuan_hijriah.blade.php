<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pewaktuan Hijriah — Panel Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('admin-template/img/favicon.png') }}">
    @include('partials.assets')
    @livewireStyles

    <style>
      .ticker-preview-card {
        background: linear-gradient(135deg, #1e1b4b 0%, #311042 100%);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
      }
      .ticker-preview-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(109, 40, 217, 0.08) 0%, transparent 60%);
        pointer-events: none;
      }
      .ticker-title {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #a78bfa;
        margin-bottom: 16px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
      }
      .ticker-flat-display {
        background: #0f0c1b;
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        padding: 16px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
      }
      .ticker-segment {
        display: flex;
        flex-direction: column;
        justify-content: center;
      }
      .ticker-segment-label {
        font-size: 0.72rem;
        color: #64748b;
        text-transform: uppercase;
        font-weight: 500;
      }
      .ticker-segment-value {
        font-size: 1.05rem;
        color: #f1f5f9;
        font-weight: 600;
      }
      .ticker-segment-value.highlight {
        color: #38bdf8;
      }
      .ticker-prayer-grid {
        display: flex;
        gap: 12px;
        flex-grow: 1;
        justify-content: flex-end;
      }
      .ticker-prayer-item {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 6px;
        padding: 8px 14px;
        text-align: center;
        min-width: 75px;
      }
      .ticker-prayer-name {
        font-size: 0.68rem;
        color: #94a3b8;
        text-transform: uppercase;
      }
      .ticker-prayer-time {
        font-size: 0.98rem;
        color: #f8fafc;
        font-weight: 700;
      }
      .vmix-guide-card {
        background: #111827;
        border-left: 4px solid #6d28d9;
        padding: 16px;
        border-radius: 4px;
        margin-bottom: 24px;
      }
      .vmix-endpoint-url {
        font-family: monospace;
        background: #030712;
        padding: 8px 12px;
        border-radius: 4px;
        color: #10b981;
        word-break: break-all;
        margin-top: 8px;
        display: block;
      }
      .btn-copy {
        background: #374151;
        border: none;
        color: #fff;
        padding: 2px 8px;
        font-size: 0.75rem;
        border-radius: 4px;
        margin-left: 8px;
        cursor: pointer;
      }
      .btn-copy:hover {
        background: #4b5563;
      }
    </style>
  </head>
  <body class="framed main-scrollable">
    <div class="wrapper">

      {{-- ── Top Navbar ── --}}
      <nav class="navbar navbar-static-top header-navbar">
        <div class="header-navbar-mobile">
          <div class="header-navbar-mobile__menu">
            <button class="btn" type="button"><i class="fa fa-bars"></i></button>
          </div>
          <div class="header-navbar-mobile__title"><span>Pewaktuan Hijriah</span></div>
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

        <div class="navbar-header">
          <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
            <div class="logo text-nowrap">
              <div class="logo__img"><i class="fa fa-book"></i></div>
              <span class="logo__text">Jadwal Kajian</span>
            </div>
          </a>
        </div>

        <div class="topnavbar">
          <ul class="nav navbar-nav navbar-left">
            <li><a href="{{ route('admin.dashboard') }}"><span>Dashboard</span></a></li>
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

        {{-- ── Main Content ── --}}
        <div class="main">
          <div class="main__scroll scrollbar-macosx">
            <div class="main__cont">

              <div class="main-heading">
                <div class="main-title">
                  <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                    <li class="active">Pewaktuan Hijriah</li>
                  </ol>
                </div>
              </div>

              <div class="container-fluid half-padding">

                {{-- Alerts --}}
                @if (session('success'))
                  <div class="alert-kajian success" style="margin-bottom: 20px;">
                    <i class="fa fa-check-circle"></i>
                    {{ session('success') }}
                  </div>
                @endif
                @if (session('error'))
                  <div class="alert-kajian error" style="margin-bottom: 20px;">
                    <i class="fa fa-exclamation-circle"></i>
                    {{ session('error') }}
                  </div>
                @endif

                {{-- Live Ticker Preview Card --}}
                <div class="ticker-preview-card">
                  <div class="ticker-title">
                    <i class="fa fa-eye"></i> Live Preview Ticker (Masehi & Hijriah)
                    @if($preview['is_fallback'] ?? false)
                      <span class="label label-warning" style="margin-left: 8px;">Fallback Mode (API Offline)</span>
                    @else
                      <span class="label label-success" style="margin-left: 8px;">Active (Cached)</span>
                    @endif
                  </div>

                  <div class="ticker-flat-display">
                    <div class="ticker-segment" style="min-width: 140px;">
                      <span class="ticker-segment-label">Masehi</span>
                      <span class="ticker-segment-value">{{ $preview['tanggal_masehi'] }}</span>
                    </div>

                    <div class="ticker-segment" style="min-width: 160px; border-left: 1px solid rgba(255,255,255,0.08); padding-left: 15px;">
                      <span class="ticker-segment-label">Hijriah</span>
                      <span class="ticker-segment-value highlight">{{ $preview['tanggal_hijriah'] }}</span>
                    </div>

                    <div class="ticker-segment" style="min-width: 120px; border-left: 1px solid rgba(255,255,255,0.08); padding-left: 15px;">
                      <span class="ticker-segment-label">Wilayah (Provider)</span>
                      <span class="ticker-segment-value" style="font-size: 0.95rem; color: #94a3b8;">
                        {{ $preview['city'] }} ({{ strtoupper($preview['provider']) }})
                      </span>
                    </div>

                    <div class="ticker-prayer-grid">
                      <div class="ticker-prayer-item">
                        <div class="ticker-prayer-name">Subuh</div>
                        <div class="ticker-prayer-time">{{ $preview['subuh'] }}</div>
                      </div>
                      <div class="ticker-prayer-item">
                        <div class="ticker-prayer-name">Dzuhur</div>
                        <div class="ticker-prayer-time">{{ $preview['dzuhur'] }}</div>
                      </div>
                      <div class="ticker-prayer-item">
                        <div class="ticker-prayer-name">Ashar</div>
                        <div class="ticker-prayer-time">{{ $preview['ashar'] }}</div>
                      </div>
                      <div class="ticker-prayer-item">
                        <div class="ticker-prayer-name">Maghrib</div>
                        <div class="ticker-prayer-time" style="color: #f43f5e;">{{ $preview['maghrib'] }}</div>
                      </div>
                      <div class="ticker-prayer-item">
                        <div class="ticker-prayer-name">Isya</div>
                        <div class="ticker-prayer-time">{{ $preview['isya'] }}</div>
                      </div>
                    </div>
                  </div>
                </div>

                {{-- vMix Broadcast Guide Card --}}
                <div class="vmix-guide-card">
                  <div style="font-weight: 700; color: #fff; display: flex; align-items: center; gap: 8px;">
                    <i class="fa fa-television" style="color: #a78bfa;"></i> Integrasi vMix Data Source
                  </div>
                  <div style="font-size: 0.85rem; color: #9ca3af; margin-top: 6px;">
                    Gunakan URL endpoint JSON flat di bawah ini sebagai input <strong>JSON Data Source</strong> di software vMix Anda.
                    <span class="vmix-endpoint-url" id="endpointUrl">
                      {{ url('/api/vmix/hijri-ticker') }}?timezone=WIB
                      <button class="btn-copy" onclick="copyEndpoint()">Copy Link</button>
                    </span>
                  </div>
                </div>

                {{-- Settings Form Panel --}}
                <div class="panel panel-default panel-custom">
                  <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-cog"></i> Pengaturan Pewaktuan Hijriah & Jadwal Sholat</h3>
                  </div>
                  <div class="panel-body">
                    <form method="POST" action="{{ route('admin.hijri.update') }}">
                      @csrf

                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="control-label" for="default_city">Pilih Wilayah / Kota Representatif</label>
                            <select id="default_city" name="default_city" class="form-control form-control-custom" required>
                              @foreach($cities as $key => $cityInfo)
                                <option value="{{ $key }}" {{ $settings->default_city === $key ? 'selected' : '' }}>
                                  {{ $cityInfo['name'] }} ({{ $key === 'Jayapura' || $key === 'Ambon' ? 'WIT' : ($key === 'Makassar' || $key === 'Banjarmasin' ? 'WITA' : 'WIB') }})
                                </option>
                              @endforeach
                            </select>
                            <span class="help-block" style="font-size: 0.8rem; color: #64748b;">
                              Menentukan jadwal sholat dan zona waktu lokal yang digunakan.
                            </span>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="control-label" for="prayer_time_provider">API Provider Jadwal Sholat</label>
                            <select id="prayer_time_provider" name="prayer_time_provider" class="form-control form-control-custom" required>
                              <option value="aladhan" {{ $settings->prayer_time_provider === 'aladhan' ? 'selected' : '' }}>
                                Aladhan API (Kemenag RI Method - International & Stabil)
                              </option>
                              <option value="myquran" {{ $settings->prayer_time_provider === 'myquran' ? 'selected' : '' }}>
                                MyQuran API (Standard Kemenag Indonesia - Lokal)
                              </option>
                            </select>
                            <span class="help-block" style="font-size: 0.8rem; color: #64748b;">
                              Metode lookup API utama. Sistem akan otomatis fallback ke provider cadangan jika provider utama offline.
                            </span>
                          </div>
                        </div>
                      </div>

                      <div class="row" style="margin-top: 15px;">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="control-label" for="hijri_offset_days">Koreksi Penyesuaian Tanggal Hijriah (Offset Hari)</label>
                            <div style="display: flex; align-items: center; gap: 15px;">
                              <input type="number" id="hijri_offset_days" name="hijri_offset_days" 
                                     class="form-control form-control-custom" 
                                     value="{{ $settings->hijri_offset_days }}" 
                                     min="-5" max="5" required style="width: 100px; display: inline-block;">
                              <span style="font-size: 0.85rem; color: #94a3b8;">
                                Geser penanggalan Hijriah (misal: isi <code>-1</code> untuk memundurkan sehari, atau <code>1</code> untuk memajukan sehari jika rukyat hilal berbeda).
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>

                      <hr style="border-top: 1px solid rgba(255,255,255,0.06); margin: 20px 0;">

                      <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary" style="padding: 10px 40px; font-size: 1.05rem;">
                          <i class="fa fa-save"></i> Simpan Pengaturan
                        </button>
                      </div>
                    </form>
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
      function copyEndpoint() {
        const text = "{{ url('/api/vmix/hijri-ticker') }}?timezone=WIB";
        navigator.clipboard.writeText(text).then(() => {
          alert('URL Endpoint berhasil disalin ke clipboard!');
        }).catch(err => {
          console.error('Gagal menyalin URL: ', err);
        });
      }

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
