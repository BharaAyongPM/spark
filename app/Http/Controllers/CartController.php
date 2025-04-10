<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'field_id' => 'required|integer',
            'date' => 'required|date',
            'time_slot' => 'required|string',
            'price' => 'required|numeric',
        ]);

        // Ambil keranjang dari session
        $cart = Session::get('cart', []);

        // Buat key unik berdasarkan field_id, date, dan time_slot
        $key = "{$request->field_id}|{$request->date}|{$request->time_slot}";

        // Cek apakah sudah ada di keranjang
        if (!in_array($key, array_column($cart, 'key'))) {
            $cart[] = [
                'key' => $key,
                'field_id' => $request->field_id,
                'date' => $request->date,
                'time_slot' => $request->time_slot,
                'price' => $request->price,
            ];

            Session::put('cart', $cart);
        }

        // Hitung ulang jumlah item di keranjang
        $cartCount = count($cart);

        return response()->json([
            'success' => true,
            'cartCount' => $cartCount,
        ]);
    }
    public function removeFromCart(Request $request)
    {
        try {
            $request->validate([
                'field_id' => 'required|integer',
                'date' => 'required|date',
                'time_slot' => 'required|string',
            ]);

            // Ambil keranjang dari session
            $cart = session()->get('cart', []);

            // Log permintaan
            Log::info('Remove request:', $request->all());

            // Cari item di keranjang berdasarkan field_id, date, dan time_slot
            foreach ($cart as $index => $item) {
                if (
                    $item['field_id'] == $request->field_id &&
                    $item['date'] == $request->date &&
                    $item['time_slot'] == $request->time_slot
                ) {
                    // Hapus item dari array
                    unset($cart[$index]);
                }
            }

            // Simpan kembali ke session
            session()->put('cart', array_values($cart));

            // Kirimkan respons JSON
            return response()->json([
                'success' => true,
                'message' => 'Item berhasil dihapus dari keranjang.',
                'cartCount' => count(session()->get('cart', [])),
            ]);
        } catch (\Exception $e) {
            Log::error('Error in removeFromCart:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus item dari keranjang.',
            ], 500);
        }
    }
    public function index()
    {
        // Ambil data keranjang dari session
        $cart = Session::get('cart', []);

        return view('cart.index', compact('cart'));
    }

    // public function checkout(Request $request)
    // {
    //     // Ambil data keranjang dari session
    //     $cart = Session::get('cart', []);

    //     if (empty($cart)) {
    //         return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
    //     }

    //     // Proses checkout (hanya placeholder untuk sementara)
    //     Session::forget('cart'); // Kosongkan keranjang setelah checkout

    //     return redirect()->route('home')->with('success', 'Proses checkout berhasil!');
    // }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []); // Ambil data dari sesi
        $orderHistory = []; // Untuk menyimpan histori pesanan

        if ($request->has('order_id')) {
            // Ambil order berdasarkan ID yang diberikan di URL
            $order = Order::where('id', $request->order_id)
                ->where('user_id', Auth::id())
                ->where('status', 'pending')
                ->first();

            if (!$order) {
                return redirect()->route('orders')->with('error', 'Pesanan tidak ditemukan.');
            }

            // Ambil semua item yang terkait dengan order ini dari tabel order_items
            $orderItems = OrderItem::where('order_id', $order->id)->get();

            // Ubah format data agar sesuai dengan tampilan di view
            foreach ($orderItems as $item) {
                $field = Field::find($item->field_id); // Ambil nama lapangan dari tabel field

                $orderHistory[] = [
                    'field_id' => $item->field_id,
                    'field_name' => $field ? $field->name : 'Lapangan Tidak Diketahui',
                    'date' => \Carbon\Carbon::parse($item->slot_start)->format('Y-m-d'),
                    'time_slot' => \Carbon\Carbon::parse($item->slot_start)->format('H:i') . ' - ' .
                        \Carbon\Carbon::parse($item->slot_end)->format('H:i'),
                    'price' => $item->price
                ];
            }
        }
        // 🔥 Simpan orderHistory ke session sebagai cart
        // session()->put('cart', $orderHistory);
        // $mergedCart = array_merge($cart, $orderHistory);
        // session()->put('cart', $mergedCart);
        // Hitung total harga dari sesi keranjang dan histori pesanan
        $totalSewaCart = !empty($cart) ? array_sum(array_column($cart, 'price')) : 0;
        $totalSewaHistory = !empty($orderHistory) ? array_sum(array_column($orderHistory, 'price')) : 0;

        // Ambil biaya tambahan dari settings
        $settings = Setting::first();
        $feeService = $settings->fee_service ?? 0;
        $feeXendit = $settings->fee_xendit ?? 0;

        // Hitung total keseluruhan
        $total = $totalSewaCart + $totalSewaHistory + $feeService + $feeXendit;

        return view('home.checkout', compact('cart', 'orderHistory', 'totalSewaCart', 'totalSewaHistory', 'feeService', 'feeXendit', 'total'));
    }
}
