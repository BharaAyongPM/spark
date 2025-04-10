<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Order;
use App\Models\User;
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

        // Jumlah lapangan
        $countFields = Field::count();

        // Data dummy untuk grafik pemesanan
        $bookingData = [
            'Jan' => 5,
            'Feb' => 10,
            'Mar' => 3,
            'Apr' => 7,
            'May' => 8,
            'Jun' => 12
        ];

        return view('admin.dashboard', compact('countPenyewa', 'countPemilik', 'countFields', 'bookingData'));
    }
    public function viewUsers()
    {
        // Ambil semua user dengan role mereka
        $users = User::with('roles')->get();

        return view('admin.users', compact('users'));
    }
    public function viewFields()
    {
        // Data dummy untuk lapangan
        $fields = collect([
            ['user_id' => 1, 'field_type_id' => 101, 'name' => 'Lapangan A', 'location' => 'Jakarta', 'owner' => 'Ahmad', 'grass_quality' => 'High'],
            ['user_id' => 2, 'field_type_id' => 102, 'name' => 'Lapangan B', 'location' => 'Bandung', 'owner' => 'Budi', 'grass_quality' => 'Medium'],
            ['user_id' => 3, 'field_type_id' => 103, 'name' => 'Lapangan C', 'location' => 'Surabaya', 'owner' => 'Charlie', 'grass_quality' => 'Low'],
            // Tambahkan lebih banyak data sesuai format di atas
            // Total 10 data dummy
        ]);

        return view('admin.fields', compact('fields'));
    }
    public function viewOrders()
    {
        // Dummy data untuk demonstrasi
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
}
