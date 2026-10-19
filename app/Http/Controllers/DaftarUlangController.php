<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Reregistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DaftarUlangController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole(['ADMIN', 'KETUA'])) {
            // Admin View
            $query = User::with(['member', 'reregistration'])->whereDoesntHave('roles', function($q) {
                $q->where('name', 'ADMIN');
            }); // Maybe include all users except pure admins. Actually let's just include all users for simplicity.
            
            $query = User::with(['member', 'reregistration']);

            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            if ($request->filled('kelas')) {
                $query->whereHas('member', function($q) use ($request) {
                    $q->where('kelas', 'like', '%' . $request->kelas . '%');
                });
            }

            if ($request->filled('status')) {
                if ($request->status == 'Belum Daftar Ulang') {
                    $query->whereDoesntHave('reregistration');
                } else {
                    $query->whereHas('reregistration', function($q) use ($request) {
                        $q->where('status_verifikasi', $request->status);
                    });
                }
            }

            $users = $query->get();

            $totalAnggota = User::count();
            $sudahDaftarUlang = Reregistration::count();
            $belumDaftarUlang = $totalAnggota - $sudahDaftarUlang;
            $menungguVerifikasi = Reregistration::where('status_verifikasi', 'Menunggu Verifikasi')->count();
            $terverifikasi = Reregistration::where('status_verifikasi', 'Terverifikasi')->count();
            $tidakMelanjutkan = Reregistration::where('status_verifikasi', 'Tidak Melanjutkan')->count();

            $stats = [
                'total' => $totalAnggota,
                'sudah' => $sudahDaftarUlang,
                'belum' => $belumDaftarUlang,
                'menunggu' => $menungguVerifikasi,
                'terverifikasi' => $terverifikasi,
                'tidak' => $tidakMelanjutkan,
            ];

            return view('daftar-ulang.admin', compact('users', 'stats'));
        } else {
            // Anggota View
            $reregistration = $user->reregistration;
            return view('daftar-ulang.anggota', compact('user', 'reregistration'));
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->reregistration) {
            return redirect()->back()->with('error', 'Anda sudah melakukan daftar ulang.');
        }

        $request->validate([
            'no_hp' => 'nullable|string',
            'email' => 'required|email',
            'kelas' => 'required|string',
            'status_keaktifan' => 'required|boolean',
            'alasan_tidak_melanjutkan' => 'nullable|string',
        ]);

        // Update user email
        $user->update(['email' => $request->email]);

        // Update member no_hp and kelas
        if ($user->member) {
            $user->member->update([
                'phone' => $request->no_hp,
                'kelas' => $request->kelas,
            ]);
        }

        $statusVerifikasi = $request->status_keaktifan ? 'Menunggu Verifikasi' : 'Tidak Melanjutkan';

        Reregistration::create([
            'user_id' => $user->id,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'kelas' => $request->kelas,
            'jurusan' => $user->member->jurusan ?? null,
            'status_keaktifan' => $request->status_keaktifan,
            'alasan_tidak_melanjutkan' => $request->alasan_tidak_melanjutkan,
            'status_verifikasi' => $statusVerifikasi,
        ]);

        return redirect()->back()->with('success', 'Formulir daftar ulang berhasil dikirim.');
    }

    public function updateStatus(Request $request, Reregistration $reregistration)
    {
        if (!Auth::user()->hasRole(['ADMIN', 'KETUA'])) {
            abort(403);
        }

        $request->validate([
            'status_verifikasi' => 'required|in:Menunggu Verifikasi,Terverifikasi,Tidak Melanjutkan'
        ]);

        $reregistration->update([
            'status_verifikasi' => $request->status_verifikasi
        ]);

        return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }
}
