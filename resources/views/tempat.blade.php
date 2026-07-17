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
                      <button type="button" class="btn btn-xs btn-danger" onclick="toggleAddForm()">
                        <i class="fa fa-times"></i> Tutup
                      </button>
                    </div>
                    <div class="panel-body">
                      <form method="POST" action="{{ route('admin.tempat.store') }}">
                        @csrf
                        <div class="form-group">
                          <label class="control-label" for="tempat_nama">Nama Masjid / Tempat Kegiatan</label>
                          <div class="tooltip-container">
                            <input type="text" id="tempat_nama" name="nama" class="form-control form-control-custom" placeholder="Isi Lokasi Disini" value="{{ old('nama') }}" required autocomplete="off"
                                    oninput="updateTooltip(this, 'Nama masjid/lokasi kegiatan')"
                                    onfocus="updateTooltip(this, 'Nama masjid/lokasi kegiatan')"
                                    onblur="hideTooltip(this)">
                            <span class="tooltiptext">Nama masjid/lokasi kegiatan</span>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label" for="tempat_deskripsi">Deskripsi Alamat / Detail Alamat</label>
                          <div class="tooltip-container">
                            <textarea id="tempat_deskripsi" name="deskripsi_alamat" class="form-control form-control-custom" placeholder="Isi Detail Alamat Disini (Misal: Jl. Raya No. 12, Lantai 2)" rows="3" autocomplete="off"
                                      oninput="updateTooltip(this, 'Detail alamat tempat kegiatan')"
                                      onfocus="updateTooltip(this, 'Detail alamat tempat kegiatan')"
                                      onblur="hideTooltip(this)">{{ old('deskripsi_alamat') }}</textarea>
                            <span class="tooltiptext">Detail alamat tempat kegiatan</span>
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
                  <div class="panel-heading" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
                    <h3 class="panel-title" style="margin:0;"><i class="fa fa-list"></i> Daftar Tempat <small style="margin-left:8px; color:rgba(255,255,255,0.4);" class="section-count">Total: {{ $tempatList->count() }} tempat</small></h3>
                    <div style="display:flex; align-items:center; gap:8px; flex-wrap:nowrap;">
                      <input type="search" id="tempatSearchInput" placeholder="Cari tempat..." class="form-control form-control-custom" style="width:200px; padding:6px 12px; height:34px; margin:0;" onkeyup="filterTempatTable()">
                      <button class="btn btn-primary" onclick="filterTempatTable()" style="padding:6px 15px; height:34px; line-height:20px; font-size:1.15rem; margin:0;"><i class="fa fa-search"></i> Cari</button>
                    </div>
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
                              <th>Deskripsi Alamat</th>
                              <th style="text-align:right; width: 280px;">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($tempatList as $index => $item)
                            <tr id="mainRow-{{ $item->id }}" class="searchable-row">
                              <td>{{ $index + 1 }}</td>
                              <td><strong>{{ $item->nama }}</strong></td>
                              <td><span style="color:#94a3b8; font-size:0.95em;">{{ $item->deskripsi_alamat ?: '—' }}</span></td>
                              <td>
                                <div class="td-actions" style="justify-content:flex-end; gap:8px;">
                                  <button class="btn-kajian-outline" style="background-color: rgba(56, 189, 248, 0.15) !important; color: #38bdf8 !important; border-color: rgba(56, 189, 248, 0.3) !important;"
                                    onclick="toggleInfoRow({{ $item->id }})">
                                    <i class="fa fa-info-circle"></i> Info
                                  </button>
                                  <button class="btn-kajian-outline"
                                    onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->nama) }}', '{{ str_replace(["\r", "\n"], ["\\r", "\\n"], addslashes($item->deskripsi_alamat ?? '')) }}')">
                                    <i class="fa fa-pencil"></i> Edit
                                  </button>
                                  <button class="btn-kajian-danger"
                                    onclick="openDeleteModal(
                                      {{ $item->id }},
                                      '{{ addslashes($item->nama) }}',
                                      {{ json_encode($item->kajian->map(fn($k) => $k->Judul)->values()) }},
                                      {{ json_encode($item->acara->map(fn($a) => $a->judul)->values()) }}
                                    )">
                                    <i class="fa fa-trash"></i> Hapus
                                  </button>
                                </div>
                              </td>
                            </tr>
                            <tr id="rowInfo-{{ $item->id }}" style="display: none; background-color: rgba(15, 23, 42, 0.25);">
                              <td colspan="4" style="padding: 1.25rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.05); text-align: left;">
                                @include('partials.metadata')
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

    {{-- Edit Modal --}}
    <div class="modal-backdrop-custom" id="editModalBackdrop" onclick="closeEditModal(event)">
      <div class="modal-card-custom" style="max-width: 450px; text-align: left;">
        <h3 style="margin-top:0; margin-bottom:1.5rem; color:#e2e8f0; font-weight:600;">
          <i class="fa fa-pencil" style="color:var(--accent);"></i> Edit Tempat
        </h3>
        <form method="POST" id="editForm">
          @csrf
          <div class="form-group">
            <label class="control-label" for="edit_nama">Nama Masjid / Tempat Kegiatan</label>
            <div class="tooltip-container">
              <input id="edit_nama" type="text" name="nama" class="form-control form-control-custom" required autocomplete="off"
                     oninput="updateTooltip(this)">
              <span class="tooltiptext">Nama tempat kegiatan</span>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label" for="edit_deskripsi">Deskripsi Alamat / Detail Alamat</label>
            <div class="tooltip-container">
              <textarea id="edit_deskripsi" name="deskripsi_alamat" class="form-control form-control-custom" rows="3" autocomplete="off"
                        oninput="updateTooltip(this)"></textarea>
              <span class="tooltiptext">Detail alamat tempat kegiatan</span>
            </div>
          </div>
          <div style="display:flex; justify-content:flex-end; gap:1rem; margin-top: 1.5rem;">
            <button type="button" class="btn btn-default" onclick="closeEditModal(null)">Batal</button>
            <button type="submit" class="btn btn-primary">
              <i class="fa fa-check"></i> Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal-backdrop-custom" id="deleteModalBackdrop" onclick="closeDeleteModal(event)">
      <div class="modal-confirm">
        <div style="font-size:2.5rem; margin-bottom:1rem;">🗑️</div>
        <div style="font-size:1rem; font-weight:700; margin-bottom:0.5rem; color:#e2e8f0;">Hapus Tempat?</div>
        <div style="font-size:0.85rem; color:#64748b; margin-bottom:0.75rem;" id="deleteDesc"></div>
        <div id="deleteUsageWarning" style="display:none; margin-bottom:1rem; background:rgba(234,179,8,0.1); border:1px solid rgba(234,179,8,0.3); border-radius:8px; padding:0.75rem 1rem; text-align:left;">
          <div style="font-size:0.8rem; font-weight:700; color:#fbbf24; margin-bottom:0.5rem;">
            <i class="fa fa-exclamation-triangle"></i> Data ini masih digunakan oleh:
          </div>
          <div id="deleteUsageList" style="font-size:0.78rem; color:#cbd5e1; max-height:120px; overflow-y:auto;"></div>
          <div style="font-size:0.75rem; color:#94a3b8; margin-top:0.5rem;">
            Setelah dihapus, Kajian/Acara terkait akan menampilkan "—" untuk Tempat.
          </div>
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

      function filterTempatTable() {
        var input = document.getElementById('tempatSearchInput');
        var filter = input.value.toLowerCase();
        var mainRows = document.getElementsByClassName('searchable-row');
        
        for (var i = 0; i < mainRows.length; i++) {
          var row = mainRows[i];
          var text = row.innerText.toLowerCase();
          
          if (text.indexOf(filter) > -1) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        }
      }

      @if ($errors->any() && old('nama'))
      toggleAddForm();
      @endif

      function openEditModal(id, nama, deskripsi) {
        document.getElementById('editForm').action = `/admin_dashboard/tempat/${id}`;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_deskripsi').value = deskripsi;
        document.getElementById('editModalBackdrop').classList.add('open');
      }

      function closeEditModal(e) {
        if (e === null || e.target === document.getElementById('editModalBackdrop')) {
          document.getElementById('editModalBackdrop').classList.remove('open');
        }
      }

      function openDeleteModal(id, nama, kajianUsage, acaraUsage) {
        document.getElementById('deleteForm').action = `/admin_dashboard/tempat/${id}/delete`;
        document.getElementById('deleteDesc').textContent =
          `"${nama}" akan dihapus secara permanen dari daftar tempat.`;

        var usageItems = [];
        if (kajianUsage && kajianUsage.length > 0) {
          kajianUsage.forEach(function(judul) {
            usageItems.push('<div style="padding:2px 0;"><i class="fa fa-calendar" style="color:#38bdf8; margin-right:5px;"></i><strong>Kajian:</strong> ' + judul + '</div>');
          });
        }
        if (acaraUsage && acaraUsage.length > 0) {
          acaraUsage.forEach(function(judul) {
            usageItems.push('<div style="padding:2px 0;"><i class="fa fa-play" style="color:#a78bfa; margin-right:5px;"></i><strong>Acara:</strong> ' + judul + '</div>');
          });
        }

        var warningEl = document.getElementById('deleteUsageWarning');
        var listEl = document.getElementById('deleteUsageList');
        if (usageItems.length > 0) {
          listEl.innerHTML = usageItems.join('');
          warningEl.style.display = 'block';
        } else {
          warningEl.style.display = 'none';
        }

        document.getElementById('deleteModalBackdrop').classList.add('open');
      }

      function closeDeleteModal(e) {
        if (e === null || e.target === document.getElementById('deleteModalBackdrop')) {
          document.getElementById('deleteModalBackdrop').classList.remove('open');
        }
      }

      function toggleInfoRow(id) {
        var row = document.getElementById('rowInfo-' + id);
        if (row.style.display === 'none') {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      }

      document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
          closeEditModal(null);
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
