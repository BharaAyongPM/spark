<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Field;
use App\Models\FieldType;
use App\Models\Location;
use App\Models\Order;
use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Jumlah user berdasarkan role
        $countPenyewa = User::whereHas('roles', function ($q) {
            $q->where('name_roles', 'PENYEWA');
        })->count();

        $countPemilik = User::whereHas('roles', function ($q) {
            $q->where('name_roles', 'PEMILIK');
        })->count();

        // Jumlah lapangan (semua lapangan yang dimiliki vendor)
        $countFields = Field::count();

        // Total pesanan hanya yang status 'complete'
        $totalPesanan = Order::where('status', 'complete')->count();

        // Total pemasukan hanya dari pesanan yang 'complete'
        $totalPemasukan = Order::where('status', 'complete')->sum('total_price');

        // Analisis pesanan: sukses & batal (misal sukses = complete, batal = cancelled)
        $totalPesananSukses = Order::where('status', 'complete')->count();
        $totalPesananBatal = Order::where('status', 'cancelled')->count();

        // Grafik pemesanan per bulan (berdasarkan created_at di Order)
        $bookingData = [
            'Jan' => Order::whereMonth('created_at', 1)->where('status', 'complete')->count(),
            'Feb' => Order::whereMonth('created_at', 2)->where('status', 'complete')->count(),
            'Mar' => Order::whereMonth('created_at', 3)->where('status', 'complete')->count(),
            'Apr' => Order::whereMonth('created_at', 4)->where('status', 'complete')->count(),
            'May' => Order::whereMonth('created_at', 5)->where('status', 'complete')->count(),
            'Jun' => Order::whereMonth('created_at', 6)->where('status', 'complete')->count(),
            // Lanjutkan sesuai kebutuhan
        ];

        $labels = array_keys($bookingData);
        $completedData = array_values($bookingData);
        $cancelledData = [0, 0, 0, 0, 0, 0]; // Dummy jika belum ada data batal per bulan

        return view('admin.dashboard', compact(
            'countPenyewa',
            'countPemilik',
            'countFields',
            'totalPesanan',
            'totalPemasukan',
            'totalPesananSukses',
            'totalPesananBatal',
            'bookingData',
            'labels',
            'completedData',
            'cancelledData'
        ));
    }
    public function viewUsers()
    {
        // Ambil semua user dengan role mereka
        $users = User::with('roles')->get();

        return view('admin.users', compact('users'));
    }
    public function viewFields()
    {
        // Ambil semua data field + relasinya
        $fields = Field::with(['location', 'fieldType', 'facilities'])->get();

        // Kalau mau kirim data tambahan juga (jika ada form edit di masa depan), tapi untuk lihat aja ini nggak wajib:
        $fieldTypes = FieldType::all();
        $locations = Location::all();
        $facilities = Facility::all();

        return view('admin.fields', compact('fields', 'fieldTypes', 'locations', 'facilities'));
    }

    public function viewOrders()
    {

        $orders = Order::with(['user', 'orderItems', 'orderItems.field'])
            ->get()
            ->map(function ($order) {
                $order->orderItems->each(function ($item) {
                    $item->field_name = $item->field->name; // Asumsikan field memiliki kolom 'name'
                    $item->owner_name = $item->field->user->name; // Asumsi relasi 'owner' dalam model 'Field'
                });
                return $order;
            });

        return view('admin.orders', compact('orders'));
    }
    public function wdindex()
    {
        $withdraws = Withdraw::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.withdraws.index', compact('withdraws'));
    }

    public function updateStatus(Request $request, $id)
    {
        $withdraw = Withdraw::findOrFail($id);
        $withdraw->status = $request->status;
        $withdraw->save();

        return redirect()->back()->with('success', 'Status withdraw berhasil diperbarui.');
    }
}
