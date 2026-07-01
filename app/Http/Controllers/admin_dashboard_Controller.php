<?php

namespace App\Http\Controllers;

use App\Models\running_text_data;
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

    /**
     * Save an uploaded logo to public/logo/ and return the filename.
     * Generates a unique name to avoid collisions.
     */
    private function saveLogo(Request $request): ?string
    {
        if (!$request->hasFile('Logo')) {
            return null;
        }

        $file      = $request->file('Logo');
        $extension = $file->getClientOriginalExtension();
        $filename  = uniqid('logo_', true) . '.' . $extension;

        $file->move($this->logoDir, $filename);

        return $filename; // only the filename is stored in DB
    }

    /**
     * Delete a logo file from public/logo/ if it exists.
     * Called automatically on update (old file) and destroy.
     */
    private function deleteLogo(?string $filename): void
    {
        if (!$filename) return;

        $path = $this->logoDir . DIRECTORY_SEPARATOR . $filename;

        if (File::exists($path)) {
            File::delete($path);
        }
    }

    // ─────────────────────────────────────────────────────────────

    /**
     * Show the admin dashboard with all kajian records.
     */
    public function index()
    {
        $this->requireAdmin();

        $kajian = running_text_data::orderBy('Tanggal', 'asc')->get();

        return view('admin_dashboard', compact('kajian'));
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
            'Narasumber' => ['required', 'string', 'max:255'],
            'Tempat'     => ['required', 'string', 'max:255'],
            'Kontak'     => ['nullable', 'string', 'max:100'],
            'Tampilkan'  => ['nullable', 'boolean'],
            'Logo'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg,webp', 'max:4096'],
        ]);

        $data['Tampilkan'] = $request->boolean('Tampilkan');
        $data['Logo']      = $this->saveLogo($request); // null if no file

        running_text_data::create($data);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Kajian berhasil ditambahkan.');
    }

    /**
     * Update an existing kajian record.
     * If a new logo is uploaded, the old file is deleted automatically.
     */
    public function update(Request $request, $id)
    {
        $this->requireAdmin();

        $kajian = running_text_data::findOrFail($id);

        $data = $request->validate([
            'Tanggal'    => ['required', 'date'],
            'Judul'      => ['required', 'string', 'max:255'],
            'Narasumber' => ['required', 'string', 'max:255'],
            'Tempat'     => ['required', 'string', 'max:255'],
            'Kontak'     => ['nullable', 'string', 'max:100'],
            'Tampilkan'  => ['nullable', 'boolean'],
            'Logo'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg,webp', 'max:4096'],
        ]);

        $data['Tampilkan'] = $request->boolean('Tampilkan');

        if ($request->hasFile('Logo')) {
            // Delete the old logo file before saving the new one
            $this->deleteLogo($kajian->Logo);
            $data['Logo'] = $this->saveLogo($request);
        } else {
            // Keep the existing logo filename in DB
            unset($data['Logo']);
        }

        $kajian->update($data);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Kajian berhasil diperbarui.');
    }

    /**
     * Toggle the Tampilkan (show/hide) flag for a kajian.
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
     * The logo file is deleted from public/logo/ automatically — no orphans left.
     */
    public function destroy($id)
    {
        $this->requireAdmin();

        $kajian = running_text_data::findOrFail($id);

        // Delete the logo file first — it is no longer used after this
        $this->deleteLogo($kajian->Logo);

        $kajian->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Kajian berhasil dihapus.');
    }
}
