<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\FieldType;
use App\Models\Location;
use App\Models\OrderItem;
use App\Models\Pricing;
use App\Models\UserPreference;
use App\Models\UserSource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Field::query();

        // Filter berdasarkan aktivitas (Field Type)
        if ($request->filled('field_type_id')) {
            $query->where('field_type_id', $request->field_type_id);
        }

        // Filter berdasarkan lokasi
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        // Filter berdasarkan tanggal (jika diperlukan logic lanjutan bisa ditambahkan)
        if ($request->filled('date')) {
            // Logika pengecekan slot tersedia bisa ditambahkan di sini jika ada
        }

        $fields = $query->get();
        $fieldTypes = FieldType::all();
        $locations = Location::all();

        // ✅ Tambahkan ini untuk user login
        $hasSource = false;
        $hasPreference = false;

        if (Auth::check()) {
            $userId = auth()->id();
            $hasSource = UserSource::where('user_id', $userId)->exists();
            $hasPreference = UserPreference::where('user_id', $userId)->exists();
        }

        return view('home.index', compact(
            'fields',
            'fieldTypes',
            'locations',
            'hasSource',
            'hasPreference'
        ));
    }
    public function indexfield(Request $request)
    {
        $query = Field::query();
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        // Filter berdasarkan aktivitas
        if ($request->filled('field_type_id')) {
            $query->where('field_type_id', $request->field_type_id);
        }

        // Filter berdasarkan lokasi
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        // (Opsional) Filter tanggal bisa disesuaikan jika kamu punya data booking/schedule
        if ($request->filled('date')) {
            // Misalnya nanti mau tambahkan logika validasi tanggal tersedia
        }

        $fields = $query->get();
        $fieldTypes = FieldType::all();
        $locations = Location::all();

        return view('home.indexfield', compact('fields', 'fieldTypes', 'locations'));
    }

    /**
     * Menampilkan detail lapangan, jadwal operasional, dan form booking.
     */
    public function show(Request $request, $id)
    {
        $field = Field::with('jamOperasionals')->findOrFail($id);

        Log::info('Field:', $field->toArray());
        Log::info('Jam Operasional:', $field->jamOperasionals->toArray());
        $faciliti = Field::with('facilities')->findOrFail($id);

        $pricing = Pricing::where('field_id', $field->id)->get();
        Log::info('Pricing:', $pricing->toArray());

        $today = \Carbon\Carbon::today();
        $startDate = $request->input('start_date') ? Carbon::parse($request->start_date) : Carbon::today();
        $endDate = $request->input('end_date') ? Carbon::parse($request->end_date) : Carbon::today()->addDays(4);

        $dates = collect();
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $dates->push($date->copy());
        }

        // **Ambil daftar slot yang sudah "booked" (paid)**
        $bookedSlots = OrderItem::whereHas('order', function ($query) {
            $query->where('payment_status', 'paid');
        })
            ->where('field_id', $field->id)
            ->get()
            ->map(function ($orderItem) {
                return [
                    'date' => \Carbon\Carbon::parse($orderItem->slot_start)->format('Y-m-d'),
                    'time' => \Carbon\Carbon::parse($orderItem->slot_start)->format('H:i') . ' - ' .
                        \Carbon\Carbon::parse($orderItem->slot_end)->format('H:i'),
                    'status' => 'booked'
                ];
            });

        // **Ambil daftar slot yang "pending" (sudah checkout tapi belum bayar)**


        // Konversi hari bahasa Inggris ke bahasa Indonesia
        $hariIndo = [
            'monday'    => 'senin',
            'tuesday'   => 'selasa',
            'wednesday' => 'rabu',
            'thursday'  => 'kamis',
            'friday'    => 'jumat',
            'saturday'  => 'sabtu',
            'sunday'    => 'minggu',
        ];

        $schedules = [];
        $orders = OrderItem::with('order') // Ambil order yang terkait
            ->whereHas('order', function ($query) {
                $query->where('payment_status', 'pending');
            })
            ->where('field_id', $field->id)
            ->get();

        foreach ($orders as $orderItem) {
            $date = \Carbon\Carbon::parse($orderItem->slot_start)->format('Y-m-d');

            $schedules[$date][] = [
                'time' => \Carbon\Carbon::parse($orderItem->slot_start)->format('H:i') . ' - ' .
                    \Carbon\Carbon::parse($orderItem->slot_end)->format('H:i'),
                'price' => $orderItem->price,
                'status' => 'pending',
                'due_date' => optional($orderItem->order)->due_date, // Pastikan due_date ada
            ];
        }
        foreach ($dates as $date) {
            $dayNameEnglish = strtolower($date->format('l'));
            $dayNameIndo = $hariIndo[$dayNameEnglish];
            $dayType = $date->isWeekend() ? 'weekend' : 'weekday';

            Log::info("Proses Tanggal: {$date->format('Y-m-d')} | Hari: $dayNameIndo | Tipe Hari: $dayType");

            $operational = $field->jamOperasionals->first()->$dayNameIndo ?? null;

            if ($operational) {
                [$start, $end] = explode(' - ', $operational);

                $currentSlot = \Carbon\Carbon::parse($start);

                while ($currentSlot < \Carbon\Carbon::parse($end)) {
                    $nextSlot = $currentSlot->copy()->addHours($field->slot_tipe === 'per 1 jam' ? 1 : 2);
                    $timeSlot = $currentSlot->format('H:i') . ' - ' . $nextSlot->format('H:i');

                    $timeSlotType = $currentSlot->hour < 12 ? 'morning' : ($currentSlot->hour < 18 ? 'afternoon' : 'evening');

                    // Ambil data pricing untuk slot ini
                    $priceModel = $pricing->where('time_slot', $timeSlotType)
                        ->where('day_type', $dayType)
                        ->first();

                    $price = $priceModel ? $priceModel->price : null;

                    // Cek apakah slot sudah dibooking (paid) atau pending
                    $isBooked = $bookedSlots->contains(function ($booked) use ($date, $timeSlot) {
                        return $booked['date'] === $date->format('Y-m-d') && $booked['time'] === $timeSlot;
                    });

                    $isPending =  $orders->contains(function ($pending) use ($date, $timeSlot) {
                        return $pending['date'] === $date->format('Y-m-d') && $pending['time'] === $timeSlot;
                    });

                    $status = $isBooked ? 'booked' : ($isPending ? 'pending' : ($price ? 'available' : 'unavailable'));

                    $schedules[$date->format('Y-m-d')][] = [
                        'time' => $timeSlot,
                        'price' => $price,
                        'status' => $status,
                    ];

                    $currentSlot = $nextSlot;
                }
            } else {
                Log::warning("Tidak ada jam operasional untuk hari: $dayNameIndo");
            }
        }
        $minPrice = collect($schedules)
            ->flatten(1)
            ->where('status', 'available')
            ->pluck('price')
            ->filter()
            ->min();

        Log::info('Schedules setelah diisi:', $schedules);

        return view('home.field', compact('field', 'schedules', 'dates', 'faciliti', 'minPrice'));
    }





    /**
     * Mendapatkan slot jadwal berdasarkan tanggal.
     */
    public function getSlots(Request $request, $id)
    {
        $field = Field::findOrFail($id);
        $date = Carbon::parse($request->input('date'));

        // Periksa tipe hari
        $dayType = $date->isWeekend() ? 'weekend' : 'weekday';

        // Dapatkan slot berdasarkan slot_tipe (per 1 jam atau 2 jam)
        $slotDuration = $field->slot_tipe === 'per 1 jam' ? 1 : 2;

        $slots = [];
        $pricing = Pricing::where('field_id', $id)->where('day_type', $dayType)->get();

        // Loop untuk membuat slot berdasarkan jam operasional
        $operationalHours = $field->jamOperasionals->first();
        foreach (['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'] as $day) {
            if ($date->format('l') === ucfirst($day) && $operationalHours->$day) {
                [$start, $end] = explode(' - ', $operationalHours->$day);
                $currentSlot = Carbon::parse($start);

                while ($currentSlot < Carbon::parse($end)) {
                    $nextSlot = $currentSlot->copy()->addHours($slotDuration);
                    $slotTime = $currentSlot->format('H:i') . ' - ' . $nextSlot->format('H:i');

                    $timeSlot = $currentSlot->hour < 12 ? 'morning' : ($currentSlot->hour < 18 ? 'afternoon' : 'evening');

                    $slotPrice = $pricing->firstWhere('time_slot', $timeSlot)->price ?? 'Tidak ada harga';

                    $slots[] = [
                        'time' => $slotTime,
                        'price' => $slotPrice,
                    ];

                    $currentSlot = $nextSlot;
                }
                break;
            }
        }

        return response()->json($slots);
    }
}
