<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::with(['field', 'user'])->orderBy('id', 'desc')->get();
        $fields = Field::all(); // Tambahkan ini agar bisa digunakan di view

        return view('admin.discounts.index', compact('discounts', 'fields'));
    }


    public function create()
    {
        $fields = Field::all();
        return view('admin.discounts.create', compact('fields'));
    }

    public function store(Request $request)
    {
        Log::info('🔄 [DISKON] Memulai proses penyimpanan diskon.', $request->all());

        try {
            $validated = $request->validate([
                'code' => 'required|string|max:20|unique:discounts,code',
                'percentage' => 'required|numeric|min:1|max:100',
                'start_date' => 'required|date|before_or_equal:end_date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'field_id' => 'nullable|exists:fields,id',
                'automatic' => 'nullable|boolean',
                'scope' => 'required|in:all,specific',
            ]);

            $discount = Discount::create([
                'code' => $validated['code'],
                'percentage' => $validated['percentage'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'field_id' => $validated['field_id'] ?? null,
                'automatic' => $request->has('automatic') ? true : false,

                'scope' => $validated['scope'],
                'user_id' => Auth::id(), // Gantilah 'created_by' dengan 'user_id' jika memang pakai kolom user_id
            ]);

            Log::info('✅ [DISKON] Diskon berhasil dibuat.', $discount->toArray());

            return redirect()->route('discounts.index')->with('success', 'Diskon berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('❌ [DISKON] Validasi gagal.', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('❌ [DISKON] Gagal menyimpan diskon.', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan diskon.')->withInput();
        }
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();
        return redirect()->back()->with('success', 'Diskon berhasil dihapus.');
    }
    public function update(Request $request, Discount $discount)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:discounts,code,' . $discount->id,
            'percentage' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'field_id' => 'nullable|exists:fields,id',
            'automatic' => 'nullable|boolean',
            'scope' => 'required|in:all,specific',
        ]);

        $discount->update([
            'code' => $validated['code'],
            'percentage' => $validated['percentage'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'field_id' => $validated['field_id'] ?? null,
            'automatic' => $request->has('automatic') ? true : false,
            'scope' => $validated['scope'],
            // 'user_id' => Auth::id(), // Optional: Hanya jika kamu ingin update pembuatnya
        ]);

        return redirect()->route('discounts.index')->with('success', 'Diskon berhasil diperbarui.');
    }
}
