<?php

namespace App\Http\Controllers;

use App\Models\running_text_data;
use App\Models\Narasumber;
use App\Models\Tempat;
use App\Models\Kontak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class admin_dashboard_Controller extends Controller
{
    /**
     * Absolute path to the logo storage folder.
     * Files here are publicly accessible via asset('logo/filename').
     */
    private string $logoDir;

    public function __construct()
    {
        // public/logo/ — web-accessible, no symlink needed
        $this->logoDir = public_path('logo');

        // Create the directory if it doesn't exist yet
        if (!File::exists($this->logoDir)) {
            File::makeDirectory($this->logoDir, 0755, true);
        }
    }

    /**
     * Guard: only admin role may access any method in this controller.
     */
    private function requireAdmin(): void
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized!');
        }
    }



    // ─────────────────────────────────────────────────────────────

    /**
     * Show the admin dashboard with all kajian records and global logo.
     */
    public function index()
    {
        $this->requireAdmin();

        $kajian = running_text_data::orderBy('Tanggal', 'asc')->get();
        $narasumberList = Narasumber::orderBy('nama', 'asc')->get();
        $tempatList     = Tempat::orderBy('nama', 'asc')->get();
        $kontakList     = Kontak::orderBy('nama', 'asc')->get();

        $logoUrl = null;
        $files = glob(public_path('logo/global_logo.*'));
        if (!empty($files)) {
            $logoUrl = asset('logo/' . basename($files[0])) . '?v=' . filemtime($files[0]);
        }

        return view('admin_dashboard', compact('kajian', 'logoUrl', 'narasumberList', 'tempatList', 'kontakList'));
    }

    /**
     * Show the logo management page.
     */
    public function logo()
    {
        $this->requireAdmin();

        $logoUrl = null;
        $files = glob(public_path('logo/global_logo.*'));
        if (!empty($files)) {
            $logoUrl = asset('logo/' . basename($files[0])) . '?v=' . filemtime($files[0]);
        }

        return view('logo', compact('logoUrl'));
    }

    /**
     * Upload or update the single global logo.
     */
    public function uploadGlobalLogo(Request $request)
    {
        $this->requireAdmin();

        $request->validate([
            'Logo' => ['required', 'image', 'mimes:jpg,jpeg,png,svg,webp', 'max:4096'],
        ]);

        if ($request->hasFile('Logo')) {
            // Delete any existing global logo files
            $existing = glob(public_path('logo/global_logo.*'));
            foreach ($existing as $file) {
                if (File::exists($file)) {
                    File::delete($file);
                }
            }

            // Save the new one
            $file = $request->file('Logo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'global_logo.' . $extension;
            $file->move(public_path('logo'), $filename);

            return redirect()->route('admin.dashboard')
                ->with('success', 'Logo global berhasil diperbarui.');
        }

        return redirect()->route('admin.dashboard')
            ->with('error', 'Gagal mengupload logo.');
    }

    /**
     * Store a new kajian record.
     */
    public function store(Request $request)
    {
        $this->requireAdmin();

        $data = $request->validate([
            'Tanggal'    => ['required', 'date'],
            'Judul'      => ['required', 'string', 'max:255'],
            'Narasumber' => ['required', 'string', 'exists:narasumber,nama'],
            'Tempat'     => ['required', 'string', 'exists:tempat,nama'],
            'Kontak'     => ['nullable', 'string', 'exists:kontak,nama'],
            'Tampilkan'  => ['nullable', 'boolean'],
        ]);

        $data['Tampilkan'] = $request->boolean('Tampilkan');

        running_text_data::create($data);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Kajian berhasil ditambahkan.');
    }

    /**
     * Update an existing kajian record.
     */
    public function update(Request $request, $id)
    {
        $this->requireAdmin();

        $kajian = running_text_data::findOrFail($id);

        $data = $request->validate([
            'Tanggal'    => ['required', 'date'],
            'Judul'      => ['required', 'string', 'max:255'],
            'Narasumber' => ['required', 'string', 'exists:narasumber,nama'],
            'Tempat'     => ['required', 'string', 'exists:tempat,nama'],
            'Kontak'     => ['nullable', 'string', 'exists:kontak,nama'],
            'Tampilkan'  => ['nullable', 'boolean'],
        ]);

        $data['Tampilkan'] = $request->boolean('Tampilkan');

        $kajian->update($data);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Kajian berhasil diperbarui.');
    }

    /**
     * Toggle the Tampilkan (show/hide kajian) flag.
     */
    public function toggle($id)
    {
        $this->requireAdmin();

        $kajian = running_text_data::findOrFail($id);
        $kajian->update(['Tampilkan' => !$kajian->Tampilkan]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Status tampil kajian diperbarui.');
    }

    /**
     * Delete a kajian record.
     */
    public function destroy($id)
    {
        $this->requireAdmin();

        $kajian = running_text_data::findOrFail($id);
        $kajian->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Kajian berhasil dihapus.');
    }
}
