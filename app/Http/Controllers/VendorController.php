<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Field;
use App\Models\FieldFacility;
use App\Models\FieldType;
use App\Models\JamOperasional;
use App\Models\Location;
use App\Models\Pricing;
use App\Models\Rekening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    public function index()
    {
        return view('vendor.dashboard');
    }

    public function fieldsIndex()
    {
        // Ambil semua lapangan milik user yang login
        $fields = Field::where('user_id', auth()->id())->with('fieldType', 'location')->get();

        // Ambil data field types dan locations untuk digunakan di view
        $fieldTypes = FieldType::all();
        $locations = Location::all();
        $facilities = Facility::all(); // Ambil semua fasilitas

        return view('vendor.fields.index', compact('fields', 'fieldTypes', 'locations', 'facilities'));
    }



    public function fieldsStore(Request $request)
    {
        // Log request input
        Log::info('Incoming request:', $request->all());

        // Validasi input
        try {
            $validatedData = $request->validate([
                'field_type_id' => 'required|exists:field_types,id',
                'location_id' => 'required|exists:locations,id',
                'location' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'deskripsi' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:fields,slug',
                'owner' => 'required|string|max:255',
                'grass_quality' => 'required|string|max:255',
                'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'banner' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'slot_tipe' => 'required|in:per 1 jam,per 2 jam',
                'lat' => 'nullable|numeric',
                'lon' => 'nullable|numeric',
                'no_whatsapp' => 'nullable|string|max:15',
                'custom_domain' => 'nullable|string|max:255',
                'instagram' => 'nullable|string|max:255',
                'facebook' => 'nullable|string|max:255',
                'video' => 'nullable|string|max:255',
                'batas_pembayaran' => 'required|in:30 menit,1 jam,2 jam,10 jam,24 jam',
                'syarat_ketentuan' => 'nullable|string',
                'status' => 'required|boolean',
                'facilities' => 'array', // Validasi array fasilitas
                'facilities.*' => 'exists:facilities,id', // Validasi setiap fasilitas
            ]);

            Log::info('Validation passed.', $validatedData);
        } catch (\Exception $e) {
            Log::error('Validation failed.', ['message' => $e->getMessage()]);
            return redirect()->back()->withErrors('Validation failed. Please check your input.');
        }

        try {
            // Simpan file foto
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('fields/foto', 'public');
                Log::info('Foto file saved:', ['path' => $fotoPath]);
            } else {
                throw new \Exception('Foto file missing.');
            }

            // Simpan file banner
            if ($request->hasFile('banner')) {
                $bannerPath = $request->file('banner')->store('fields/banner', 'public');
                Log::info('Banner file saved:', ['path' => $bannerPath]);
            } else {
                throw new \Exception('Banner file missing.');
            }

            // Simpan data ke database
            $field = Field::create([
                'user_id' => auth()->id(),
                'field_type_id' => $request->field_type_id,
                'location_id' => $request->location_id,
                'name' => $request->name,
                'location' => $request->location,
                'deskripsi' => $request->deskripsi,
                'slug' => $request->slug,
                'owner' => $request->owner,
                'grass_quality' => $request->grass_quality,
                'foto' => $fotoPath ?? null,
                'banner' => $bannerPath ?? null,
                'slot_tipe' => $request->slot_tipe,
                'lat' => $request->lat,
                'lon' => $request->lon,
                'no_whatsapp' => $request->no_whatsapp,
                'custom_domain' => $request->custom_domain,
                'instagram' => $request->instagram,
                'facebook' => $request->facebook,
                'video' => $request->video,
                'batas_pembayaran' => $request->batas_pembayaran,
                'syarat_ketentuan' => $request->syarat_ketentuan,
                'status' => $request->status,
            ]);
            if ($request->filled('facilities')) {
                foreach ($request->facilities as $facilityId) {
                    FieldFacility::create([
                        'field_id' => $field->id,
                        'facility_id' => $facilityId,
                    ]);
                }
            }
            Log::info('Field created successfully:', $field->toArray());

            return redirect()->back()->with('success', 'Field created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating field.', ['message' => $e->getMessage()]);
            return redirect()->back()->withErrors('Failed to create field. Please try again.');
        }
    }



    public function fieldsDestroy(Field $field)
    {
        // Hapus file foto jika ada
        if ($field->foto && Storage::disk('public')->exists($field->foto)) {
            Storage::disk('public')->delete($field->foto);
        }

        // Hapus file banner jika ada
        if ($field->banner && Storage::disk('public')->exists($field->banner)) {
            Storage::disk('public')->delete($field->banner);
        }

        // Hapus data lapangan
        $field->delete();

        return redirect()->back()->with('success', 'Field deleted successfully.');
    }
    public function fieldsShow(Field $field)
    {
        // Pastikan hanya pemilik lapangan yang dapat melihat detail lapangan mereka
        if ($field->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Ambil semua tipe lapangan
        $fieldTypes = FieldType::all();

        // Ambil semua lokasi
        $locations = Location::all();

        // Ambil semua fasilitas
        $facilities = Facility::all();

        return view('vendor.fields.show', compact('field', 'fieldTypes', 'locations', 'facilities'));
    }

    public function fieldsUpdate(Request $request, Field $field)
    {
        // Validasi input
        $request->validate([
            'field_type_id' => 'required|exists:field_types,id',
            'location_id' => 'required|exists:locations,id',
            'name' => 'required|string|max:255',
            'slug' => "required|string|max:255|unique:fields,slug,{$field->id}",
            'owner' => 'required|string|max:255',
            'grass_quality' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'slot_tipe' => 'required|in:per 1 jam,per 2 jam',
            'lat' => 'nullable|numeric',
            'lon' => 'nullable|numeric',
        ]);

        // Update file jika ada
        if ($request->hasFile('foto')) {
            Storage::disk('public')->delete($field->foto);
            $field->foto = $request->file('foto')->store('fields/foto', 'public');
        }

        if ($request->hasFile('banner')) {
            Storage::disk('public')->delete($field->banner);
            $field->banner = $request->file('banner')->store('fields/banner', 'public');
        }

        // Update data lapangan
        $field->update($request->all());

        return redirect()->back()->with('success', 'Field updated successfully.');
    }


    public function indexvendor()
    {
        // Ambil data user yang sedang login
        $vendor = Auth::user();
        $rekening = Rekening::where('id_user', $vendor->id)->first();

        return view('vendor.viewvendor', compact('vendor', 'rekening'));
    }

    /**
     * Update data vendor.
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $vendor = Auth::user();
        $vendor->update($request->only('name', 'email'));

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update password vendor.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $vendor = Auth::user();

        if (!Hash::check($request->current_password, $vendor->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $vendor->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->back()->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Tambah atau update rekening.
     */
    public function updateRekening(Request $request)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:100',
            'rekening' => 'required|string|max:50',
            'nama' => 'required|string|max:100',
            'email' => 'nullable|email|max:100',
        ]);

        Rekening::updateOrCreate(
            ['id_user' => Auth::id()],
            $request->only('nama_bank', 'rekening', 'nama', 'email')
        );

        return redirect()->back()->with('success', 'Data rekening berhasil diperbarui.');
    }

    //HARGA
    public function hargaIndex()
    {
        $fields = Field::where('user_id', Auth::id())->get();
        $pricing = Pricing::whereIn('field_id', $fields->pluck('id'))->get();

        return view('vendor.viewharga', compact('fields', 'pricing'));
    }

    /**
     * Tambah harga baru.
     */
    public function tambahHarga(Request $request)
    {
        $request->validate([
            'field_id' => 'required|exists:fields,id',
            'price' => 'required|numeric|min:0',
            'time_slot' => 'required|in:morning,afternoon,evening',
            'day_type' => 'required|in:weekday,weekend',
        ]);

        // Validasi jika kombinasi time_slot dan day_type sudah ada
        $exists = Pricing::where('field_id', $request->field_id)
            ->where('time_slot', $request->time_slot)
            ->where('day_type', $request->day_type)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['error' => 'Harga untuk waktu dan jenis hari ini sudah ada.']);
        }

        Pricing::create([
            'field_id' => $request->field_id,
            'price' => $request->price,
            'time_slot' => $request->time_slot,
            'day_type' => $request->day_type,
        ]);

        return redirect()->back()->with('success', 'Harga berhasil ditambahkan.');
    }

    /**
     * Update harga yang sudah ada.
     */
    public function updateHarga(Request $request, $id)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        $pricing = Pricing::findOrFail($id);

        // Pastikan harga milik lapangan user yang sedang login
        if (!Field::where('id', $pricing->field_id)->where('user_id', Auth::id())->exists()) {
            abort(403, 'Unauthorized action.');
        }

        $pricing->update([
            'price' => $request->price,
        ]);

        return redirect()->back()->with('success', 'Harga berhasil diperbarui.');
    }

    //JAM OPERASIONAL
    public function jamOperasionalIndex()
    {
        $fields = Field::where('user_id', Auth::id())->get();
        $jamOperasionals = JamOperasional::whereIn('field_id', $fields->pluck('id'))->get();

        return view('vendor.viewjamoperasional', compact('fields', 'jamOperasionals'));
    }

    /**
     * Menambah jam operasional.
     */

    public function jamOperasionalStore(Request $request)
    {
        $request->validate([
            'field_id' => 'required|exists:fields,id',
            '*.buka' => 'nullable|date_format:H:i',
            '*.tutup' => 'nullable|date_format:H:i',
        ]);

        // Format data jam operasional
        $data = [];
        foreach (['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'] as $day) {
            $data[$day] = $request->input("{$day}_buka") && $request->input("{$day}_tutup")
                ? $request->input("{$day}_buka") . ' - ' . $request->input("{$day}_tutup")
                : null;
        }

        $data['field_id'] = $request->field_id;

        JamOperasional::create($data);

        return redirect()->back()->with('success', 'Jam operasional berhasil ditambahkan.');
    }

    /**
     * Update jam operasional.
     */
    public function jamOperasionalUpdate(Request $request, $id)
    {
        $request->validate([
            '*.buka' => 'nullable|date_format:H:i',
            '*.tutup' => 'nullable|date_format:H:i',
        ]);

        $jamOperasional = JamOperasional::findOrFail($id);

        // Pastikan jam operasional milik lapangan vendor yang sedang login
        if (!Field::where('id', $jamOperasional->field_id)->where('user_id', Auth::id())->exists()) {
            abort(403, 'Unauthorized action.');
        }

        // Format data jam operasional
        $data = [];
        foreach (['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'] as $day) {
            $data[$day] = $request->input("{$day}_buka") && $request->input("{$day}_tutup")
                ? $request->input("{$day}_buka") . ' - ' . $request->input("{$day}_tutup")
                : null;
        }

        $jamOperasional->update($data);

        return redirect()->back()->with('success', 'Jam operasional berhasil diperbarui.');
    }
    /**
     * Menghapus jam operasional.
     */
    public function jamOperasionalDestroy($id)
    {
        $jamOperasional = JamOperasional::findOrFail($id);

        // Pastikan jam operasional milik lapangan vendor yang sedang login
        if (!Field::where('id', $jamOperasional->field_id)->where('user_id', Auth::id())->exists()) {
            abort(403, 'Unauthorized action.');
        }

        $jamOperasional->delete();

        return redirect()->back()->with('success', 'Jam operasional berhasil dihapus.');
    }
}
