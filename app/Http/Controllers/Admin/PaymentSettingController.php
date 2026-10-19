<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentSettingController extends Controller
{
    public function index(): View
    {
        return view('admin.settings.payment', [
            'settings' => PaymentSetting::current(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bank_name' => ['required_with:account_holder,account_number', 'nullable', 'string', 'max:100'],
            'account_holder' => ['required_with:bank_name,account_number', 'nullable', 'string', 'max:150'],
            'account_number' => ['required_with:bank_name,account_holder', 'nullable', 'string', 'max:40', 'regex:/^[0-9 -]+$/'],
            'wallet_provider' => ['required_with:wallet_account_holder,wallet_account_number', 'nullable', 'string', 'max:100'],
            'wallet_account_holder' => ['required_with:wallet_provider,wallet_account_number', 'nullable', 'string', 'max:150'],
            'wallet_account_number' => ['required_with:wallet_provider,wallet_account_holder', 'nullable', 'string', 'max:40', 'regex:/^[0-9 -]+$/'],
            'instructions' => ['nullable', 'string', 'max:2000'],
        ]);

        PaymentSetting::current()->fill($validated)->save();

        return redirect()->route('admin.settings.payment.index')
            ->with('success', 'Informasi pembayaran berhasil disimpan.');
    }
}
