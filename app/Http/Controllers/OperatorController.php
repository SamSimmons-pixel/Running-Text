<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OperatorController extends Controller
{
    /**
     * Guard: only admin_operator role may access this controller.
     */
    private function requireAdminOperator(): void
    {
        if (!Auth::check() || Auth::user()->role !== 'admin_operator') {
            abort(403, 'Unauthorized!');
        }
    }

    /**
     * Display a listing of the operators.
     */
    public function index()
    {
        $this->requireAdminOperator();

        $operators = User::orderBy('name', 'asc')->get();

        return view('kelola_operator', compact('operators'));
    }

    /**
     * Store a newly created operator in storage.
     */
    public function store(Request $request)
    {
        $this->requireAdminOperator();

        $request->validate([
            'name'     => ['required', 'string', 'max:255', 'unique:users,name'],
            'password' => ['required', 'string', 'min:6'],
            'role'     => ['required', 'string', 'in:admin_operator,operator'],
        ]);

        $user = new User();
        $user->name = $request->name;
        // Password hashing is handled automatically by User model casts attribute,
        // but assigning text is fine. To be extremely safe and standard, assign it directly:
        $user->password = $request->password;
        $user->role = $request->role;
        $user->save();

        return redirect()->route('admin.operator')
            ->with('success', 'Akun operator berhasil ditambahkan.');
    }

    /**
     * Update the specified operator in storage.
     */
    public function update(Request $request, $id)
    {
        $this->requireAdminOperator();

        $user = User::findOrFail($id);

        $request->validate([
            'name'     => ['required', 'string', 'max:255', 'unique:users,name,' . $id],
            'password' => ['nullable', 'string', 'min:6'],
            'role'     => ['required', 'string', 'in:admin_operator,operator'],
        ]);

        $user->name = $request->name;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = $request->password;
        }

        $user->save();

        return redirect()->route('admin.operator')
            ->with('success', 'Akun operator berhasil diperbarui.');
    }

    /**
     * Remove the specified operator from storage.
     */
    public function destroy($id)
    {
        $this->requireAdminOperator();

        if ($id == Auth::id()) {
            return redirect()->route('admin.operator')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.operator')
            ->with('success', 'Akun operator berhasil dihapus.');
    }
}
