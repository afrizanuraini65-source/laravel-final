<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegistrationSetting;
use App\Models\RegistrationSettingLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationSettingController extends Controller
{
    /**
     * Authorize access: ADMIN, KETUA, SEKRETARIS.
     */
    protected function authorizeAccess(): void
    {
        if (! Auth::user()->hasRole(['ADMIN', 'KETUA', 'SEKRETARIS'])) {
            abort(403, 'Akses khusus Ketua, Sekretaris, dan Admin.');
        }
    }

    /**
     * Display registration configuration page.
     */
    public function index()
    {
        $this->authorizeAccess();

        $settings = RegistrationSetting::current();
        $logs = RegistrationSettingLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        $isAccountOpen = $settings->isAccountOpen();
        $isMemberOpen = $settings->isMemberOpen();

        return view('admin.settings.registration', compact('settings', 'logs', 'isAccountOpen', 'isMemberOpen'));
    }

    /**
     * Update registration period configuration.
     */
    public function update(Request $request)
    {
        $this->authorizeAccess();

        $request->validate([
            'is_account_registration_open' => 'required|boolean',
            'account_registration_start' => 'nullable|date',
            'account_registration_end' => 'nullable|date|after_or_equal:account_registration_start',
            'is_member_registration_open' => 'required|boolean',
            'member_registration_start' => 'nullable|date',
            'member_registration_end' => 'nullable|date|after_or_equal:member_registration_start',
            'announcement_title' => 'nullable|string|max:255',
            'announcement_description' => 'nullable|string',
            'requirements' => 'nullable|string',
        ]);

        $settings = RegistrationSetting::current();
        $settings->update([
            'is_account_registration_open' => (bool) $request->is_account_registration_open,
            'account_registration_start' => $request->account_registration_start,
            'account_registration_end' => $request->account_registration_end,
            'is_member_registration_open' => (bool) $request->is_member_registration_open,
            'member_registration_start' => $request->member_registration_start,
            'member_registration_end' => $request->member_registration_end,
            'announcement_title' => $request->announcement_title,
            'announcement_description' => $request->announcement_description,
            'requirements' => $request->requirements,
            'updated_by_user_id' => Auth::id(),
        ]);

        RegistrationSettingLog::create([
            'user_id' => Auth::id(),
            'action' => 'Perbarui Pengaturan Pendaftaran',
            'details' => 'Memperbarui parameter periode pendaftaran akun & penerimaan anggota baru.',
        ]);

        return redirect()->back()->with('success', 'Pengaturan periode pendaftaran berhasil disimpan.');
    }

    /**
     * Fast toggle for account registration.
     */
    public function toggleAccount(Request $request)
    {
        $this->authorizeAccess();

        $settings = RegistrationSetting::current();
        $newStatus = ! $settings->is_account_registration_open;
        $settings->update([
            'is_account_registration_open' => $newStatus,
            'updated_by_user_id' => Auth::id(),
        ]);

        $statusText = $newStatus ? 'Membuka' : 'Menutup';
        RegistrationSettingLog::create([
            'user_id' => Auth::id(),
            'action' => "{$statusText} Pendaftaran Akun",
            'details' => 'Status manual pendaftaran akun diubah menjadi '.($newStatus ? 'DIBUKA' : 'DITUTUP').'.',
        ]);

        return redirect()->back()->with('success', 'Pendaftaran akun berhasil diubah menjadi '.($newStatus ? 'DIBUKA' : 'DITUTUP').'.');
    }

    /**
     * Fast toggle for member acceptance.
     */
    public function toggleMember(Request $request)
    {
        $this->authorizeAccess();

        $settings = RegistrationSetting::current();
        $newStatus = ! $settings->is_member_registration_open;
        $settings->update([
            'is_member_registration_open' => $newStatus,
            'updated_by_user_id' => Auth::id(),
        ]);

        $statusText = $newStatus ? 'Membuka' : 'Menutup';
        RegistrationSettingLog::create([
            'user_id' => Auth::id(),
            'action' => "{$statusText} Penerimaan Anggota Baru",
            'details' => 'Status manual penerimaan anggota baru diubah menjadi '.($newStatus ? 'DIBUKA' : 'DITUTUP').'.',
        ]);

        return redirect()->back()->with('success', 'Penerimaan anggota baru berhasil diubah menjadi '.($newStatus ? 'DIBUKA' : 'DITUTUP').'.');
    }
}
