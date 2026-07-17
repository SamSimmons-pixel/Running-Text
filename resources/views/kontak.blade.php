<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Kontak — Jadwal Kajian</title>
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
                    <div class="panel-heading" style="display:flex; align-items:center; justify-content:space-between;">
                      <h3 class="panel-title"><i class="fa fa-plus-circle"></i> Tambah Kontak Baru</h3>
                      <button type="button" class="btn btn-xs btn-danger" onclick="toggleAddForm()">
                        <i class="fa fa-times"></i> Tutup
                      </button>
                    </div>
                    <div class="panel-body">
                      <form method="POST" action="{{ route('admin.kontak.store') }}">
                        @csrf
                        <div class="row">
                          <div class="col-sm-5 col-md-5">
                            <div class="form-group">
                              <label class="control-label" for="kontak_nama">Nama</label>
                              <div class="tooltip-container">
                                <input id="kontak_nama" type="text" name="nama" class="form-control form-control-custom"
                                       placeholder="Contoh: Abu Ahmad" value="{{ old('nama') }}" required autocomplete="off"
                                       oninput="updateTooltip(this, 'Nama pemilik kontak')"
                                       onfocus="updateTooltip(this, 'Nama pemilik kontak')"
                                       onblur="hideTooltip(this)">
                                <span class="tooltiptext">Nama pemilik kontak</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-5 col-md-5">
                            <div class="form-group">
                              <label class="control-label" for="kontak_nomor">Nomor Kontak (HP / WA)</label>
                              <div class="tooltip-container">
                                <input id="kontak_nomor" type="text" name="nomor_kontak" class="form-control form-control-custom"
                                       placeholder="Contoh: 0812-3456-7890" value="{{ old('nomor_kontak') }}" required autocomplete="off"
                                       oninput="updateTooltip(this, 'Nomor HP atau WA')"
                                       onfocus="updateTooltip(this, 'Nomor HP atau WA')"
                                       onblur="hideTooltip(this)">
                                <span class="tooltiptext">Nomor HP atau WA</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-2 col-md-2" style="margin-top: 25px;">
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
                  <div class="panel-heading" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
                    <h3 class="panel-title" style="margin:0;"><i class="fa fa-list"></i> Daftar Kontak <small style="margin-left:8px; color:rgba(255,255,255,0.4);" class="section-count">Total: {{ $kontakList->count() }} kontak</small></h3>
                    <div style="display:flex; align-items:center; gap:8px; flex-wrap:nowrap;">
                      <input type="search" id="kontakSearchInput" placeholder="Cari kontak..." class="form-control form-control-custom" style="width:200px; padding:6px 12px; height:34px; margin:0;" onkeyup="filterKontakTable()">
                      <button class="btn btn-primary" onclick="filterKontakTable()" style="padding:6px 15px; height:34px; line-height:20px; font-size:1.15rem; margin:0;"><i class="fa fa-search"></i> Cari</button>
                    </div>
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
                              <th>Nama Pemilik</th>
                              <th>Nomor Kontak (HP / WA)</th>
                              <th style="text-align:right; width: 220px;">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($kontakList as $index => $item)
                            <tr id="mainRow-{{ $item->id }}" class="searchable-row">
                              <td>{{ $index + 1 }}</td>
                              <td><strong>{{ $item->nama }}</strong></td>
                              <td><span style="color:#94a3b8;">{{ $item->nomor_kontak }}</span></td>
                              <td>
                                <div class="td-actions" style="justify-content:flex-end; gap:8px;">
                                  <button class="btn-kajian-outline" style="background-color: rgba(56, 189, 248, 0.15) !important; color: #38bdf8 !important; border-color: rgba(56, 189, 248, 0.3) !important;"
                                    onclick="toggleInfoRow({{ $item->id }})">
                                    <i class="fa fa-info-circle"></i> Info
                                  </button>
                                  <button class="btn-kajian-danger"
                                     onclick="openDeleteModal(
                                       {{ $item->id }},
                                       '{{ addslashes($item->nama) }} ({{ addslashes($item->nomor_kontak) }})',
                                       {{ json_encode($item->kajian->map(fn($k) => $k->Judul)->values()) }}
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

    {{-- Delete Modal --}}
    <div class="modal-backdrop-custom" id="deleteModalBackdrop" onclick="closeDeleteModal(event)">
      <div class="modal-confirm">
        <div style="font-size:2.5rem; margin-bottom:1rem;">🗑️</div>
        <div style="font-size:1rem; font-weight:700; margin-bottom:0.5rem; color:#e2e8f0;">Hapus Kontak?</div>
        <div style="font-size:0.85rem; color:#64748b; margin-bottom:0.75rem;" id="deleteDesc"></div>
        <div id="deleteUsageWarning" style="display:none; margin-bottom:1rem; background:rgba(234,179,8,0.1); border:1px solid rgba(234,179,8,0.3); border-radius:8px; padding:0.75rem 1rem; text-align:left;">
          <div style="font-size:0.8rem; font-weight:700; color:#fbbf24; margin-bottom:0.5rem;">
            <i class="fa fa-exclamation-triangle"></i> Data ini masih digunakan oleh:
          </div>
          <div id="deleteUsageList" style="font-size:0.78rem; color:#cbd5e1; max-height:120px; overflow-y:auto;"></div>
          <div style="font-size:0.75rem; color:#94a3b8; margin-top:0.5rem;">
            Setelah dihapus, Kajian terkait akan menampilkan "—" untuk Kontak.
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

      function filterKontakTable() {
        var input = document.getElementById('kontakSearchInput');
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

      @if ($errors->any() && (old('nama') || old('nomor_kontak')))
      toggleAddForm();
      @endif

      function openDeleteModal(id, nama, kajianUsage) {
        document.getElementById('deleteForm').action = `/admin_dashboard/kontak/${id}/delete`;
        document.getElementById('deleteDesc').textContent =
          `"${nama}" akan dihapus secara permanen dari daftar kontak.`;

        var usageItems = [];
        if (kajianUsage && kajianUsage.length > 0) {
          kajianUsage.forEach(function(judul) {
            usageItems.push('<div style="padding:2px 0;"><i class="fa fa-calendar" style="color:#38bdf8; margin-right:5px;"></i><strong>Kajian:</strong> ' + judul + '</div>');
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
