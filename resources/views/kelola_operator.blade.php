<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Operator — Jadwal Kajian</title>
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
                    <li class="active">Kelola Operator</li>
                  </ol>
                </div>
                <div class="main-filter">
                  <button class="btn btn-primary" onclick="toggleAddForm()">
                    <i class="fa fa-plus"></i> Tambah Operator
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
                      <h3 class="panel-title"><i class="fa fa-plus-circle"></i> Tambah Operator Baru</h3>
                      <button type="button" class="btn btn-xs btn-danger" onclick="toggleAddForm()">
                        <i class="fa fa-times"></i> Tutup
                      </button>
                    </div>
                    <div class="panel-body">
                      <form method="POST" action="{{ route('admin.operator.store') }}">
                        @csrf
                        <div class="row">
                          <div class="col-sm-4">
                            <div class="form-group">
                              <label class="control-label" for="add_name">Nama Operator</label>
                              <input id="add_name" type="text" name="name" class="form-control form-control-custom"
                                     placeholder="Isi Nama Disini" value="{{ old('name') }}" required autocomplete="off">
                            </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group">
                              <label class="control-label" for="add_password">Password</label>
                              <input id="add_password" type="password" name="password" class="form-control form-control-custom"
                                     placeholder="Password (min. 6 karakter)" required autocomplete="new-password">
                            </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group">
                              <label class="control-label" for="add_role">Role / Peran</label>
                              <select id="add_role" name="role" class="form-control form-control-custom" required>
                                <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                                <option value="admin_operator" {{ old('role') == 'admin_operator' ? 'selected' : '' }}>Admin</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="row" style="margin-top: 15px;">
                          <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-primary" style="min-width: 150px;">
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
                    <h3 class="panel-title" style="margin:0;"><i class="fa fa-list"></i> Daftar Akun Operator <small style="margin-left:8px; color:rgba(255,255,255,0.4);" class="section-count">Total: {{ $operators->count() }} akun</small></h3>
                    <div style="display:flex; align-items:center; gap:8px; flex-wrap:nowrap;">
                      <input type="search" id="operatorSearchInput" placeholder="Cari operator..." class="form-control form-control-custom" style="width:200px; padding:6px 12px; height:34px; margin:0;" onkeyup="filterOperatorTable()">
                      <button class="btn btn-primary" onclick="filterOperatorTable()" style="padding:6px 15px; height:34px; line-height:20px; font-size:1.15rem; margin:0;"><i class="fa fa-search"></i> Cari</button>
                    </div>
                  </div>
                  <div class="panel-body" style="padding:0;">
                    @if ($operators->isEmpty())
                      <div class="empty-state">
                        <i class="fa fa-users" style="font-size:2.5rem; margin-bottom:1rem; display:block; opacity:0.3;"></i>
                        Belum ada data operator.
                      </div>
                    @else
                      <div class="table-responsive">
                        <table class="table table-hover" style="margin-bottom:0; table-layout: fixed; width: 100%;">
                          <thead>
                            <tr>
                              <th style="width: 80px;">No.</th>
                              <th>Nama Operator</th>
                              <th style="width: 200px;">Role / Peran</th>
                              <th style="text-align:right; width: 220px;">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($operators as $index => $item)
                            <tr id="mainRow-{{ $item->id }}" class="searchable-row">
                              <td>{{ $index + 1 }}</td>
                              <td><strong>{{ $item->name }}</strong></td>
                              <td>
                                @if($item->role === 'admin_operator')
                                  <span class="label label-danger" style="border-radius: 99px; padding: 3px 8px; font-size: 1.15rem; background-color: rgba(239, 68, 68, 0.15) !important; color: #fb7185 !important; border: 1px solid rgba(239, 68, 68, 0.3) !important;">Admin</span>
                                @else
                                  <span class="label label-info" style="border-radius: 99px; padding: 3px 8px; font-size: 1.15rem; background-color: rgba(56, 189, 248, 0.15) !important; color: #38bdf8 !important; border: 1px solid rgba(56, 189, 248, 0.3) !important;">Operator</span>
                                @endif
                              </td>
                              <td>
                                <div class="td-actions" style="justify-content:flex-end; gap: 8px;">
                                  <button class="btn-kajian-outline"
                                    onclick="openEditModal('{{ $item->id }}', '{{ addslashes($item->name) }}', '{{ $item->role }}')">
                                    <i class="fa fa-pencil"></i> Edit
                                  </button>
                                  @if($item->id !== Auth::id())
                                    <button class="btn-kajian-danger"
                                      onclick="openDeleteModal('{{ $item->id }}', '{{ addslashes($item->name) }}')">
                                      <i class="fa fa-trash"></i> Hapus
                                    </button>
                                  @else
                                    <span style="color:#64748b; font-size:1.1rem; padding: 2px 8px; font-style:italic;">Akun Anda</span>
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

              </div>{{-- end container-fluid --}}
            </div>{{-- end main__cont --}}
          </div>{{-- end main__scroll --}}
        </div>{{-- end main --}}

      </div>{{-- end dashboard --}}
    </div>{{-- end wrapper --}}

    {{-- Edit Modal --}}
    <div class="modal-backdrop-custom" id="editModalBackdrop" onclick="closeEditModal(event)">
      <div class="modal-confirm" style="max-width: 450px; text-align: left;">
        <div style="font-size:1.5rem; font-weight:700; margin-bottom:1.5rem; color:#e2e8f0; text-align: center;">
          <i class="fa fa-pencil" style="color: #6366f1;"></i> Edit Akun Operator
        </div>
        <form method="POST" id="editForm">
          @csrf
          <div class="form-group" style="margin-bottom: 15px;">
            <label class="control-label" for="edit_name">Nama Operator</label>
            <input id="edit_name" type="text" name="name" class="form-control form-control-custom" required autocomplete="off">
          </div>
          <div class="form-group" style="margin-bottom: 15px;">
            <label class="control-label" for="edit_password">Password Baru (Opsional)</label>
            <input id="edit_password" type="password" name="password" class="form-control form-control-custom" placeholder="Kosongkan jika tidak ingin diubah" autocomplete="new-password">
          </div>
          <div class="form-group" style="margin-bottom: 20px;">
            <label class="control-label" for="edit_role">Role / Peran</label>
            <select id="edit_role" name="role" class="form-control form-control-custom" required>
              <option value="operator">Operator</option>
              <option value="admin_operator">Admin</option>
            </select>
          </div>
          <div style="display:flex; justify-content:flex-end; gap:1rem;">
            <button type="button" class="btn btn-default" onclick="closeEditModal(null)">Batal</button>
            <button type="submit" class="btn btn-primary">
              <i class="fa fa-save"></i> Perbarui
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal-backdrop-custom" id="deleteModalBackdrop" onclick="closeDeleteModal(event)">
      <div class="modal-confirm">
        <div style="font-size:2.5rem; margin-bottom:1rem;">🗑️</div>
        <div style="font-size:1rem; font-weight:700; margin-bottom:0.5rem; color:#e2e8f0;">Hapus Akun Operator?</div>
        <div style="font-size:0.85rem; color:#64748b; margin-bottom:1.5rem;" id="deleteDesc">
          Akun ini akan dihapus secara permanen.
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

      function filterOperatorTable() {
        var input = document.getElementById('operatorSearchInput');
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

      @if ($errors->any() && (old('name') && !old('_edit_id')))
        toggleAddForm();
      @endif

      function openEditModal(id, name, role) {
        document.getElementById('editForm').action = `/admin_dashboard/operator/${id}`;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_role').value = role;
        document.getElementById('edit_password').value = '';
        document.getElementById('editModalBackdrop').classList.add('open');
      }

      function closeEditModal(e) {
        if (e === null || e.target === document.getElementById('editModalBackdrop')) {
          document.getElementById('editModalBackdrop').classList.remove('open');
        }
      }

      function openDeleteModal(id, name) {
        document.getElementById('deleteForm').action = `/admin_dashboard/operator/${id}/delete`;
        document.getElementById('deleteDesc').textContent =
          `Akun operator "${name}" akan dihapus secara permanen.`;
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
