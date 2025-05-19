<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Maatwebsite\Excel\Facades\Excel;

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
    public function exportExcel(Request $request)
    {
        $userId = auth()->id();

        $query = \App\Models\Order::with(['user', 'orderItems.field'])
            ->whereHas('orderItems.field', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });

        // Filter tanggal
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->get();

        return Excel::download(new OrdersExport($orders), 'data-pemesanan.xlsx');
    }
}
