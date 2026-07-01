<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard — Jadwal Kajian</title>
    <meta name="description" content="Panel admin untuk mengelola jadwal kajian.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:          #0f1117;
            --surface:     #1a1d27;
            --surface-2:   #22263a;
            --border:      rgba(255,255,255,0.07);
            --border-h:    rgba(255,255,255,0.13);
            --accent:      #6d28d9;
            --accent-h:    #7c3aed;
            --gold:        #f59e0b;
            --green:       #10b981;
            --red:         #ef4444;
            --red-h:       #dc2626;
            --text:        #e2e8f0;
            --text-dim:    #64748b;
            --radius:      10px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── Top bar ── */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(15,17,23,0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            height: 60px;
        }

        .topbar-brand {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .topbar-brand span.badge {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            background: var(--accent);
            color: #fff;
            border-radius: 4px;
            padding: 2px 7px;
            text-transform: uppercase;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topbar-user {
            font-size: 0.82rem;
            color: var(--text-dim);
        }

        .topbar-user strong {
            color: var(--text);
        }

        .btn-logout {
            font-size: 0.8rem;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-dim);
            padding: 5px 14px;
            cursor: pointer;
            transition: border-color 0.15s, color 0.15s;
        }

        .btn-logout:hover { border-color: var(--red); color: var(--red); }

        /* ── Page wrapper ── */
        .page {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2rem 1.5rem 4rem;
        }

        /* ── Alerts ── */
        .alert {
            border-radius: var(--radius);
            padding: 0.75rem 1.1rem;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .alert-success { background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.3); color: #6ee7b7; }
        .alert-error   { background: rgba(239,68,68,0.1);   border: 1px solid rgba(239,68,68,0.3);  color: #fca5a5; }

        /* ── Section header ── */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .section-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text);
        }

        .section-count {
            font-size: 0.78rem;
            color: var(--text-dim);
            margin-top: 2px;
        }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: 'Inter', sans-serif;
            font-size: 0.82rem;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 7px 16px;
            cursor: pointer;
            transition: opacity 0.15s, transform 0.1s;
            text-decoration: none;
            white-space: nowrap;
        }
        .btn:active { transform: scale(0.97); }
        .btn-primary { background: var(--accent);  color: #fff; }
        .btn-primary:hover { background: var(--accent-h); }
        .btn-success { background: var(--green);   color: #fff; }
        .btn-danger  { background: var(--red);     color: #fff; }
        .btn-danger:hover { background: var(--red-h); }
        .btn-outline {
            background: transparent;
            border: 1px solid var(--border-h);
            color: var(--text-dim);
        }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }
        .btn-sm { font-size: 0.75rem; padding: 5px 11px; }
        .btn-icon { padding: 5px 8px; }

        /* ── Toggle switch ── */
        .toggle-form { display: inline; }
        .toggle {
            position: relative;
            display: inline-block;
            width: 42px;
            height: 22px;
            vertical-align: middle;
        }
        .toggle input { opacity: 0; width: 0; height: 0; }
        .toggle-slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background: rgba(255,255,255,0.12);
            border-radius: 22px;
            transition: background 0.2s;
        }
        .toggle-slider::before {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            left: 3px;
            top: 3px;
            background: #fff;
            border-radius: 50%;
            transition: transform 0.2s;
        }
        .toggle input:checked + .toggle-slider { background: var(--green); }
        .toggle input:checked + .toggle-slider::before { transform: translateX(20px); }

        /* ── Card / table container ── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
        }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        thead {
            background: var(--surface-2);
        }

        thead th {
            padding: 12px 16px;
            text-align: left;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-dim);
            white-space: nowrap;
            border-bottom: 1px solid var(--border);
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.12s;
        }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: rgba(255,255,255,0.025); }

        td {
            padding: 12px 16px;
            vertical-align: middle;
            color: var(--text);
        }

        .td-logo img {
            width: 38px;
            height: 38px;
            border-radius: 7px;
            object-fit: contain;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            padding: 3px;
        }

        .td-logo .no-logo {
            width: 38px;
            height: 38px;
            border-radius: 7px;
            background: rgba(255,255,255,0.04);
            border: 1px dashed var(--border-h);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.6rem;
            color: var(--text-dim);
        }

        .td-judul { font-weight: 600; color: var(--text); max-width: 220px; }
        .td-judul small { display: block; font-size: 0.75rem; font-weight: 400; color: var(--text-dim); margin-top: 2px; }

        .pill {
            display: inline-block;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            border-radius: 20px;
            padding: 3px 10px;
        }
        .pill-on  { background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.3); }
        .pill-off { background: rgba(100,116,139,0.15); color: #94a3b8; border: 1px solid rgba(100,116,139,0.2); }

        .td-actions {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: nowrap;
        }

        /* ── Empty state ── */
        .empty {
            padding: 3rem;
            text-align: center;
            color: var(--text-dim);
            font-size: 0.9rem;
        }

        /* ── Add kajian section ── */
        .add-section {
            margin-bottom: 2rem;
        }

        .form-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.5rem;
            display: none; /* hidden by default, toggled by JS */
        }
        .form-card.open { display: block; }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-group { display: flex; flex-direction: column; gap: 5px; }

        .form-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-dim);
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .form-control {
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
            padding: 8px 12px;
            outline: none;
            transition: border-color 0.15s;
            width: 100%;
        }
        .form-control:focus { border-color: var(--accent); }
        .form-control::placeholder { color: var(--text-dim); }

        .form-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        /* ── Edit modal ── */
        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.65);
            backdrop-filter: blur(4px);
            z-index: 100;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .modal-backdrop.open { display: flex; }

        .modal {
            background: var(--surface);
            border: 1px solid var(--border-h);
            border-radius: 16px;
            padding: 1.75rem;
            width: 100%;
            max-width: 680px;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalIn 0.2s ease;
        }

        @keyframes modalIn {
            from { opacity: 0; transform: translateY(16px) scale(0.98); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }

        .modal-title { font-size: 1rem; font-weight: 700; }

        .modal-close {
            background: none;
            border: none;
            color: var(--text-dim);
            font-size: 1.3rem;
            cursor: pointer;
            line-height: 1;
            padding: 4px;
            border-radius: 6px;
            transition: color 0.15s;
        }
        .modal-close:hover { color: var(--red); }

        /* ── Confirm delete modal ── */
        .confirm-modal {
            background: var(--surface);
            border: 1px solid var(--border-h);
            border-radius: 16px;
            padding: 2rem;
            width: 100%;
            max-width: 420px;
            text-align: center;
            animation: modalIn 0.2s ease;
        }

        .confirm-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .confirm-title {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .confirm-desc {
            font-size: 0.85rem;
            color: var(--text-dim);
            margin-bottom: 1.5rem;
        }

        .confirm-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
    </style>
</head>
<body>

{{-- ── Top Bar ── --}}
<header class="topbar">
    <div class="topbar-brand">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
        </svg>
        Jadwal Kajian
        <span class="badge">Admin</span>
    </div>
    <div class="topbar-right">
        <span class="topbar-user">Login sebagai <strong>{{ Auth::user()->name }}</strong></span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
</header>

{{-- ── Main Page ── --}}
<div class="page">

    {{-- Alerts --}}
    @if (session('success'))
        <div class="alert alert-success">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-error">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-error">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
            <div>
                @foreach ($errors->all() as $e) <div>{{ $e }}</div> @endforeach
            </div>
        </div>
    @endif

    {{-- ── Add Kajian ── --}}
    <div class="add-section">
        <div class="section-header">
            <div>
                <div class="section-title">Tambah Kajian Baru</div>
            </div>
            <button class="btn btn-primary" id="toggleAddForm" onclick="toggleAddForm()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Kajian
            </button>
        </div>

        <div class="form-card" id="addForm">
            <form method="POST" action="{{ route('admin.kajian.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="add_Judul">Judul Kajian</label>
                        <input id="add_Judul" type="text" name="Judul" class="form-control" placeholder="Nama kajian" value="{{ old('Judul') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="add_Narasumber">Narasumber</label>
                        <input id="add_Narasumber" type="text" name="Narasumber" class="form-control" placeholder="Ustadz / Pembicara" value="{{ old('Narasumber') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="add_Tanggal">Tanggal &amp; Waktu</label>
                        <input id="add_Tanggal" type="datetime-local" name="Tanggal" class="form-control" value="{{ old('Tanggal') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="add_Tempat">Tempat</label>
                        <input id="add_Tempat" type="text" name="Tempat" class="form-control" placeholder="Lokasi kajian" value="{{ old('Tempat') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="add_Kontak">Kontak</label>
                        <input id="add_Kontak" type="text" name="Kontak" class="form-control" placeholder="No. HP / WA (opsional)" value="{{ old('Kontak') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="add_Logo">Logo / Gambar</label>
                        <input id="add_Logo" type="file" name="Logo" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom:1rem; flex-direction:row; align-items:center; gap:10px;">
                    <label class="toggle" for="add_Tampilkan">
                        <input type="checkbox" id="add_Tampilkan" name="Tampilkan" value="1" {{ old('Tampilkan') ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                    <span class="form-label" style="text-transform:none; letter-spacing:0;">Tampilkan di running text</span>
                </div>
                <div class="form-footer">
                    <button type="button" class="btn btn-outline" onclick="toggleAddForm()">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Kajian Table ── --}}
    <div>
        <div class="section-header">
            <div>
                <div class="section-title">Daftar Kajian</div>
                <div class="section-count">Total: {{ $kajian->count() }} kajian</div>
            </div>
        </div>

        <div class="card">
            <div class="table-wrap">
                @if ($kajian->isEmpty())
                    <div class="empty">Belum ada data kajian. Tambahkan kajian pertama!</div>
                @else
                <table>
                    <thead>
                        <tr>
                            <th style="width:46px">Logo</th>
                            <th>Judul &amp; Narasumber</th>
                            <th>Tanggal</th>
                            <th>Tempat</th>
                            <th>Kontak</th>
                            <th style="text-align:center">Tampil</th>
                            <th style="text-align:right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kajian as $item)
                        <tr>
                            {{-- Logo --}}
                            <td class="td-logo">
                                @if ($item->Logo)
                                    <img src="{{ asset('logo/' . $item->Logo) }}" alt="{{ $item->Judul }}">
                                @else
                                    <span class="no-logo">N/A</span>
                                @endif
                            </td>

                            {{-- Judul & Narasumber --}}
                            <td class="td-judul">
                                {{ $item->Judul }}
                                <small>{{ $item->Narasumber }}</small>
                            </td>

                            {{-- Tanggal --}}
                            <td style="white-space:nowrap; color:var(--text-dim);">
                                {{ \Carbon\Carbon::parse($item->Tanggal)->locale('id')->isoFormat('ddd, D MMM Y') }}
                                <br>
                                <small>{{ \Carbon\Carbon::parse($item->Tanggal)->format('H:i') }}</small>
                            </td>

                            {{-- Tempat --}}
                            <td style="color:var(--text-dim)">{{ $item->Tempat }}</td>

                            {{-- Kontak --}}
                            <td style="color:var(--text-dim)">{{ $item->Kontak ?: '—' }}</td>

                            {{-- Toggle Tampilkan --}}
                            <td style="text-align:center;">
                                <form method="POST" action="{{ route('admin.kajian.toggle', $item->id) }}" class="toggle-form" id="toggleForm-{{ $item->id }}">
                                    @csrf
                                </form>
                                <label class="toggle" title="{{ $item->Tampilkan ? 'Klik untuk sembunyikan' : 'Klik untuk tampilkan' }}">
                                    <input type="checkbox"
                                        {{ $item->Tampilkan ? 'checked' : '' }}
                                        onchange="document.getElementById('toggleForm-{{ $item->id }}').submit()">
                                    <span class="toggle-slider"></span>
                                </label>
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div class="td-actions" style="justify-content:flex-end;">
                                    <button class="btn btn-outline btn-sm"
                                        onclick="openEditModal(
                                            {{ $item->id }},
                                            '{{ addslashes($item->Judul) }}',
                                            '{{ addslashes($item->Narasumber) }}',
                                            '{{ \Carbon\Carbon::parse($item->Tanggal)->format('Y-m-d\TH:i') }}',
                                            '{{ addslashes($item->Tempat) }}',
                                            '{{ addslashes($item->Kontak ?? '') }}',
                                            {{ $item->Tampilkan ? 'true' : 'false' }}
                                        )">
                                        ✏️ Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm"
                                        onclick="openDeleteModal({{ $item->id }}, '{{ addslashes($item->Judul) }}')">
                                        🗑 Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>

</div>

{{-- ── Edit Modal ── --}}
<div class="modal-backdrop" id="editModalBackdrop" onclick="closeEditModal(event)">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Edit Kajian</div>
            <button class="modal-close" onclick="closeEditModal(null)">✕</button>
        </div>
        <form method="POST" id="editForm" enctype="multipart/form-data">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="edit_Judul">Judul Kajian</label>
                    <input id="edit_Judul" type="text" name="Judul" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="edit_Narasumber">Narasumber</label>
                    <input id="edit_Narasumber" type="text" name="Narasumber" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="edit_Tanggal">Tanggal &amp; Waktu</label>
                    <input id="edit_Tanggal" type="datetime-local" name="Tanggal" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="edit_Tempat">Tempat</label>
                    <input id="edit_Tempat" type="text" name="Tempat" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="edit_Kontak">Kontak</label>
                    <input id="edit_Kontak" type="text" name="Kontak" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label" for="edit_Logo">Ganti Logo (opsional)</label>
                    <input id="edit_Logo" type="file" name="Logo" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="form-group" style="margin-bottom:1.25rem; flex-direction:row; align-items:center; gap:10px;">
                <label class="toggle" for="edit_Tampilkan">
                    <input type="checkbox" id="edit_Tampilkan" name="Tampilkan" value="1">
                    <span class="toggle-slider"></span>
                </label>
                <span class="form-label" style="text-transform:none; letter-spacing:0;">Tampilkan di running text</span>
            </div>
            <div class="form-footer">
                <button type="button" class="btn btn-outline" onclick="closeEditModal(null)">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── Delete Confirm Modal ── --}}
<div class="modal-backdrop" id="deleteModalBackdrop" onclick="closeDeleteModal(event)">
    <div class="confirm-modal">
        <div class="confirm-icon">🗑️</div>
        <div class="confirm-title">Hapus Kajian?</div>
        <div class="confirm-desc" id="deleteDesc">Kajian ini akan dihapus secara permanen dan tidak bisa dikembalikan.</div>
        <div class="confirm-actions">
            <button class="btn btn-outline" onclick="closeDeleteModal(null)">Batal</button>
            <form method="POST" id="deleteForm">
                @csrf
                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
            </form>
        </div>
    </div>
</div>

<script>
    // ── Add Form toggle ───────────────────────────────────────────
    function toggleAddForm() {
        const form = document.getElementById('addForm');
        const btn  = document.getElementById('toggleAddForm');
        form.classList.toggle('open');
        btn.textContent = form.classList.contains('open') ? '✕ Tutup' : '＋ Tambah Kajian';
    }

    // ── Edit Modal ────────────────────────────────────────────────
    function openEditModal(id, judul, narasumber, tanggal, tempat, kontak, tampilkan) {
        document.getElementById('editForm').action = `/admin_dashboard/${id}`;
        document.getElementById('edit_Judul').value       = judul;
        document.getElementById('edit_Narasumber').value  = narasumber;
        document.getElementById('edit_Tanggal').value     = tanggal;
        document.getElementById('edit_Tempat').value      = tempat;
        document.getElementById('edit_Kontak').value      = kontak;
        document.getElementById('edit_Tampilkan').checked = tampilkan;
        document.getElementById('editModalBackdrop').classList.add('open');
    }

    function closeEditModal(e) {
        if (e === null || e.target === document.getElementById('editModalBackdrop')) {
            document.getElementById('editModalBackdrop').classList.remove('open');
        }
    }

    // ── Delete Modal ──────────────────────────────────────────────
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

    // Close modals on Escape key
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeEditModal(null);
            closeDeleteModal(null);
        }
    });

    // Auto-open add form if there were validation errors on store
    @if ($errors->any() && old('Judul'))
    toggleAddForm();
    @endif
</script>

</body>
</html>
