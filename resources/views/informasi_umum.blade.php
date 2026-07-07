<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Informasi Umum — Jadwal Kajian</title>
    <link rel="icon" type="image/png" href="{{ asset('admin-template/img/favicon.png') }}">
    @include('partials.assets')
    <style>
      /* ── Custom overrides ── */
      :root {
        --accent:   #6d28d9;
        --accent-h: #7c3aed;
        --green:    #10b981;
        --red:      #ef4444;
        --red-h:    #dc2626;
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
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
      }
      .alert-kajian.success {
        background-color: rgba(16, 185, 129, 0.15);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.25);
      }
      .alert-kajian.error {
        background-color: rgba(239, 68, 68, 0.15);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.25);
      }

      /* ── Custom Switch Toggle ── */
      .toggle {
        position: relative;
        display: inline-block;
        width: 38px;
        height: 22px;
        margin-bottom: 0;
        vertical-align: middle;
      }
      .toggle input {
        opacity: 0;
        width: 0;
        height: 0;
      }
      .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.15);
        transition: .3s;
        border-radius: 20px;
      }
      .toggle-slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
      }
      .toggle input:checked + .toggle-slider {
        background-color: var(--green);
        border-color: var(--green);
      }
      .toggle input:checked + .toggle-slider:before {
        transform: translateX(16px);
      }

      /* ── Modal Backdrop & Card Custom ── */
      .modal-backdrop-custom {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s ease;
      }
      .modal-backdrop-custom.open {
        opacity: 1;
        pointer-events: auto;
      }
      .modal-card-custom {
        background: #1a1d27;
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 12px;
        width: 100%;
        max-width: 600px;
        padding: 2rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
        transform: scale(0.9);
        transition: transform 0.25s ease;
      }
      .modal-backdrop-custom.open .modal-card-custom {
        transform: scale(1);
      }

      .section-count {
        font-size: 0.78rem;
        color: #64748b;
      }

      .empty-state {
        padding: 3rem;
        text-align: center;
        color: #64748b;
        font-size: 0.9rem;
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

        {{-- ── Main Content ── --}}
        <div class="main">
          <div class="main__scroll scrollbar-macosx">
            <div class="main__cont">

              <div class="main-heading">
                <div class="main-title">
                  <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                    <li class="active">Kelola Informasi Umum</li>
                  </ol>
                </div>
                <div class="main-filter">
                  <button class="btn btn-primary" onclick="toggleAddForm()">
                    <i class="fa fa-plus"></i> Tambah Informasi
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
                      <h3 class="panel-title"><i class="fa fa-plus-circle"></i> Tambah Informasi Baru</h3>
                      <button type="button" class="btn btn-xs btn-default" onclick="toggleAddForm()">
                        <i class="fa fa-times"></i> Tutup
                      </button>
                    </div>
                    <div class="panel-body">
                      <form method="POST" action="{{ route('admin.informasi.store') }}">
                        @csrf
                        <div class="form-group">
                          <label class="control-label" for="add_judul">Judul Informasi</label>
                          <div class="tooltip-container">
                            <input id="add_judul" type="text" name="judul" class="form-control form-control-custom"
                                   placeholder="Judul info" value="{{ old('judul') }}" required autocomplete="off"
                                   oninput="updateTooltip(this)">
                            <span class="tooltiptext"></span>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label" for="add_deskripsi">Deskripsi</label>
                          <textarea id="add_deskripsi" name="deskripsi" class="form-control form-control-custom"
                                    rows="4" placeholder="Deskripsi informasi lengkap" required style="resize: vertical; min-height: 100px; background-color: #22263a; border-color: rgba(255,255,255,0.07); color: #e2e8f0;">{{ old('deskripsi') }}</textarea>
                        </div>
                        <div class="form-group" style="display:flex; align-items:center; gap:10px; margin-top: 15px; margin-bottom: 20px;">
                          <label class="toggle" for="add_Tampilkan">
                            <input type="checkbox" id="add_Tampilkan" name="tampilkan" value="1"
                                   {{ old('tampilkan', '1') ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                          </label>
                          <span style="color:#94a3b8; font-size:0.85rem;">Tampilkan di running text</span>
                        </div>
                        <button type="submit" class="btn btn-primary">
                          <i class="fa fa-check"></i> Simpan Informasi
                        </button>
                      </form>
                    </div>
                  </div>
                </div>

                {{-- Table --}}
                <div class="panel panel-default">
                  <div class="panel-heading" style="display:flex; align-items:center; justify-content:space-between;">
                    <h3 class="panel-title"><i class="fa fa-list"></i> Daftar Informasi Umum</h3>
                    <small class="section-count">Total: {{ $informasiList->count() }} records</small>
                  </div>
                  <div class="panel-body" style="padding:0;">
                    @if ($informasiList->isEmpty())
                      <div class="empty-state">
                        <i class="fa fa-info-circle" style="font-size:2.5rem; margin-bottom:1rem; display:block; opacity:0.3;"></i>
                        Belum ada data informasi umum. Tambahkan informasi pertama!
                      </div>
                    @else
                      <div class="table-responsive">
                        <table class="table table-hover" style="margin-bottom:0;">
                          <thead>
                            <tr>
                              <th style="width: 60px;">No.</th>
                              <th>Judul</th>
                              <th>Deskripsi</th>
                              <th style="text-align:center; width: 120px;">Tampil di JSON</th>
                              <th style="text-align:right; width: 180px;">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($informasiList as $index => $item)
                            <tr>
                              <td>{{ $index + 1 }}</td>
                              <td><strong>{{ $item->judul }}</strong></td>
                              <td><span style="color:#94a3b8; font-size:0.9em;">{{ $item->deskripsi }}</span></td>
                              <td style="text-align:center; vertical-align:middle;">
                                <form method="POST" action="{{ route('admin.informasi.toggle', $item->id) }}"
                                      class="toggle-form" id="toggleForm-{{ $item->id }}" style="display:inline;">
                                  @csrf
                                </form>
                                <label class="toggle"
                                       title="{{ $item->tampilkan ? 'Klik untuk sembunyikan' : 'Klik untuk tampilkan' }}">
                                  <input type="checkbox"
                                    {{ $item->tampilkan ? 'checked' : '' }}
                                    onchange="document.getElementById('toggleForm-{{ $item->id }}').submit()">
                                  <span class="toggle-slider"></span>
                                </label>
                              </td>
                              <td>
                                <div class="td-actions" style="justify-content:flex-end;">
                                  <button class="btn-kajian-outline"
                                    onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->judul) }}', '{{ addslashes($item->deskripsi) }}', '{{ $item->tampilkan ? 'true' : 'false' }}')">
                                    <i class="fa fa-pencil"></i> Edit
                                  </button>
                                  <button class="btn-kajian-danger"
                                    onclick="openDeleteModal({{ $item->id }}, '{{ addslashes($item->judul) }}')">
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

    {{-- ── Edit Modal ── --}}
    <div class="modal-backdrop-custom" id="editModalBackdrop" onclick="closeEditModal(event)">
      <div class="modal-card-custom">
        <h3 style="margin-top:0; margin-bottom:1.5rem; color:#e2e8f0; font-weight:600;">
          <i class="fa fa-pencil" style="color:var(--accent);"></i> Edit Informasi
        </h3>
        <form method="POST" id="editForm">
          @csrf
          <div class="form-group">
            <label class="control-label" for="edit_judul">Judul Informasi</label>
            <div class="tooltip-container">
              <input id="edit_judul" type="text" name="judul" class="form-control form-control-custom" required autocomplete="off"
                     oninput="updateTooltip(this)">
              <span class="tooltiptext">Judul info</span>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label" for="edit_deskripsi">Deskripsi</label>
            <textarea id="edit_deskripsi" name="deskripsi" class="form-control form-control-custom"
                      rows="5" required style="resize: vertical; min-height: 120px; background-color: #22263a; border-color: rgba(255,255,255,0.07); color: #e2e8f0;"></textarea>
          </div>
          <div class="form-group" style="display:flex; align-items:center; gap:10px; margin-top: 15px; margin-bottom: 20px;">
            <label class="toggle" for="edit_Tampilkan">
              <input type="checkbox" id="edit_Tampilkan" name="tampilkan" value="1">
              <span class="toggle-slider"></span>
            </label>
            <span style="color:#94a3b8; font-size:0.85rem;">Tampilkan di Json</span>
          </div>
          <div style="text-align:right; gap:10px; display:flex; justify-content:flex-end;">
            <button type="button" class="btn btn-default" onclick="closeEditModal(null)">Batal</button>
            <button type="submit" class="btn btn-primary">
              <i class="fa fa-save"></i> Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- ── Delete Modal ── --}}
    <div class="modal-backdrop-custom" id="deleteModalBackdrop" onclick="closeDeleteModal(event)">
      <div class="modal-card-custom" style="max-width:400px; text-align:center;">
        <i class="fa fa-exclamation-triangle" style="font-size:3rem; color:var(--red); margin-bottom:1rem; display:block;"></i>
        <h3 style="margin-top:0; margin-bottom:0.5rem; color:#e2e8f0; font-weight:600;">Hapus Informasi?</h3>
        <div id="deleteDesc" style="color:#94a3b8; margin-bottom:2rem; font-size:0.9em;">
          Informasi ini akan dihapus secara permanen.
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
        const form = document.getElementById('addForm');
        form.classList.toggle('open');
        if (form.classList.contains('open')) {
          form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      }

      function openEditModal(id, judul, deskripsi, tampilkan) {
        document.getElementById('editForm').action = `/admin_dashboard/informasi/${id}`;
        document.getElementById('edit_judul').value = judul;
        document.getElementById('edit_deskripsi').value = deskripsi;
        document.getElementById('edit_Tampilkan').checked = (tampilkan === 'true');
        document.getElementById('editModalBackdrop').classList.add('open');
      }

      function closeEditModal(e) {
        if (e === null || e.target === document.getElementById('editModalBackdrop')) {
          document.getElementById('editModalBackdrop').classList.remove('open');
        }
      }

      function openDeleteModal(id, judul) {
        document.getElementById('deleteForm').action = `/admin_dashboard/informasi/${id}/delete`;
        document.getElementById('deleteDesc').textContent =
          `"${judul}" akan dihapus secara permanen dari daftar informasi umum.`;
        document.getElementById('deleteModalBackdrop').classList.add('open');
      }

      function closeDeleteModal(e) {
        if (e === null || e.target === document.getElementById('deleteModalBackdrop')) {
          document.getElementById('deleteModalBackdrop').classList.remove('open');
        }
      }

      document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
          closeEditModal(null);
          closeDeleteModal(null);
        }
      });

      @if ($errors->any() && old('judul'))
      toggleAddForm();
      @endif
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
