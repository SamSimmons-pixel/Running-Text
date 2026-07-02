<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard — Jadwal Kajian</title>
    <meta name="description" content="Panel admin untuk mengelola jadwal kajian.">
    <link rel="icon" type="image/png" href="{{ asset('admin-template/img/favicon.png') }}">
    <link href="{{ asset('admin-template/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,400italic,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin-template/libs/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-template/libs/jquery.scrollbar/jquery.scrollbar.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-template/css/right.dark.css') }}" rel="stylesheet">

    <style>
      /* ── Custom overrides for Kajian Admin ── */
      :root {
        --accent:   #6d28d9;
        --accent-h: #7c3aed;
        --green:    #10b981;
        --red:      #ef4444;
        --red-h:    #dc2626;
      }

      /* ── Toggle switch ── */
      .toggle-form { display: inline; }
      .toggle {
        position: relative;
        display: inline-block;
        width: 42px;
        height: 22px;
        vertical-align: middle;
      }
      .toggle input { opacity: 0; width: 0; height: 0; }
      .toggle-slider {
        position: absolute;
        cursor: pointer;
        inset: 0;
        background: rgba(255,255,255,0.12);
        border-radius: 22px;
        transition: background 0.2s;
      }
      .toggle-slider::before {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        left: 3px;
        top: 3px;
        background: #fff;
        border-radius: 50%;
        transition: transform 0.2s;
      }
      .toggle input:checked + .toggle-slider { background: var(--green); }
      .toggle input:checked + .toggle-slider::before { transform: translateX(20px); }

      /* ── Logo cell ── */
      .td-logo img {
        width: 38px; height: 38px;
        border-radius: 7px;
        object-fit: contain;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.07);
        padding: 3px;
      }
      .td-logo .no-logo {
        width: 38px; height: 38px;
        border-radius: 7px;
        background: rgba(255,255,255,0.04);
        border: 1px dashed rgba(255,255,255,0.13);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6rem;
        color: #64748b;
      }

      /* ── Add/Edit form card ── */
      .form-card-kajian {
        display: none;
        margin-bottom: 1.5rem;
      }
      .form-card-kajian.open { display: block; }

      /* ── Table actions ── */
      .td-actions {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: nowrap;
      }

      /* ── Alerts ── */
      .alert-kajian {
        border-radius: 8px;
        padding: 0.75rem 1.1rem;
        font-size: 0.875rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
      }
      .alert-kajian.success { background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.3); color: #6ee7b7; }
      .alert-kajian.error   { background: rgba(239,68,68,0.1);   border: 1px solid rgba(239,68,68,0.3);  color: #fca5a5; }

      /* ── Empty state ── */
      .empty-state {
        padding: 3rem;
        text-align: center;
        color: #64748b;
        font-size: 0.9rem;
      }

      /* ── Section count badge ── */
      .section-count {
        font-size: 0.78rem;
        color: #64748b;
        margin-top: 2px;
      }

      /* ── Edit/Delete modal backdrops ── */
      .modal-backdrop-custom {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.65);
        backdrop-filter: blur(4px);
        z-index: 1060;
        align-items: center;
        justify-content: center;
        padding: 1rem;
      }
      .modal-backdrop-custom.open { display: flex; }

      .modal-kajian {
        background: #1a1d27;
        border: 1px solid rgba(255,255,255,0.13);
        border-radius: 16px;
        padding: 1.75rem;
        width: 100%;
        max-width: 680px;
        max-height: 90vh;
        overflow-y: auto;
        animation: modalIn 0.2s ease;
      }

      .modal-confirm {
        background: #1a1d27;
        border: 1px solid rgba(255,255,255,0.13);
        border-radius: 16px;
        padding: 2rem;
        width: 100%;
        max-width: 420px;
        text-align: center;
        animation: modalIn 0.2s ease;
      }

      @keyframes modalIn {
        from { opacity: 0; transform: translateY(16px) scale(0.98); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
      }

      .modal-title-custom {
        font-size: 1rem;
        font-weight: 700;
        color: #e2e8f0;
      }

      .btn-close-modal {
        background: none;
        border: none;
        color: #64748b;
        font-size: 1.3rem;
        cursor: pointer;
        line-height: 1;
        padding: 4px;
        border-radius: 6px;
        transition: color 0.15s;
      }
      .btn-close-modal:hover { color: var(--red); }

      /* ── Custom form controls ── */
      .form-control-custom {
        background: #22263a !important;
        border: 1px solid rgba(255,255,255,0.07) !important;
        border-radius: 8px !important;
        color: #e2e8f0 !important;
        font-size: 0.875rem !important;
        transition: border-color 0.15s;
      }
      .form-control-custom:focus {
        border-color: var(--accent) !important;
        outline: none !important;
        box-shadow: none !important;
      }
      .form-control-custom::placeholder { color: #64748b; }

      /* ── Action buttons ── */
      .btn-kajian-outline {
        background: transparent;
        color: #94a3b8;
        border: 1px solid rgba(255,255,255,0.13);
        border-radius: 6px;
        padding: 4px 10px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: border-color 0.15s, color 0.15s;
        white-space: nowrap;
      }
      .btn-kajian-outline:hover { border-color: var(--accent); color: #a78bfa; }

      .btn-kajian-danger {
        background: var(--red);
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 4px 10px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: background 0.15s;
        white-space: nowrap;
      }
      .btn-kajian-danger:hover { background: var(--red-h); color: #fff; }

      /* ── Topbar user info ── */
      .topbar-user-info {
        display: flex;
        align-items: center;
        height: 100%;
        padding: 0 1rem;
        font-size: 0.82rem;
        color: #94a3b8;
      }
      .topbar-user-info strong { color: #e2e8f0; margin-left: 4px; }
    </style>
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
                <span><i class="fa fa-external-link" style="font-size:0.75em;"></i> Running Text</span>
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

        {{-- ── Sidebar ── --}}
        <div class="sidebar">
          {{-- Quick icon strip --}}
          <div class="quickmenu">
            <div class="quickmenu__cont">
              <div class="quickmenu__list">
                <div class="quickmenu__item active" title="Dashboard">
                  <div class="fa fa-fw fa-home"></div>
                </div>
                <div class="quickmenu__item" title="Running Text">
                  <div class="fa fa-fw fa-television"></div>
                </div>
                <div class="quickmenu__item" title="Pengaturan">
                  <div class="fa fa-fw fa-cog"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="scrollable scrollbar-macosx">
            <div class="sidebar__cont">

              {{-- Navigation --}}
              <div class="sidebar__menu">
                <div class="sidebar__title">Menu Utama</div>
                <ul class="nav nav-menu">
                  <li class="active">
                    <a href="{{ route('admin.dashboard') }}">
                      <div class="nav-menu__ico"><i class="fa fa-fw fa-home"></i></div>
                      <div class="nav-menu__text"><span>Dashboard</span></div>
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('admin.narasumber') }}">
                      <div class="nav-menu__ico"><i class="fa fa-fw fa-user"></i></div>
                      <div class="nav-menu__text"><span>Kelola Narasumber</span></div>
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('admin.tempat') }}">
                      <div class="nav-menu__ico"><i class="fa fa-fw fa-map-marker"></i></div>
                      <div class="nav-menu__text"><span>Kelola Tempat</span></div>
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('admin.kontak') }}">
                      <div class="nav-menu__ico"><i class="fa fa-fw fa-phone"></i></div>
                      <div class="nav-menu__text"><span>Kelola Kontak</span></div>
                    </a>
                  </li>
                  <li>
                    <a href="{{ url('/') }}" target="_blank">
                      <div class="nav-menu__ico"><i class="fa fa-fw fa-television"></i></div>
                      <div class="nav-menu__text"><span>Running Text</span></div>
                      <div class="nav-menu__right">
                        <i class="fa fa-fw fa-external-link" style="font-size:0.7em; opacity:0.5;"></i>
                      </div>
                    </a>
                  </li>
                </ul>
              </div>

              {{-- Stats --}}
              <div class="sidebar__menu">
                <div class="sidebar__title">Statistik</div>
                @php
                  $totalKajian  = $kajian->count();
                  $activeKajian = $kajian->where('Tampilkan', 1)->count();
                  $hiddenKajian = $totalKajian - $activeKajian;
                @endphp
                <div class="sidestat">
                  <div class="sidestat__cont">
                    <div class="sidestat__item">
                      <div class="sidestat__value">{{ $totalKajian }}</div>
                      <div class="sidestat__text">total kajian</div>
                    </div>
                    <div class="sidestat__item">
                      <div class="sidestat__value" style="color:#34d399;">{{ $activeKajian }}</div>
                      <div class="sidestat__text">ditampilkan</div>
                    </div>
                    <div class="sidestat__item">
                      <div class="sidestat__value" style="color:#94a3b8;">{{ $hiddenKajian }}</div>
                      <div class="sidestat__text">disembunyikan</div>
                    </div>
                  </div>
                </div>
              </div>

              {{-- Quick Actions --}}
              <div class="sidebar__menu">
                <div class="sidebar__title">Aksi Cepat</div>
                <div class="sidebar__btn">
                  <a class="btn btn-block btn-default" href="#" onclick="toggleAddForm(); return false;">
                    <i class="fa fa-plus"></i> Tambah Kajian Baru
                  </a>
                </div>
                <div class="sidebar__btn" style="margin-top:0.4rem;">
                  <a class="btn btn-block btn-default" href="{{ url('/') }}" target="_blank">
                    <i class="fa fa-eye"></i> Lihat Running Text
                  </a>
                </div>
                <div class="sidebar__btn" style="margin-top:0.4rem;">
                  <a class="btn btn-block btn-danger" href="#"
                     onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                    <i class="fa fa-sign-out"></i> Logout
                  </a>
                  <form id="logout-form-sidebar" method="POST" action="{{ route('logout') }}" style="display:none;">@csrf</form>
                </div>
              </div>

            </div>
          </div>
        </div>
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
                    <li class="active">Kelola Kajian</li>
                  </ol>
                </div>
                <div class="main-filter">
                  <button class="btn btn-primary" onclick="toggleAddForm()">
                    <i class="fa fa-plus"></i> Tambah Kajian
                  </button>
                </div>
              </div>

              <div class="container-fluid half-padding">

                {{-- ── Alerts ── --}}
                @if (session('success'))
                  <div class="alert-kajian success">
                    <i class="fa fa-check-circle"></i>
                    {{ session('success') }}
                  </div>
                @endif
                @if (session('error'))
                  <div class="alert-kajian error">
                    <i class="fa fa-exclamation-circle"></i>
                    {{ session('error') }}
                  </div>
                @endif
                @if ($errors->any())
                  <div class="alert-kajian error">
                    <i class="fa fa-exclamation-circle"></i>
                    <div>
                      @foreach ($errors->all() as $e) <div>{{ $e }}</div> @endforeach
                    </div>
                  </div>
                @endif

                {{-- ── Global Logo Upload Panel ── --}}
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-image"></i> Logo Global</h3>
                  </div>
                  <div class="panel-body">
                    <form method="POST" action="{{ route('admin.logo.update') }}" enctype="multipart/form-data" class="form-inline" style="display:flex; align-items:center; gap:20px; flex-wrap:wrap; justify-content:space-between;">
                      @csrf
                      <div style="display:flex; justify-content:space-between; gap:20px; width: 89%;">
                        <div class="form-group" style="margin-bottom:0; display:flex; align-items:center; gap:10px;">
                          <label class="control-label" style="margin-bottom:0;">Logo Berjalan Saat Ini:</label>
                          <div class="td-logo" style="display:inline-block;">
                            @if ($logoUrl)
                              <img src="{{ $logoUrl }}" alt="Logo Global" style="max-height:50px; width:auto; border-radius:6px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.07); padding:3px;">
                            @else
                              <span class="no-logo" style="display:inline-block; padding:10px 15px; background:rgba(255,255,255,0.04); border:1px dashed rgba(255,255,255,0.13); border-radius:6px; color:#64748b; font-size:0.8rem; font-weight:normal;">Belum ada logo</span>
                            @endif
                          </div>
                        </div>
                        <div class="form-group" style="margin-bottom:0; display:flex; align-items:center; gap:10px;">
                          <label class="control-label" style="margin-bottom:0;">Ganti Logo Global:</label>
                          <input type="file" name="Logo" class="form-control form-control-custom" accept="image/*" required style="display:inline-block; width:auto;">
                        </div>
                      </div>
                      <button type="submit" class="btn btn-primary" style="margin-top:0;">
                        <i class="fa fa-upload"></i> Upload Logo
                      </button>
                    </form>
                  </div>
                </div>

                {{-- ── Add Kajian Form (collapsible) ── --}}
                <div class="form-card-kajian" id="addForm">
                  <div class="panel panel-primary">
                    <div class="panel-heading" style="display:flex; align-items:center; justify-content:space-between;">
                      <h3 class="panel-title"><i class="fa fa-plus-circle"></i> Tambah Kajian Baru</h3>
                      <button type="button" class="btn btn-xs btn-default" onclick="toggleAddForm()">
                        <i class="fa fa-times"></i> Tutup
                      </button>
                    </div>
                    <div class="panel-body">
                      <form method="POST" action="{{ route('admin.kajian.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                          <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                              <label class="control-label">Judul Kajian</label>
                              <input type="text" name="Judul" class="form-control form-control-custom"
                                     placeholder="Nama kajian" value="{{ old('Judul') }}" required>
                            </div>
                          </div>
                          <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                              <label class="control-label">Narasumber</label>
                              <select name="Narasumber" class="form-control form-control-custom" required>
                                <option value="" disabled selected>Pilih Narasumber</option>
                                @foreach($narasumberList as $nara)
                                  <option value="{{ $nara->nama }}" {{ old('Narasumber') == $nara->nama ? 'selected' : '' }}>
                                    {{ $nara->nama }}
                                  </option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                              <label class="control-label">Tanggal &amp; Waktu</label>
                              <input type="datetime-local" name="Tanggal" class="form-control form-control-custom"
                                     value="{{ old('Tanggal') }}" required>
                            </div>
                          </div>
                          <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                              <label class="control-label">Tempat</label>
                              <select name="Tempat" class="form-control form-control-custom" required>
                                <option value="" disabled selected>Pilih Tempat</option>
                                @foreach($tempatList as $temp)
                                  <option value="{{ $temp->nama }}" {{ old('Tempat') == $temp->nama ? 'selected' : '' }}>
                                    {{ $temp->nama }}
                                  </option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                              <label class="control-label">Kontak</label>
                              <select name="Kontak" class="form-control form-control-custom">
                                <option value="" selected>— Tanpa Kontak —</option>
                                @foreach($kontakList as $kon)
                                  <option value="{{ $kon->nama }}" {{ old('Kontak') == $kon->nama ? 'selected' : '' }}>
                                    {{ $kon->nama }}
                                  </option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="row" style="margin-bottom: 1rem;">
                          <div class="col-sm-12">
                            <div class="form-group" style="display:flex; align-items:center; gap:10px; margin-bottom:0;">
                              <label class="toggle" for="add_Tampilkan">
                                <input type="checkbox" id="add_Tampilkan" name="Tampilkan" value="1"
                                       {{ old('Tampilkan') ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                              </label>
                              <span style="color:#94a3b8; font-size:0.85rem;">Tampilkan di running text</span>
                            </div>
                          </div>
                        </div>

                        <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                          <button type="button" class="btn btn-default" onclick="toggleAddForm()">Batal</button>
                          <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check"></i> Simpan
                          </button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                {{-- End Add Form --}}

                {{-- ── Kajian Table ── --}}
                <div class="panel panel-default">
                  <div class="panel-heading" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.5rem;">
                    <h3 class="panel-title"><i class="fa fa-list"></i> Daftar Kajian</h3>
                    <small class="section-count">Total: {{ $kajian->count() }} kajian</small>
                  </div>
                  <div class="panel-body" style="padding:0;">
                    @if ($kajian->isEmpty())
                      <div class="empty-state">
                        <i class="fa fa-calendar-o" style="font-size:2.5rem; margin-bottom:1rem; display:block; opacity:0.3;"></i>
                        Belum ada data kajian. Tambahkan kajian pertama!
                      </div>
                    @else
                      <div class="table-responsive">
                        <table class="table table-hover" style="margin-bottom:0;">
                          <thead>
                            <tr>
                              <th>Judul &amp; Narasumber</th>
                              <th>Tanggal</th>
                              <th>Tempat</th>
                              <th>Kontak</th>
                              <th style="text-align:center;">Tampil</th>
                              <th style="text-align:right;">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($kajian as $item)
                            <tr>
                              {{-- Judul & Narasumber --}}
                              <td>
                                <strong>{{ $item->Judul }}</strong>
                                <small style="display:block; color:#94a3b8; font-size:0.78rem; margin-top:2px;">
                                  {{ $item->Narasumber }}
                                </small>
                              </td>

                              {{-- Tanggal --}}
                              <td style="white-space:nowrap; color:#94a3b8;">
                                {{ \Carbon\Carbon::parse($item->Tanggal)->locale('id')->isoFormat('ddd, D MMM Y') }}
                                <br>
                                <small>{{ \Carbon\Carbon::parse($item->Tanggal)->format('H:i') }}</small>
                              </td>

                              {{-- Tempat --}}
                              <td style="color:#94a3b8;">{{ $item->Tempat }}</td>

                              {{-- Kontak --}}
                              <td style="color:#94a3b8;">{{ $item->Kontak ?: '—' }}</td>

                              {{-- Toggle Tampilkan --}}
                              <td style="text-align:center;">
                                <form method="POST" action="{{ route('admin.kajian.toggle', $item->id) }}"
                                      class="toggle-form" id="toggleForm-{{ $item->id }}">
                                  @csrf
                                </form>
                                <label class="toggle"
                                       title="{{ $item->Tampilkan ? 'Klik untuk sembunyikan' : 'Klik untuk tampilkan' }}">
                                  <input type="checkbox"
                                    {{ $item->Tampilkan ? 'checked' : '' }}
                                    onchange="document.getElementById('toggleForm-{{ $item->id }}').submit()">
                                  <span class="toggle-slider"></span>
                                </label>
                              </td>

                              {{-- Actions --}}
                              <td>
                                <div class="td-actions" style="justify-content:flex-end;">
                                  <button class="btn-kajian-outline"
                                    onclick="openEditModal(
                                      '{{ $item->id }}',
                                      '{{ addslashes($item->Judul) }}',
                                      '{{ addslashes($item->Narasumber) }}',
                                      '{{ \Carbon\Carbon::parse($item->Tanggal)->format('Y-m-d\TH:i') }}',
                                      '{{ addslashes($item->Tempat) }}',
                                      '{{ addslashes($item->Kontak ?? '') }}',
                                      '{{ $item->Tampilkan ? 'true' : 'false' }}'
                                    )">
                                    <i class="fa fa-pencil"></i> Edit
                                  </button>
                                  <button class="btn-kajian-danger"
                                    onclick="openDeleteModal({{ $item->id }}, '{{ addslashes($item->Judul) }}')">
                                    <i class="fa fa-trash"></i> Hapus
                                  </button>
                                </div>
                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    @endif
                  </div>
                </div>
                {{-- End Table --}}

              </div>{{-- end container-fluid --}}
            </div>{{-- end main__cont --}}
          </div>{{-- end main__scroll --}}
        </div>{{-- end main --}}

      </div>{{-- end dashboard --}}
    </div>{{-- end wrapper --}}

    {{-- ── Edit Modal ── --}}
    <div class="modal-backdrop-custom" id="editModalBackdrop" onclick="closeEditModal(event)">
      <div class="modal-kajian">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem;">
          <div class="modal-title-custom"><i class="fa fa-pencil-square-o"></i> Edit Kajian</div>
          <button class="btn-close-modal" onclick="closeEditModal(null)">✕</button>
        </div>
        <form method="POST" id="editForm" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label">Judul Kajian</label>
                <input id="edit_Judul" type="text" name="Judul" class="form-control form-control-custom" required>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label">Narasumber</label>
                <select id="edit_Narasumber" name="Narasumber" class="form-control form-control-custom" required>
                  <option value="" disabled>Pilih Narasumber</option>
                  @foreach($narasumberList as $nara)
                    <option value="{{ $nara->nama }}">{{ $nara->nama }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label">Tanggal &amp; Waktu</label>
                <input id="edit_Tanggal" type="datetime-local" name="Tanggal" class="form-control form-control-custom" required>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label">Tempat</label>
                <select id="edit_Tempat" name="Tempat" class="form-control form-control-custom" required>
                  <option value="" disabled>Pilih Tempat</option>
                  @foreach($tempatList as $temp)
                    <option value="{{ $temp->nama }}">{{ $temp->nama }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label">Kontak</label>
                <select id="edit_Kontak" name="Kontak" class="form-control form-control-custom">
                  <option value="">— Tanpa Kontak —</option>
                  @foreach($kontakList as $kon)
                    <option value="{{ $kon->nama }}">{{ $kon->nama }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="row" style="margin-bottom:1.25rem;">
            <div class="col-sm-12">
              <div class="form-group" style="display:flex; align-items:center; gap:10px; margin-bottom:0;">
                <label class="toggle" for="edit_Tampilkan">
                  <input type="checkbox" id="edit_Tampilkan" name="Tampilkan" value="1">
                  <span class="toggle-slider"></span>
                </label>
                <span style="color:#94a3b8; font-size:0.85rem;">Tampilkan di running text</span>
              </div>
            </div>
          </div>

          <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
            <button type="button" class="btn btn-default" onclick="closeEditModal(null)">Batal</button>
            <button type="submit" class="btn btn-primary">
              <i class="fa fa-check"></i> Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- ── Delete Confirm Modal ── --}}
    <div class="modal-backdrop-custom" id="deleteModalBackdrop" onclick="closeDeleteModal(event)">
      <div class="modal-confirm">
        <div style="font-size:2.5rem; margin-bottom:1rem;">🗑️</div>
        <div style="font-size:1rem; font-weight:700; margin-bottom:0.5rem; color:#e2e8f0;">Hapus Kajian?</div>
        <div style="font-size:0.85rem; color:#64748b; margin-bottom:1.5rem;" id="deleteDesc">
          Kajian ini akan dihapus secara permanen dan tidak bisa dikembalikan.
        </div>
        <div style="display:flex; justify-content:center; gap:1rem;">
          <button class="btn btn-default" onclick="closeDeleteModal(null)">Batal</button>
          <form method="POST" id="deleteForm" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-danger">
              <i class="fa fa-trash"></i> Ya, Hapus
            </button>
          </form>
        </div>
      </div>
    </div>

    {{-- ── Scripts ── --}}
    <script src="{{ asset('admin-template/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin-template/libs/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin-template/libs/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('admin-template/js/main.js') }}"></script>

    <script>
      // ── Add Form toggle ─────────────────────────────────────────
      function toggleAddForm() {
        const form = document.getElementById('addForm');
        form.classList.toggle('open');
        if (form.classList.contains('open')) {
          form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      }

      // ── Edit Modal ──────────────────────────────────────────────
      function openEditModal(id, judul, narasumber, tanggal, tempat, kontak, tampilkan) {
        document.getElementById('editForm').action = `/admin_dashboard/${id}`;
        document.getElementById('edit_Judul').value           = judul;
        document.getElementById('edit_Narasumber').value      = narasumber;
        document.getElementById('edit_Tanggal').value         = tanggal;
        document.getElementById('edit_Tempat').value          = tempat;
        document.getElementById('edit_Kontak').value          = kontak;
        document.getElementById('edit_Tampilkan').checked     = (tampilkan === 'true');
        document.getElementById('editModalBackdrop').classList.add('open');
      }

      function closeEditModal(e) {
        if (e === null || e.target === document.getElementById('editModalBackdrop')) {
          document.getElementById('editModalBackdrop').classList.remove('open');
        }
      }

      // ── Delete Modal ────────────────────────────────────────────
      function openDeleteModal(id, judul) {
        document.getElementById('deleteForm').action = `/admin_dashboard/${id}/delete`;
        document.getElementById('deleteDesc').textContent =
          `"${judul}" akan dihapus secara permanen dan tidak bisa dikembalikan.`;
        document.getElementById('deleteModalBackdrop').classList.add('open');
      }

      function closeDeleteModal(e) {
        if (e === null || e.target === document.getElementById('deleteModalBackdrop')) {
          document.getElementById('deleteModalBackdrop').classList.remove('open');
        }
      }

      // Close modals on Escape key
      document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
          closeEditModal(null);
          closeDeleteModal(null);
        }
      });

      // Auto-open add form if there were validation errors on store
      @if ($errors->any() && old('Judul'))
      toggleAddForm();
      @endif
    </script>

  </body>
</html>
