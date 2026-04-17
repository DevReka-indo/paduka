<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UnitKerja;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('username', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // $users = $query->with('unitKerja')->latest()->paginate(10)->withQueryString();
        $users = $query
            ->with('unitKerja')
            ->orderByRaw(
                "CASE
                    WHEN level = 'superadmin' THEN 1
                    ELSE 2
                END",
            )
            ->orderByRaw('LOWER(name) ASC')
            ->paginate(15)
            ->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $unitKerja = UnitKerja::aktif()->orderBy('nama_unit')->get();

        return view('users.create', compact('unitKerja'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:15', 'unique:users,username'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'confirmed', Password::min(8)],
                'no_telp' => ['nullable', 'string', 'max:15'],
                'jabatan' => ['nullable', 'string', 'max:20'],
                'unit_kerja' => ['nullable', 'string', 'max:100'],
                'departemen' => ['nullable', 'string', 'max:100'],
                'divisi' => ['nullable', 'string', 'max:100'],
                'level' => ['required', 'in:admin,user,manager,superadmin'],
                'keterangan' => ['boolean'],
                'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
                'unit_kerja_ids' => ['nullable', 'array'],
                'unit_kerja_ids.*' => ['exists:unit_kerja,id'],
            ],
            [
                'username.unique' => 'Username sudah digunakan.',
                'email.unique' => 'Email sudah terdaftar.',
                'foto.max' => 'Ukuran foto maksimal 2MB.',
            ],
        );

        $data = $request->except(['password', 'password_confirmation', 'foto', 'unit_kerja_ids']);
        $data['password'] = Hash::make($request->password);
        $data['keterangan'] = $request->boolean('keterangan', true);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto-profil', 'public');
        }

        $user = User::create($data);
        $user->unitKerja()->sync($request->input('unit_kerja_ids', []));

        return redirect()->route('users.index')->with('pesan', 'User berhasil ditambahkan.');
    }

    public function show(User $user): View
    {
        $user->load('unitKerja');

        return view('users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $unitKerja = UnitKerja::aktif()->orderBy('nama_unit')->get();
        $user->load('unitKerja');

        return view('users.edit', compact('user', 'unitKerja'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:15', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'no_telp' => ['nullable', 'string', 'max:15'],
            'jabatan' => ['nullable', 'string', 'max:20'],
            'unit_kerja' => ['nullable', 'string', 'max:100'],
            'departemen' => ['nullable', 'string', 'max:100'],
            'divisi' => ['nullable', 'string', 'max:100'],
            'level' => ['required', 'in:admin,user,manager,superadmin'],
            'keterangan' => ['boolean'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'unit_kerja_ids' => ['nullable', 'array'],
            'unit_kerja_ids.*' => ['exists:unit_kerja,id'],
        ]);

        $data = $request->except(['password', 'password_confirmation', 'foto', 'unit_kerja_ids']);
        $data['keterangan'] = $request->boolean('keterangan', true);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $data['foto'] = $request->file('foto')->store('foto-profil', 'public');
        }

        $user->update($data);
        $user->unitKerja()->sync($request->input('unit_kerja_ids', []));

        return redirect()->route('users.index')->with('pesan', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        if ($authUser->id === $user->id) {
            return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        $user->unitKerja()->detach();
        $user->delete();

        return redirect()->route('users.index')->with('pesan', 'User berhasil dihapus.');
    }
}
