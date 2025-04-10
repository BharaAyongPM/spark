<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first(); // Ambil hanya satu record karena ini konfigurasi umum
        return view('settings.index', compact('setting'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fee_service' => 'numeric|required',
            'fee_xendit' => 'numeric|required',
            'fee_penarikan' => 'numeric|required',
            'baner1' => 'nullable|image|max:5048',
            'baner2' => 'nullable|image|max:5048',
            'baner3' => 'nullable|image|max:5048',
            'deskripsi' => 'required|string',
            'nama_web' => 'required|string',
            'email_suport' => 'required|email',
            'wa_suport' => 'required|string',
        ]);

        $setting = Setting::findOrFail($id);

        // Handle file upload
        if ($request->hasFile('baner1')) {
            $baner1 = $request->file('baner1')->store('banners', 'public');
            $setting->baner1 = $baner1;
        }
        if ($request->hasFile('baner2')) {
            $baner2 = $request->file('baner2')->store('banners', 'public');
            $setting->baner2 = $baner2;
        }
        if ($request->hasFile('baner3')) {
            $baner3 = $request->file('baner3')->store('banners', 'public');
            $setting->baner3 = $baner3;
        }

        // Update data
        $setting->update($request->except(['baner1', 'baner2', 'baner3']));

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
