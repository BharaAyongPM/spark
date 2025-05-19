<?php

namespace App\Http\Controllers;

use App\Models\Rekening;
use App\Models\Withdraw;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    // Menampilkan halaman withdraw (form + history)
    public function index()
    {
        $vendor = auth()->user();

        // Ambil rekening vendor
        $rekening = Rekening::where('id_user', $vendor->id)->first();

        // Ambil withdraw history (paginate 10)
        $withdraws = Withdraw::where('user_id', $vendor->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('vendor.withdraw.index', compact('rekening', 'withdraws', 'vendor'));
    }

    // Simpan permintaan withdraw
    public function store(Request $request)
    {
        $vendor = auth()->user();

        $request->validate([
            'amount' => 'required|numeric|min:10000', // contoh minimal 10 ribu
        ]);

        // Ambil rekening vendor
        $rekening = Rekening::where('id_user', $vendor->id)->first();
        if (!$rekening) {
            return back()->withErrors(['rekening' => 'Anda belum mengisi data rekening.']);
        }

        // Hitung saldo siap tarik
        $saldo = $vendor->saldo_siap_tarik;

        if ($request->amount > $saldo) {
            return back()->withErrors(['amount' => 'Saldo tidak cukup untuk withdraw.']);
        }

        // Simpan withdraw
        Withdraw::create([
            'user_id' => $vendor->id,
            'rekening_id' => $rekening->id,
            'amount' => $request->amount,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Pengajuan withdraw berhasil. Tunggu persetujuan admin.');
    }
}
