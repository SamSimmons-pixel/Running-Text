<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Tempat — Jadwal Kajian</title>
    <link rel="icon" type="image/png" href="{{ asset('admin-template/img/favicon.png') }}">
    @include('partials.assets')
    @livewireStyles
    <style>
      /* Tooltip container */
      .tooltip-container {
        position: relative;
        width: 100%;
      }

      /* Tooltip text */
      .tooltiptext {
        visibility: hidden;
        opacity: 0;
        transition: opacity 0.2s ease, visibility 0.2s ease;
        background-color: #1e1b4b; /* Premium deep indigo */
        color: #ffffff;
        text-align: left;
        padding: 8px 14px;
        border-radius: 6px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.6);
        position: absolute;
        bottom: 125%; /* Position above input field */
        left: 0;
        z-index: 999; /* Ensure displayed above content */
        white-space: pre-wrap;
        word-break: break-all;
        font-size: 2rem;
        min-width: 220px;
        max-width: 100%;
      }

      /* Triangle indicator */
      .tooltiptext::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 20px;
        margin-left: -5px;
        border-width: 6px;
        border-style: solid;
        border-color: #1e1b4b transparent transparent transparent;
      }

      /* Show the tooltip text on hover / focus active typing */
      .tooltip-container.show-tooltip .tooltiptext {
        visibility: visible;
        opacity: 1;
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
                    <li class="active">Kelola Tempat</li>
                  </ol>
                </div>
                <div class="main-filter">
                  <button class="btn btn-primary" onclick="toggleAddForm()">
                    <i class="fa fa-plus"></i> Tambah Tempat
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
                    <div class="panel-heading" style="display:flex; align-items:center; justify-content:space-between;">
                      <h3 class="panel-title"><i class="fa fa-plus-circle"></i> Tambah Tempat Baru</h3>
                      <button type="button" class="btn btn-xs btn-default" onclick="toggleAddForm()">
                        <i class="fa fa-times"></i> Tutup
                      </button>
                    </div>
                    <div class="panel-body">
                      <form method="POST" action="{{ route('admin.tempat.store') }}">
                        @csrf
                        <div class="form-group">
                          <label class="control-label" for="tempat_nama">Nama Masjid / Tempat Kegiatan</label>
                          <div class="tooltip-container">
                            <input type="text" id="tempat_nama" name="nama" class="form-control form-control-custom" placeholder="Isi Lokasi Disini" value="{{ old('nama') }}" required autocomplete="off">
                            <span class="tooltiptext" id="tempat_nama_tooltip"></span>
                          </div>
                        </div>
                        <div style="display:flex; justify-content:flex-end;">
                          <button type="submit" class="btn btn-primary" style="padding: 8px 30px; font-size: 1.15rem;">
                            <i class="fa fa-check"></i> Simpan
                          </button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

                {{-- Table --}}
                <div class="panel panel-default">
                  <div class="panel-heading" style="display:flex; align-items:center; justify-content:space-between;">
                    <h3 class="panel-title"><i class="fa fa-list"></i> Daftar Tempat</h3>
                    <small class="section-count">Total: {{ $tempatList->count() }} tempat</small>
                  </div>
                  <div class="panel-body" style="padding:0;">
                    @if ($tempatList->isEmpty())
                      <div class="empty-state">
                        <i class="fa fa-map-o" style="font-size:2.5rem; margin-bottom:1rem; display:block; opacity:0.3;"></i>
                        Belum ada data tempat. Tambahkan lokasi pertama!
                      </div>
                    @else
                      <div class="table-responsive">
                        <table class="table table-hover" style="margin-bottom:0;">
                          <thead>
                            <tr>
                              <th style="width: 80px;">No.</th>
                              <th>Nama Tempat</th>
                              <th style="text-align:right; width: 150px;">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($tempatList as $index => $item)
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
        <div style="font-size:1rem; font-weight:700; margin-bottom:0.5rem; color:#e2e8f0;">Hapus Tempat?</div>
        <div style="font-size:0.85rem; color:#64748b; margin-bottom:1.5rem;" id="deleteDesc">
          Tempat ini akan dihapus secara permanen.
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
        document.getElementById('deleteForm').action = `/admin_dashboard/tempat/${id}/delete`;
        document.getElementById('deleteDesc').textContent =
          `"${nama}" akan dihapus secara permanen dari daftar tempat.`;
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

          // Dynamic ongoing input tooltip logic
          const input = document.getElementById('tempat_nama');
          const tooltip = document.getElementById('tempat_nama_tooltip');
          const container = input ? input.closest('.tooltip-container') : null;

          if (input && tooltip && container) {
            const updateTooltip = () => {
              const val = input.value;
              if (val.length > 0) {
                tooltip.textContent = val;
                container.classList.add('show-tooltip');
              } else {
                container.classList.remove('show-tooltip');
              }
            };

            input.addEventListener('input', updateTooltip);
            input.addEventListener('focus', updateTooltip);
            input.addEventListener('blur', function() {
              container.classList.remove('show-tooltip');
            });
          }
        }
      });
    </script>
  </body>
</html>
