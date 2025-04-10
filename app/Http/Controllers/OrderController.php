<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class OrderController extends Controller
{
    // Menampilkan histori pesanan
    public function index()
    {
        $orders = Auth::user()->orders()->orderBy('created_at', 'desc')->get();

        return view('orders.index', compact('orders'));
    }

    // Menampilkan detail pesanan (untuk modal)
    public function show(Order $order)
    {
        // Pastikan user hanya bisa melihat pesanannya sendiri
        if ($order->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($order);
    }
}
