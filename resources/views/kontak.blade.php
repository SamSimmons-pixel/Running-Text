<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Kontak — Jadwal Kajian</title>
    <link rel="icon" type="image/png" href="{{ asset('admin-template/img/favicon.png') }}">
    <link href="{{ asset('admin-template/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,400italic,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin-template/libs/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-template/libs/jquery.scrollbar/jquery.scrollbar.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-template/css/right.dark.css') }}" rel="stylesheet">

    <style>
      /* ── Custom overrides ── */
      :root {
        --accent:   #6d28d9;
        --accent-h: #7c3aed;
        --green:    #10b981;
        --red:      #ef4444;
        --red-h:    #dc2626;
      }

      .form-card-kajian {
        margin-bottom: 1.5rem;
      }

      .td-actions {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: nowrap;
      }

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

      .empty-state {
        padding: 3rem;
        text-align: center;
        color: #64748b;
        font-size: 0.9rem;
      }

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
    @livewireStyles
  </head>
  <body class="framed main-scrollable">
    <div class="wrapper">

      {{-- ── Top Navbar ── --}}
      <nav class="navbar navbar-static-top header-navbar">
        <div class="header-navbar-mobile">
          <div class="header-navbar-mobile__menu">
            <button class="btn" type="button"><i class="fa fa-bars"></i></button>
          </div>
          <div class="header-navbar-mobile__title"><span>Kelola Kontak</span></div>
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
                    <li class="active">Kelola Kontak</li>
                  </ol>
                </div>
                <div class="main-filter">
                  <button class="btn btn-primary" onclick="toggleAddForm()">
                    <i class="fa fa-plus"></i> Tambah Kontak
                  </button>
                </div>
              </div>

              <div class="container-fluid half-padding">

                {{-- Alerts --}}
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

                {{-- Add Form --}}
                <div class="form-card-kajian" id="addForm">
                  <div class="panel panel-primary">
                    <div class="panel-heading">
                      <h3 class="panel-title"><i class="fa fa-plus-circle"></i> Tambah Kontak Baru</h3>
                    </div>
                    <div class="panel-body">
                      <form method="POST" action="{{ route('admin.kontak.store') }}">
                        @csrf
                        <div class="row">
                          <div class="col-sm-8 col-md-9">
                            <div class="form-group">
                              <label class="control-label" for="kontak_nama">No. HP / WA / Nama Kontak</label>
                              <input id="kontak_nama" type="text" name="nama" class="form-control form-control-custom"
                                     placeholder="Contoh: 0812-3456-7890 (Abu Ahmad)" value="{{ old('nama') }}" required>
                            </div>
                          </div>
                          <div class="col-sm-4 col-md-3" style="margin-top: 25px;">
                            <button type="submit" class="btn btn-primary btn-block">
                              <i class="fa fa-check"></i> Simpan
                            </button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

                {{-- Table --}}
                <div class="panel panel-default">
                  <div class="panel-heading" style="display:flex; align-items:center; justify-content:space-between;">
                    <h3 class="panel-title"><i class="fa fa-list"></i> Daftar Kontak</h3>
                    <small class="section-count">Total: {{ $kontakList->count() }} kontak</small>
                  </div>
                  <div class="panel-body" style="padding:0;">
                    @if ($kontakList->isEmpty())
                      <div class="empty-state">
                        <i class="fa fa-phone" style="font-size:2.5rem; margin-bottom:1rem; display:block; opacity:0.3;"></i>
                        Belum ada data kontak. Tambahkan kontak pertama!
                      </div>
                    @else
                      <div class="table-responsive">
                        <table class="table table-hover" style="margin-bottom:0;">
                          <thead>
                            <tr>
                              <th style="width: 80px;">No.</th>
                              <th>Nama Kontak / Informasi</th>
                              <th style="text-align:right; width: 150px;">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($kontakList as $index => $item)
                            <tr>
                              <td>{{ $index + 1 }}</td>
                              <td><strong>{{ $item->nama }}</strong></td>
                              <td>
                                <div class="td-actions" style="justify-content:flex-end;">
                                  <button class="btn-kajian-danger"
                                    onclick="openDeleteModal({{ $item->id }}, '{{ addslashes($item->nama) }}')">
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

              </div>{{-- end container-fluid --}}
            </div>{{-- end main__cont --}}
          </div>{{-- end main__scroll --}}
        </div>{{-- end main --}}

      </div>{{-- end dashboard --}}
    </div>{{-- end wrapper --}}

    {{-- Delete Modal --}}
    <div class="modal-backdrop-custom" id="deleteModalBackdrop" onclick="closeDeleteModal(event)">
      <div class="modal-confirm">
        <div style="font-size:2.5rem; margin-bottom:1rem;">🗑️</div>
        <div style="font-size:1rem; font-weight:700; margin-bottom:0.5rem; color:#e2e8f0;">Hapus Kontak?</div>
        <div style="font-size:0.85rem; color:#64748b; margin-bottom:1.5rem;" id="deleteDesc">
          Kontak ini akan dihapus secara permanen.
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

    <script src="{{ asset('admin-template/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin-template/libs/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin-template/libs/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('admin-template/js/main.js') }}"></script>

    <script>
      function toggleAddForm() {
        var form = document.getElementById('addForm');
        form.classList.toggle('open');
      }

      @if ($errors->any() && old('nama'))
      toggleAddForm();
      @endif

      function openDeleteModal(id, nama) {
        document.getElementById('deleteForm').action = `/admin_dashboard/kontak/${id}/delete`;
        document.getElementById('deleteDesc').textContent =
          `"${nama}" akan dihapus secara permanen dari daftar kontak.`;
        document.getElementById('deleteModalBackdrop').classList.add('open');
      }

      function closeDeleteModal(e) {
        if (e === null || e.target === document.getElementById('deleteModalBackdrop')) {
          document.getElementById('deleteModalBackdrop').classList.remove('open');
        }
      }

      document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
          closeDeleteModal(null);
        }
      });
    </script>
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
