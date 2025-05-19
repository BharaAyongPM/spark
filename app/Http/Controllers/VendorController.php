<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Field;
use App\Models\FieldFacility;
use App\Models\FieldType;
use App\Models\JamOperasional;
use App\Models\Location;
use App\Models\Order;
use App\Models\Pricing;
use App\Models\Rekening;
use App\Models\WasitPhoto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VendorController extends Controller
{


    public function index()
    {
        $vendorId = auth()->id();

        $totalVenue = \App\Models\Field::where('user_id', $vendorId)->count();

        $totalOrders = \App\Models\OrderItem::whereHas('field', fn($q) => $q->where('user_id', $vendorId))
            ->distinct('order_id')
            ->count('order_id');

        $totalPendapatan = \App\Models\OrderItem::whereHas('field', fn($q) => $q->where('user_id', $vendorId))
            ->sum('price');

        $siapWithdraw = $totalPendapatan;

        // Ambil tanggal 30 hari terakhir
        $today = Carbon::today();
        $startDate = $today->copy()->subDays(29); // 30 hari ke belakang

        // Ambil data order item yang berkaitan dengan vendor & status completed/cancelled
        $chartRaw = \App\Models\OrderItem::selectRaw("DATE(orders.created_at) as tanggal, orders.status, COUNT(*) as jumlah")
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereHas('field', fn($q) => $q->where('user_id', $vendorId))
            ->whereBetween('orders.created_at', [$startDate, $today])
            ->whereIn('orders.status', ['completed', 'cancelled'])
            ->groupBy('tanggal', 'orders.status')
            ->orderBy('tanggal')
            ->get()
            ->groupBy('tanggal');

        // Buat label & dataset untuk 30 hari terakhir
        $labels = [];
        $completedData = [];
        $cancelledData = [];

        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $labels[] = Carbon::parse($date)->format('d-m');

            $completed = $chartRaw->get($date)?->firstWhere('status', 'completed')?->jumlah ?? 0;
            $cancelled = $chartRaw->get($date)?->firstWhere('status', 'cancelled')?->jumlah ?? 0;

            $completedData[] = $completed;
            $cancelledData[] = $cancelled;
        }

        return view('vendor.dashboard', compact(
            'totalVenue',
            'totalOrders',
            'totalPendapatan',
            'siapWithdraw',
            'labels',
            'completedData',
            'cancelledData'
        ));
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
                'foto' => 'required|image|mimes:jpeg,png,jpg|max:10048',
                'banner' => 'required|image|mimes:jpeg,png,jpg|max:10048',
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
                'gallery' => 'required|array|max:3',
                'gallery.*' => 'image|mimes:jpeg,png,jpg|max:10048',

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
            $galleryPaths = [];
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $galleryImage) {
                    $path = $galleryImage->store('fields/gallery', 'public');
                    $galleryPaths[] = $path;
                }
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
                'gallery' => json_encode($galleryPaths), // Simpan sebagai JSON
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
        Log::info('--- MEMULAI UPDATE FIELD ---');
        Log::info('Data request awal:', $request->all());

        try {
            $validatedData = $request->validate([
                'field_type_id' => 'required|exists:field_types,id',
                'location_id' => 'required|exists:locations,id',
                'location' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'deskripsi' => 'required|string|max:255',
                'slug' => "required|string|max:255|unique:fields,slug,{$field->id}",
                'owner' => 'required|string|max:255',
                'grass_quality' => 'required|string|max:255',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'banner' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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
                'facilities' => 'array',
                'facilities.*' => 'exists:facilities,id',
                'gallery_update' => 'array',
                'gallery_update.*' => 'nullable|image|mimes:jpeg,png,jpg|max:9048',

                'gallery_existing' => 'array',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('VALIDASI GAGAL:', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
        try {
            // Foto utama
            if ($request->hasFile('foto')) {
                Log::info('Menghapus foto lama:', ['path' => $field->foto]);
                Storage::disk('public')->delete($field->foto);

                $validatedData['foto'] = $request->file('foto')->store('fields/foto', 'public');
                Log::info('Foto baru diupload:', ['path' => $validatedData['foto']]);
            }

            // Banner
            if ($request->hasFile('banner')) {
                Log::info('Menghapus banner lama:', ['path' => $field->banner]);
                Storage::disk('public')->delete($field->banner);

                $validatedData['banner'] = $request->file('banner')->store('fields/banner', 'public');
                Log::info('Banner baru diupload:', ['path' => $validatedData['banner']]);
            }

            // Galeri
            $request->merge([
                'gallery_existing' => $request->gallery_existing ?? [],
            ]);

            $finalGallery = [];

            // 1. Proses update dari galeri yang sudah ada
            foreach ($request->gallery_existing as $index => $existingPath) {
                if ($request->hasFile("gallery_update.$index")) {
                    Log::info("Gallery update tersedia untuk index $index. Path lama: $existingPath");

                    Storage::disk('public')->delete($existingPath);
                    $newPath = $request->file("gallery_update.$index")->store('fields/gallery', 'public');
                    $finalGallery[$index] = $newPath;

                    Log::info("Gambar galeri diupdate:", ['index' => $index, 'newPath' => $newPath]);
                } else {
                    $finalGallery[$index] = $existingPath;
                    Log::info("Gambar galeri lama dipertahankan:", ['index' => $index, 'path' => $existingPath]);
                }
            }

            // 2. Tambah galeri baru jika kurang dari 3
            if ($request->hasFile('gallery_update')) {
                Log::info('File gallery_update yang masuk:', $request->file('gallery_update'));

                foreach ($request->file('gallery_update') as $file) {
                    if ($file && $file->isValid()) {
                        $newPath = $file->store('fields/gallery', 'public');
                        $finalGallery[] = $newPath;
                        Log::info("Gambar galeri tambahan berhasil disimpan:", ['path' => $newPath]);
                    } else {
                        Log::warning("Gagal simpan file galeri tambahan karena tidak valid.");
                    }
                }
            } else {
                Log::warning("Tidak ada file gallery_update yang diterima.");
            }



            ksort($finalGallery);
            $validatedData['gallery'] = json_encode(array_values($finalGallery));

            Log::info('Galeri akhir yang disimpan:', $finalGallery);

            // Update field
            $field->update($validatedData);
            Log::info('Data field berhasil diupdate.', $validatedData);

            // Sinkron fasilitas
            if ($request->filled('facilities')) {
                $field->facilities()->sync($request->facilities);
                Log::info('Fasilitas disinkronkan:', $request->facilities);
            } else {
                $field->facilities()->detach();
                Log::info('Fasilitas dihapus semua.');
            }

            return redirect()->route('vendor.fields.index')->with('success', 'Field updated successfully.');
        } catch (\Exception $e) {
            Log::error('GAGAL update field.', [
                'error_message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return redirect()->back()->withErrors('Failed to update field. Please try again.');
        }
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
            'phone' => 'nullable|string|max:20',
        ]);

        $vendor = Auth::user();
        $vendor->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update foto profil vendor (avatar).
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048', // maksimal 2MB
        ]);

        $vendor = Auth::user();

        // Hapus foto lama jika ada
        if ($vendor->avatar && Storage::disk('public')->exists('avatar/' . $vendor->avatar)) {
            Storage::disk('public')->delete('avatar/' . $vendor->avatar);
        }

        // Simpan foto baru
        $filename = uniqid() . '.' . $request->avatar->extension();
        $request->avatar->storeAs('avatar', $filename, 'public');

        // Update kolom avatar di user
        $vendor->update([
            'avatar' => $filename,
        ]);

        return redirect()->back()->with('success', 'Foto profil berhasil diperbarui.');
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
    public function viewVendorOrders()
    {
        $userId = auth()->id();

        $orders = \App\Models\Order::with(['user', 'orderItems.field.user'])
            ->whereHas('orderItems.field', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->latest()
            ->paginate(10);

        // Mapping tetap dilakukan di collection hasil pagination
        $orders->getCollection()->transform(function ($order) use ($userId) {
            // Filter item yang hanya punya field milik vendor ini
            $order->orderItems = $order->orderItems->filter(function ($item) use ($userId) {
                return optional($item->field)->user_id === $userId;
            });

            // Tambah field_name dan owner_name
            $order->orderItems->each(function ($item) {
                $item->field_name = optional($item->field)->name ?? '-';
                $item->owner_name = optional($item->field->user)->name ?? '-';
            });

            return $order;
        });

        return view('vendor.orders', compact('orders'));
    }
    public function manageWasitPhoto()
    {
        $vendorFields = Field::where('user_id', auth()->id())->get();
        $wasitData = WasitPhoto::where('jenis', 'wasit')
            ->whereIn('field_id', $vendorFields->pluck('id'))
            ->get();

        $photoData = WasitPhoto::where('jenis', 'photo')
            ->whereIn('field_id', $vendorFields->pluck('id'))
            ->get();

        return view('vendor.wasit_photo.index', compact('vendorFields', 'wasitData', 'photoData'));
    }

    public function storeWasitPhoto(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|in:wasit,photo',
            'field_id' => 'required|exists:fields,id',
            'keterangan' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
        ]);

        // Cek apakah data sudah ada untuk kombinasi field + jenis
        $exists = WasitPhoto::where('jenis', $request->jenis)
            ->where('field_id', $request->field_id)
            ->first();

        if ($exists) {
            return back()->with('error', ucfirst($request->jenis) . ' untuk lapangan ini sudah ada.');
        }

        WasitPhoto::create($request->only('nama', 'jenis', 'field_id', 'keterangan', 'harga'));

        return back()->with('success', ucfirst($request->jenis) . ' berhasil ditambahkan.');
    }

    public function updateWasitPhoto(Request $request, WasitPhoto $wasitPhoto)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
        ]);

        $wasitPhoto->update($request->only('nama', 'keterangan', 'harga'));

        return back()->with('success', ucfirst($wasitPhoto->jenis) . ' berhasil diperbarui.');
    }

    public function deleteWasitPhoto(WasitPhoto $wasitPhoto)
    {
        $wasitPhoto->delete();
        return back()->with('success', ucfirst($wasitPhoto->jenis) . ' berhasil dihapus.');
    }
}
