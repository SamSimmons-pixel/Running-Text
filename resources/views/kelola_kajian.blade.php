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
                      <button type="button" class="btn btn-xs btn-danger" onclick="toggleAddForm()">
                        <i class="fa fa-times"></i> Tutup
                      </button>
                    </div>
                    <div class="panel-body">
                      <form method="POST" action="{{ route('admin.kajian.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row" style="display: flex; flex-wrap: wrap;">
                          <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                              <label class="control-label">Judul Kajian</label>
                              <div class="tooltip-container">
                                <input type="text" name="Judul" class="form-control form-control-custom"
                                       placeholder="Nama kajian" value="{{ old('Judul') }}" required autocomplete="off"
                                       oninput="updateTooltip(this, 'Nama kajian')"
                                       onfocus="updateTooltip(this, 'Nama kajian')"
                                       onblur="hideTooltip(this)">
                                <span class="tooltiptext">Nama kajian</span>
                              </div>
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
                              <label class="control-label">Tanggal &amp; Waktu Mulai Kajian</label>
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
                          <div class="col-sm-6 col-md-4" style="">
                            <div class="form-group">
                              <div class="" style="display:flex; justify-content:space-between;">
                                <label class="control-label">Waktu Selesai Kajian</label>
                                <div style="display: flex; flex-direction:row; gap: 0.75rem; margin-bottom: 0.5rem;">
                                  <label class="radio-inline" style="color: #94a3b8; font-size: 1.2rem; padding-left: 0; margin-left: 0; display: flex; align-items: center; gap: 0.4rem; cursor: pointer;">
                                  <input type="radio" name="add_waktu_selesai_mode" value="text" checked onchange="toggleWaktuSelesaiMode('add', this.value)" style="position: static; margin-left: 0; cursor: pointer;"> Teks Pilihan
                                </label>
                                <label class="radio-inline" style="color: #94a3b8; font-size: 1.2rem; padding-left: 0; margin-left: 0; display: flex; align-items: center; gap: 0.4rem; cursor: pointer;">
                                  <input type="radio" name="add_waktu_selesai_mode" value="time" onchange="toggleWaktuSelesaiMode('add', this.value)" style="position: static; margin-left: 0; cursor: pointer;"> Jam Presisi
                                </label>
                              </div>
                              </div>
                              
                              {{-- Mode 1: Text Options --}}
                              <div id="add_waktu_selesai_text_wrapper">
                                <select name="WaktuSelesai" id="add_waktu_selesai_text" class="form-control form-control-custom">
                                  <option value="" selected>— Pilih Waktu Selesai —</option>
                                  <option value="Menjelang Dzuhur">Menjelang Dzuhur</option>
                                  <option value="Menjelang Ashar">Menjelang Ashar</option>
                                  <option value="Menjelang Maghrib">Menjelang Maghrib</option>
                                  <option value="Menjelang Isya">Menjelang Isya</option>
                                </select>
                              </div>

                              {{-- Mode 2: Time clock --}}
                              <div id="add_waktu_selesai_time_wrapper" style="display: none;">
                                <input type="time" id="add_waktu_selesai_time" class="form-control form-control-custom">
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-12" style="margin-top: 0.5rem;">
                            <div class="form-group">
                              <label class="control-label">Informasi</label>
                              <textarea name="Informasi" class="form-control form-control-custom" rows="3" placeholder="Informasi tambahan kajian (opsional)...">{{ old('Informasi') }}</textarea>
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
                             @php
                              $kajianStart = \Carbon\Carbon::parse($item->Tanggal, 'Asia/Jakarta');
                              $kajianEnd   = $kajianStart->copy()->addHour();
                              $isOnAir     = now('Asia/Jakarta')->between($kajianStart, $kajianEnd);
                             @endphp
                             <tr class="{{ $isOnAir ? 'row-on-air' : '' }}">
                               <td>
                                 <strong>{{ $item->Judul }}</strong>
                                 <small style="display:block; color:#D0DDF2; font-size:1.1rem; margin-top:2px;">
                                   {{ $item->Narasumber }}
                                 </small>
                               </td>
                               <td style="white-space:nowrap; color:#94a3b8;">
                                 {{ \Carbon\Carbon::parse($item->Tanggal)->locale('id')->isoFormat('ddd, D MMM Y') }}
                                 <br>
                                 <small>
                                   {{ \Carbon\Carbon::parse($item->Tanggal)->format('H:i') }}
                                   @if($item->WaktuSelesai)
                                     – {{ $item->WaktuSelesai }}
                                   @endif
                                 </small>
                               </td>
                               <td style="text-align:center; vertical-align:middle;">
                                 @if($isOnAir)
                                   <span class="badge-live">LIVE</span>
                                 @elseif(\Carbon\Carbon::parse($item->Tanggal)->isPast())
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
                                   <button class="btn-kajian-outline" style="background-color: rgba(56, 189, 248, 0.15) !important; color: #38bdf8 !important; border-color: rgba(56, 189, 248, 0.3) !important;"
                                     onclick="toggleInfoRow({{ $item->id }})">
                                     <i class="fa fa-info-circle"></i> Info
                                   </button>
                                  <button class="btn-kajian-outline"
                                    onclick="openEditModal(
                                      '{{ $item->id }}',
                                      '{{ addslashes($item->Judul) }}',
                                      '{{ addslashes($item->Narasumber) }}',
                                      '{{ \Carbon\Carbon::parse($item->Tanggal)->format('Y-m-d\TH:i') }}',
                                      '{{ addslashes($item->Tempat) }}',
                                      '{{ addslashes($item->Kontak ?? '') }}',
                                      '{{ addslashes($item->Informasi ?? '') }}',
                                      '{{ addslashes($item->WaktuSelesai ?? '') }}',
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
                            <tr id="rowInfo-{{ $item->id }}" style="display: none; background-color: rgba(15, 23, 42, 0.25);">
                              <td colspan="7" style="padding: 1.25rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.05); text-align: left;">
                                <div style="font-weight: 600; color: #94a3b8; margin-bottom: 0.5rem; font-size: 1.15rem;">
                                  <i class="fa fa-info-circle" style="color: #38bdf8; margin-right: 0.25rem;"></i> Informasi Kajian
                                </div>
                                <div style="color: #cbd5e1; font-size: 1.15rem; line-height: 1.6; white-space: pre-line; padding-left: 1.25rem;">
                                  @if($item->Informasi)
                                    {{ $item->Informasi }}
                                  @else
                                    <em style="color: #64748b; font-style: italic;">Tidak ada Informasi</em>
                                  @endif
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
          <div class="row" style="display: flex; flex-wrap: wrap;">
            <div class="col-sm-12">
              <div class="form-group">
                <label class="control-label">Judul Kajian</label>
                <div class="tooltip-container">
                  <input id="edit_Judul" type="text" name="Judul" class="form-control form-control-custom" required autocomplete="off"
                         oninput="updateTooltip(this, 'Nama kajian')"
                         onfocus="updateTooltip(this, 'Nama kajian')"
                         onblur="hideTooltip(this)">
                  <span class="tooltiptext">Nama kajian</span>
                </div>
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
                <label class="control-label">Waktu Selesai Kajian</label>
                <div style="display: flex; gap: 0.75rem; margin-bottom: 0.5rem;">
                  <label class="radio-inline" style="color: #94a3b8; font-size: 1.2rem; padding-left: 0; margin-left: 0; display: flex; align-items: center; gap: 0.4rem; cursor: pointer;">
                    <input type="radio" id="edit_mode_text" name="edit_waktu_selesai_mode" value="text" checked onchange="toggleWaktuSelesaiMode('edit', this.value)" style="position: static; margin-left: 0; cursor: pointer;"> Teks Pilihan
                  </label>
                  <label class="radio-inline" style="color: #94a3b8; font-size: 1.2rem; padding-left: 0; margin-left: 0; display: flex; align-items: center; gap: 0.4rem; cursor: pointer;">
                    <input type="radio" id="edit_mode_time" name="edit_waktu_selesai_mode" value="time" onchange="toggleWaktuSelesaiMode('edit', this.value)" style="position: static; margin-left: 0; cursor: pointer;"> Jam Presisi
                  </label>
                </div>
                
                {{-- Mode 1: Text Options --}}
                <div id="edit_waktu_selesai_text_wrapper">
                  <select name="WaktuSelesai" id="edit_waktu_selesai_text" class="form-control form-control-custom">
                    <option value="">— Pilih Waktu Selesai —</option>
                    <option value="Menjelang Dzuhur">Menjelang Dzuhur</option>
                    <option value="Menjelang Ashar">Menjelang Ashar</option>
                    <option value="Menjelang Maghrib">Menjelang Maghrib</option>
                    <option value="Menjelang Isya">Menjelang Isya</option>
                  </select>
                </div>

                {{-- Mode 2: Time clock --}}
                <div id="edit_waktu_selesai_time_wrapper" style="display: none;">
                  <input type="time" id="edit_waktu_selesai_time" class="form-control form-control-custom">
                </div>
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
            <div class="col-sm-12" style="margin-top: 0.5rem;">
              <div class="form-group">
                <label class="control-label">Informasi</label>
                <textarea id="edit_Informasi" name="Informasi" class="form-control form-control-custom" rows="3" placeholder="Informasi tambahan kajian (opsional)..."></textarea>
              </div>
            </div>

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

      function toggleInfoRow(id) {
        const row = document.getElementById(`rowInfo-${id}`);
        if (row.style.display === 'none') {
          row.style.display = 'table-row';
        } else {
          row.style.display = 'none';
        }
      }

      function openEditModal(id, judul, narasumber, tanggal, tempat, kontak, informasi, waktuSelesai, tampilkan) {
        document.getElementById('editForm').action = `/admin_dashboard/${id}`;
        document.getElementById('edit_Judul').value       = judul;
        document.getElementById('edit_Narasumber').value  = narasumber;
        document.getElementById('edit_Tanggal').value     = tanggal;
        document.getElementById('edit_Tempat').value      = tempat;
        document.getElementById('edit_Kontak').value      = kontak;
        document.getElementById('edit_Informasi').value   = informasi;

        // Handle WaktuSelesai modes and inputs
        const textOptions = ['Menjelang Dzuhur', 'Menjelang Ashar', 'Menjelang Maghrib', 'Menjelang Isya'];
        if (waktuSelesai && !textOptions.includes(waktuSelesai)) {
          // Time mode
          document.getElementById('edit_mode_time').checked = true;
          document.getElementById('edit_mode_text').checked = false;
          toggleWaktuSelesaiMode('edit', 'time');
          document.getElementById('edit_waktu_selesai_time').value = waktuSelesai;
          document.getElementById('edit_waktu_selesai_text').value = '';
        } else {
          // Text mode
          document.getElementById('edit_mode_text').checked = true;
          document.getElementById('edit_mode_time').checked = false;
          toggleWaktuSelesaiMode('edit', 'text');
          document.getElementById('edit_waktu_selesai_text').value = waktuSelesai || '';
          document.getElementById('edit_waktu_selesai_time').value = '';
        }

        document.getElementById('edit_Tampilkan').checked = (tampilkan === 'true');
        document.getElementById('editModalBackdrop').classList.add('open');
      }

      function toggleWaktuSelesaiMode(prefix, mode) {
        const textWrapper = document.getElementById(prefix + '_waktu_selesai_text_wrapper');
        const timeWrapper = document.getElementById(prefix + '_waktu_selesai_time_wrapper');
        const textInput = document.getElementById(prefix + '_waktu_selesai_text');
        const timeInput = document.getElementById(prefix + '_waktu_selesai_time');

        if (mode === 'text') {
          textWrapper.style.display = 'block';
          timeWrapper.style.display = 'none';
          textInput.setAttribute('name', 'WaktuSelesai');
          timeInput.removeAttribute('name');
        } else {
          textWrapper.style.display = 'none';
          timeWrapper.style.display = 'block';
          timeInput.setAttribute('name', 'WaktuSelesai');
          textInput.removeAttribute('name');
        }
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

      document.addEventListener('DOMContentLoaded', function() {
        const oldWaktuSelesai = "{{ old('WaktuSelesai') }}";
        if (oldWaktuSelesai) {
          const textOptions = ['Menjelang Dzuhur', 'Menjelang Ashar', 'Menjelang Maghrib', 'Menjelang Isya'];
          if (!textOptions.includes(oldWaktuSelesai)) {
            // Check time radio
            const timeRadio = document.querySelector('input[name="add_waktu_selesai_mode"][value="time"]');
            if (timeRadio) {
              timeRadio.checked = true;
              toggleWaktuSelesaiMode('add', 'time');
              document.getElementById('add_waktu_selesai_time').value = oldWaktuSelesai;
            }
          } else {
            // Check text radio
            const textRadio = document.querySelector('input[name="add_waktu_selesai_mode"][value="text"]');
            if (textRadio) {
              textRadio.checked = true;
              toggleWaktuSelesaiMode('add', 'text');
              document.getElementById('add_waktu_selesai_text').value = oldWaktuSelesai;
            }
          }
        }
      });

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
