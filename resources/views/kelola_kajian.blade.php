<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Kajian — Jadwal Kajian</title>
    <link rel="icon" type="image/png" href="{{ asset('admin-template/img/favicon.png') }}">
    @include('partials.assets')
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
          <div class="header-navbar-mobile__title"><span>Kelola Kajian</span></div>
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
                                       {{ old('Tampilkan') ? '' : 'checked' }}>
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
                              <th>Tanggal & Jam Mulai</th>
                              <th style="text-align:center;">Status</th>
                              <th>Tempat</th>
                              <th>Kontak</th>
                              <th style="text-align:center;">Tampil Di Json</th>
                              <th style="text-align:right;">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($kajian as $item)
                            <tr>
                              <td>
                                <strong>{{ $item->Judul }}</strong>
                                <small style="display:block; color:#D0DDF2; font-size:1.1rem; margin-top:2px;">
                                  {{ $item->Narasumber }}
                                </small>
                              </td>
                              <td style="white-space:nowrap; color:#94a3b8;">
                                {{ \Carbon\Carbon::parse($item->Tanggal)->locale('id')->isoFormat('ddd, D MMM Y') }}
                                <br>
                                <small>{{ \Carbon\Carbon::parse($item->Tanggal)->format('H:i') }}</small>
                              </td>
                              <td style="text-align:center; vertical-align:middle;">
                                @if(\Carbon\Carbon::parse($item->Tanggal)->isPast())
                                  <span class="label label-danger" style="border-radius: 99px; padding: 3px 8px; font-size: 1.25rem; background-color: rgba(239, 68, 68, 0.15) !important; color: #fb7185 !important; border: 1px solid rgba(239, 68, 68, 0.3) !important;">terlewat</span>
                                @else
                                  <span class="label label-success" style="border-radius: 99px; padding: 3px 8px; font-size: 1.25rem; background-color: rgba(34, 197, 94, 0.15) !important; color: #34d399 !important; border: 1px solid rgba(34, 197, 94, 0.3) !important;">terjadwal</span>
                                @endif
                              </td>
                              <td style="color:#94a3b8;">{{ $item->Tempat }}</td>
                              <td style="color:#94a3b8;">{{ $item->Kontak ?: '—' }}</td>
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
          Kajian ini akan dihapus secara permanen.
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

      function openEditModal(id, judul, narasumber, tanggal, tempat, kontak, tampilkan) {
        document.getElementById('editForm').action = `/admin_dashboard/${id}`;
        document.getElementById('edit_Judul').value       = judul;
        document.getElementById('edit_Narasumber').value  = narasumber;
        document.getElementById('edit_Tanggal').value     = tanggal;
        document.getElementById('edit_Tempat').value      = tempat;
        document.getElementById('edit_Kontak').value      = kontak;
        document.getElementById('edit_Tampilkan').checked = (tampilkan === 'true');
        document.getElementById('editModalBackdrop').classList.add('open');
      }

      function closeEditModal(e) {
        if (e === null || e.target === document.getElementById('editModalBackdrop')) {
          document.getElementById('editModalBackdrop').classList.remove('open');
        }
      }

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

      document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
          closeEditModal(null);
          closeDeleteModal(null);
        }
      });

      @if ($errors->any() && old('Judul'))
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
