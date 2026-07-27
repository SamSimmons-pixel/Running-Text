<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Log Aktivitas — Running Text</title>
    <link rel="icon" type="image/png" href="{{ asset('admin-template/img/favicon.png') }}">
    @include('partials.assets')
    @livewireStyles
    <style>
      .pagination-custom {
        display: flex;
        justify-content: center;
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
      }
      .pagination-custom .pagination {
        margin: 0;
        display: flex;
        gap: 4px;
      }
      .pagination-custom .pagination > li > a,
      .pagination-custom .pagination > li > span {
        background-color: rgba(15, 23, 42, 0.8) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: #cbd5e1 !important;
        border-radius: 6px !important;
        padding: 6px 12px;
        transition: all 0.2s ease-in-out;
      }
      .pagination-custom .pagination > li > a:hover,
      .pagination-custom .pagination > li > a:focus {
        background-color: rgba(59, 130, 246, 0.25) !important;
        border-color: #3b82f6 !important;
        color: #38bdf8 !important;
      }
      .pagination-custom .pagination > li.active > span,
      .pagination-custom .pagination > li.active > a {
        background-color: #3b82f6 !important;
        border-color: #3b82f6 !important;
        color: #ffffff !important;
        font-weight: 600;
      }
      .pagination-custom .pagination > li.disabled > span,
      .pagination-custom .pagination > li.disabled > a {
        background-color: rgba(15, 23, 42, 0.3) !important;
        border-color: rgba(255, 255, 255, 0.05) !important;
        color: #475569 !important;
        cursor: not-allowed;
      }
    </style>
  </head>
  <body class="framed main-scrollable">
    <div class="wrapper">

      @include('partials.navbar')

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
                    <li class="active">Log Aktivitas</li>
                  </ol>
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

                {{-- Table Panel --}}
                <div class="panel panel-default">
                  <div class="panel-heading" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
                    <h3 class="panel-title" style="margin:0;">
                      <i class="fa fa-history"></i> Log Aktivitas Sistem
                      <small style="margin-left:8px; color:rgba(255,255,255,0.4);" class="section-count">Total: {{ $logs->total() }} riwayat</small>
                    </h3>
                    @if (Auth::user() && Auth::user()->role === 'admin_operator' && $logs->total() > 0)
                      <div style="display:flex; gap:8px;">
                        <button type="button" class="btn btn-xs" onclick="openDelete30Modal()" style="padding: 5px 12px; border-radius: 6px; font-weight: 600; background-color: #b45b08ff;">
                          <i class="fa fa-trash"></i> Hapus 30 Terlama
                        </button>
                        <button type="button" class="btn btn-xs btn-danger" onclick="openDeleteAllModal()" style="padding: 5px 12px; border-radius: 6px; font-weight: 600;">
                          <i class="fa fa-trash-o"></i> Hapus Semua
                        </button>
                      </div>
                    @endif
                  </div>
                  <div class="panel-body" style="padding:0;">
                    @if ($logs->isEmpty())
                      <div class="empty-state">
                        <i class="fa fa-history" style="font-size:2.5rem; margin-bottom:1rem; display:block; opacity:0.3;"></i>
                        Belum ada riwayat aktivitas sistem.
                      </div>
                    @else
                      <div class="table-responsive">
                        <table class="table table-hover" style="margin-bottom:0;">
                          <thead>
                            <tr>
                              <th style="width: 80px;">No.</th>
                              <th style="width: 220px;">Waktu &amp; Tanggal</th>
                              <th>Deskripsi Aktivitas</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($logs as $index => $log)
                            <tr>
                              <td>{{ $logs->firstItem() + $index }}</td>
                              <td style="white-space:nowrap; color:#94a3b8;">
                                <strong>{{ $log->created_at->format('d M Y, H:i') }}</strong>
                                <small style="display:block; color:#64748b; font-size:1.1rem; margin-top:2px;">
                                  {{ $log->created_at->diffForHumans() }}
                                </small>
                              </td>
                              <td style="color:#cbd5e1; line-height: 1.5;">
                                <i class="fa fa-circle" style="color: #22c55e; font-size: 0.8rem; margin-right: 6px;"></i>
                                {{ $log->message }}
                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>

                      {{-- Pagination Links --}}
                      <div class="pagination-custom">
                        {{ $logs->links('pagination::bootstrap-4') }}
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

    @if (Auth::user() && Auth::user()->role === 'admin_operator')
      {{-- Modal Hapus 30 Terlama --}}
      <div class="modal-backdrop-custom" id="delete30ModalBackdrop" onclick="closeDelete30Modal(event)">
        <div class="modal-confirm">
          <div style="font-size:2.5rem; margin-bottom:1rem;">⚠️</div>
          <div style="font-size:1.8rem; font-weight:700; margin-bottom:0.5rem; color:#e2e8f0;">Hapus 30 Log Terlama?</div>
          <div style="font-size:1.4rem; color:#94a3b8; margin-bottom:1.5rem;">
            Apakah Anda yakin ingin menghapus <strong>30 riwayat log aktivitas terlama</strong> dari sistem?
          </div>
          <div style="display:flex; justify-content:center; gap:1rem;">
            <button type="button" class="btn btn-default" onclick="closeDelete30Modal(null)">Batal</button>
            <form method="POST" action="{{ route('admin.activity-logs.destroy-30') }}" style="display:inline;">
              @csrf
              <button type="submit" class="btn btn-warning">
                <i class="fa fa-trash"></i> Ya, Hapus 30
              </button>
            </form>
          </div>
        </div>
      </div>

      {{-- Modal Hapus Semua --}}
      <div class="modal-backdrop-custom" id="deleteAllModalBackdrop" onclick="closeDeleteAllModal(event)">
        <div class="modal-confirm">
          <div style="font-size:2.5rem; margin-bottom:1rem;">🚨</div>
          <div style="font-size:1.8rem; font-weight:700; margin-bottom:0.5rem; color:#ef4444;">Hapus Semua Log Aktivitas?</div>
          <div style="font-size:1.4rem; color:#94a3b8; margin-bottom:1.5rem;">
            Apakah Anda yakin ingin menghapus <strong>seluruh riwayat log aktivitas</strong> sistem? Tindakan ini tidak dapat dibatalkan.
          </div>
          <div style="display:flex; justify-content:center; gap:1rem;">
            <button type="button" class="btn btn-default" onclick="closeDeleteAllModal(null)">Batal</button>
            <form method="POST" action="{{ route('admin.activity-logs.destroy-all') }}" style="display:inline;">
              @csrf
              <button type="submit" class="btn btn-danger">
                <i class="fa fa-trash-o"></i> Ya, Hapus Semua
              </button>
            </form>
          </div>
        </div>
      </div>
    @endif

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

      function openDelete30Modal() {
        document.getElementById('delete30ModalBackdrop').classList.add('open');
      }
      function closeDelete30Modal(e) {
        if (e === null || e.target === document.getElementById('delete30ModalBackdrop')) {
          document.getElementById('delete30ModalBackdrop').classList.remove('open');
        }
      }
      function openDeleteAllModal() {
        document.getElementById('deleteAllModalBackdrop').classList.add('open');
      }
      function closeDeleteAllModal(e) {
        if (e === null || e.target === document.getElementById('deleteAllModalBackdrop')) {
          document.getElementById('deleteAllModalBackdrop').classList.remove('open');
        }
      }
    </script>
  </body>
</html>
