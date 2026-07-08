<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Acara — Jadwal Kajian</title>
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
                    <li class="active">Kelola Acara</li>
                  </ol>
                </div>
                <div class="main-filter">
                  <button class="btn btn-primary" onclick="toggleAddForm()">
                    <i class="fa fa-plus"></i> Tambah Acara
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

                {{-- ── Add Acara Form (collapsible) ── --}}
                <div class="form-card-kajian" id="addForm">
                  <div class="panel panel-primary">
                    <div class="panel-heading" style="display:flex; align-items:center; justify-content:space-between;">
                      <h3 class="panel-title"><i class="fa fa-plus-circle"></i> Tambah Acara Baru</h3>
                      <button type="button" class="btn btn-xs btn-default" onclick="toggleAddForm()">
                        <i class="fa fa-times"></i> Tutup
                      </button>
                    </div>
                    <div class="panel-body">
                      <form method="POST" action="{{ route('admin.acara.store') }}">
                        @csrf
                        <div class="row">
                          <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                              <label class="control-label">Judul Acara</label>
                              <div class="tooltip-container">
                                <input type="text" name="judul" class="form-control form-control-custom"
                                       placeholder="Nama acara" value="{{ old('judul') }}" required autocomplete="off"
                                       oninput="updateTooltip(this, 'Nama acara')"
                                       onfocus="updateTooltip(this, 'Nama acara')"
                                       onblur="hideTooltip(this)">
                                <span class="tooltiptext">Nama acara</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                              <label class="control-label">Hari</label>
                              <select name="hari" class="form-control form-control-custom" required>
                                <option value="" disabled selected>Pilih Hari</option>
                                @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Ahad'] as $h)
                                  <option value="{{ $h }}" {{ old('hari') == $h ? 'selected' : '' }}>{{ $h }}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="col-sm-6 col-md-2">
                            <div class="form-group">
                              <label class="control-label">Jam Mulai</label>
                              <input type="time" name="jam_mulai" class="form-control form-control-custom"
                                     value="{{ old('jam_mulai') }}" required>
                            </div>
                          </div>
                          <div class="col-sm-6 col-md-2">
                            <div class="form-group">
                              <label class="control-label">Jam Selesai</label>
                              <input type="time" name="jam_selesai" class="form-control form-control-custom"
                                     value="{{ old('jam_selesai') }}" required>
                            </div>
                          </div>
                          <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                              <label class="control-label">Narasumber</label>
                              <select name="narasumber" class="form-control form-control-custom" required>
                                <option value="" disabled selected>Pilih Narasumber</option>
                                @foreach($narasumberList as $nara)
                                  <option value="{{ $nara->nama }}" {{ old('narasumber') == $nara->nama ? 'selected' : '' }}>
                                    {{ $nara->nama }}
                                  </option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                              <label class="control-label">Tempat</label>
                              <select name="tempat" class="form-control form-control-custom" required>
                                <option value="" disabled selected>Pilih Tempat</option>
                                @foreach($tempatList as $temp)
                                  <option value="{{ $temp->nama }}" {{ old('tempat') == $temp->nama ? 'selected' : '' }}>
                                    {{ $temp->nama }}
                                  </option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                              <label class="control-label">Status <small style="color:#64748b;">(opsional)</small></label>
                              <div class="tooltip-container">
                                <input type="text" name="status" class="form-control form-control-custom"
                                       placeholder="-" value="{{ old('status') }}" autocomplete="off"
                                       oninput="updateTooltip(this)"
                                       onfocus="updateTooltip(this)"
                                       onblur="hideTooltip(this)">
                                <span class="tooltiptext"></span>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div style="display:flex; justify-content:flex-end; gap:0.75rem; margin-top:0.5rem;">
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

                {{-- ── Acara Table ── --}}
                <div class="panel panel-default">
                  <div class="panel-heading" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.5rem;">
                    <h3 class="panel-title"><i class="fa fa-play"></i> Daftar Acara</h3>
                    <small class="section-count">Total: {{ $acara->count() }} acara</small>
                  </div>
                  <div class="panel-body" style="padding:0;">
                    @if ($acara->isEmpty())
                      <div class="empty-state">
                        <i class="fa fa-calendar-o" style="font-size:2.5rem; margin-bottom:1rem; display:block; opacity:0.3;"></i>
                        Belum ada data acara. Tambahkan acara pertama!
                      </div>
                    @else
                      <div class="table-responsive">
                        <table class="table table-hover" style="margin-bottom:0;">
                          <thead>
                            <tr>
                              <th>Judul &amp; Narasumber</th>
                              <th>Hari</th>
                              <th>Jam</th>
                              <th>Tempat</th>
                              <th style="text-align:center;">Status</th>
                              <th style="text-align:center;">Tampil Di Json</th>
                              <th style="text-align:right;">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
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
                            @foreach ($acara as $item)
                            @php
                              $isOnAir = ($item->hari === $hariIni)
                                      && ($jamNow >= $item->jam_mulai)
                                      && ($jamNow <= $item->jam_selesai);
                            @endphp
                            <tr class="{{ $isOnAir ? 'row-on-air' : '' }}">
                              <td>
                                <strong>{{ $item->judul }}</strong>
                                <small style="display:block; color:#D0DDF2; font-size:1.1rem; margin-top:2px;">
                                  {{ $item->narasumber }}
                                </small>
                              </td>
                              <td style="color:#94a3b8; white-space:nowrap;">{{ $item->hari }}</td>
                              <td style="color:#94a3b8; white-space:nowrap;">
                                {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}
                                <span style="opacity:0.5;">–</span>
                                {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}
                              </td>
                              <td style="color:#94a3b8;">{{ $item->tempat }}</td>
                              <td style="text-align:center; vertical-align:middle;">
                                @if($isOnAir)
                                  <span class="badge-live">LIVE</span>
                                @elseif($item->status)
                                  <span class="label label-info" style="border-radius:99px; padding:3px 8px; font-size:1.1rem; background-color:rgba(99,179,237,0.15)!important; color:#63b3ed!important; border:1px solid rgba(99,179,237,0.3)!important;">
                                    {{ $item->status }}
                                  </span>
                                @else
                                  <span style="color:#475569; font-size:0.85rem;">—</span>
                                @endif
                              </td>
                              <td style="text-align:center; vertical-align:middle;">
                                <form method="POST" action="{{ route('admin.acara.toggle', $item->id) }}"
                                      class="toggle-form" id="toggleForm-{{ $item->id }}">
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
                                    onclick="openEditModal(
                                      '{{ $item->id }}',
                                      '{{ addslashes($item->judul) }}',
                                      '{{ addslashes($item->hari) }}',
                                      '{{ $item->jam_mulai }}',
                                      '{{ $item->jam_selesai }}',
                                      '{{ addslashes($item->narasumber) }}',
                                      '{{ addslashes($item->tempat) }}',
                                      '{{ addslashes($item->status ?? '') }}'
                                    )"> 
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
          <div class="modal-title-custom"><i class="fa fa-pencil-square-o"></i> Edit Acara</div>
          <button class="btn-close-modal" onclick="closeEditModal(null)">✕</button>
        </div>
        <form method="POST" id="editForm">
          @csrf
          <!-- Row 1: Judul Acara & Hari -->
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label">Judul Acara</label>
                <div class="tooltip-container">
                  <input id="edit_judul" type="text" name="judul" class="form-control form-control-custom" required autocomplete="off"
                         oninput="updateTooltip(this, 'Nama acara')"
                         onfocus="updateTooltip(this, 'Nama acara')"
                         onblur="hideTooltip(this)">
                  <span class="tooltiptext">Nama acara</span>
                </div>
              </div>
            </div>
            
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label">Hari</label>
                <select id="edit_hari" name="hari" class="form-control form-control-custom" required>
                  <option value="" disabled>Pilih Hari</option>
                  @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Ahad'] as $h)
                    <option value="{{ $h }}">{{ $h }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <!-- Row 2: Jam Mulai & Jam Selesai -->
          <div class="row" style="margin-top: 1rem;">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label">Jam Mulai</label>
                <input id="edit_jam_mulai" type="time" name="jam_mulai" class="form-control form-control-custom" required>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label">Jam Selesai</label>
                <input id="edit_jam_selesai" type="time" name="jam_selesai" class="form-control form-control-custom" required>
              </div>
            </div>
          </div>

          <!-- Row 3: Tempat & Narasumber -->
          <div class="row" style="margin-top: 1rem;">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label">Tempat</label>
                <select id="edit_tempat" name="tempat" class="form-control form-control-custom" required>
                  <option value="" disabled>Pilih Tempat</option>
                  @foreach($tempatList as $temp)
                    <option value="{{ $temp->nama }}">{{ $temp->nama }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label">Narasumber</label>
                <select id="edit_narasumber" name="narasumber" class="form-control form-control-custom" required>
                  <option value="" disabled>Pilih Narasumber</option>
                  @foreach($narasumberList as $nara)
                    <option value="{{ $nara->nama }}">{{ $nara->nama }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <!-- Row 4: Status (opsional) -->
          <div class="row" style="margin-top: 1rem; margin-bottom: 1rem;">
            <div class="col-sm-12">
              <div class="form-group">
                <label class="control-label">Status <small style="color:#64748b;">(opsional)</small></label>
                <div class="tooltip-container">
                  <input id="edit_status" type="text" name="status" class="form-control form-control-custom"
                         placeholder="Contoh: Aktif, Libur, dll." autocomplete="off"
                         oninput="updateTooltip(this, 'Status (Contoh: Aktif, Libur)')"
                         onfocus="updateTooltip(this, 'Status (Contoh: Aktif, Libur)')"
                         onblur="hideTooltip(this)">
                  <span class="tooltiptext">Status (Contoh: Aktif, Libur)</span>
                </div>
              </div>
            </div>
          </div>

          <div style="display:flex; justify-content:flex-end; gap:0.75rem; margin-top:0.5rem;">
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
        <div style="font-size:1rem; font-weight:700; margin-bottom:0.5rem; color:#e2e8f0;">Hapus Acara?</div>
        <div style="font-size:0.85rem; color:#64748b; margin-bottom:1.5rem;" id="deleteDesc">
          Acara ini akan dihapus secara permanen.
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

      function openEditModal(id, judul, hari, jamMulai, jamSelesai, narasumber, tempat, status) {
        document.getElementById('editForm').action = `/admin_dashboard/acara/${id}`;
        document.getElementById('edit_judul').value       = judul;
        document.getElementById('edit_hari').value        = hari;
        // jam_mulai and jam_selesai from DB come as "HH:MM:SS" — trim to "HH:MM" for time input
        document.getElementById('edit_jam_mulai').value   = jamMulai ? jamMulai.substring(0, 5) : '';
        document.getElementById('edit_jam_selesai').value = jamSelesai ? jamSelesai.substring(0, 5) : '';
        document.getElementById('edit_narasumber').value  = narasumber;
        document.getElementById('edit_tempat').value      = tempat;
        document.getElementById('edit_status').value      = status;
        document.getElementById('editModalBackdrop').classList.add('open');
      }

      function closeEditModal(e) {
        if (e === null || e.target === document.getElementById('editModalBackdrop')) {
          document.getElementById('editModalBackdrop').classList.remove('open');
        }
      }

      function openDeleteModal(id, judul) {
        document.getElementById('deleteForm').action = `/admin_dashboard/acara/${id}/delete`;
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
