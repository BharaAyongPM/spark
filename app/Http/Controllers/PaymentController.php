<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Xendit\Configuration;
use Xendit\Xendit;
use Xendit\Invoice; // Tambahkan ini untuk menghindari kesalahan pemanggilan kelas
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\Invoice as InvoiceInvoice;
use Xendit\Invoice\InvoiceApi;
use Xendit\XenditSdkException;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        Log::info("🔵 Memulai proses checkout...");

        $cart = session('cart', []);
        Log::info("🛒 Isi Cart: ", $cart);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang kosong.');
        }

        $settings = Setting::first();
        $feeService = $settings->fee_service ?? 0;
        $feeXendit  = $settings->fee_xendit ?? 0;

        $totalLapangan   = array_sum(array_column($cart, 'price'));
        $selectedAddons  = session('selected_addons', []);
        $totalAddon      = collect($selectedAddons)->sum('harga');
        $appliedDiscount = session('applied_discount');
        $discountAmount  = $appliedDiscount['amount'] ?? 0;

        $totalPrice = $totalLapangan + $totalAddon + $feeService + $feeXendit - $discountAmount;

        Log::info("💵 Total Lapangan: Rp " . number_format($totalLapangan, 0, ',', '.'));
        Log::info("➕ Addon: Rp " . number_format($totalAddon, 0, ',', '.'));
        Log::info("➖ Diskon: Rp " . number_format($discountAmount, 0, ',', '.'));
        Log::info("✅ Total Final: Rp " . number_format($totalPrice, 0, ',', '.'));

        try {
            $invoiceNumber = 'INV-' . strtoupper(uniqid());

            $order = Order::create([
                'user_id'         => Auth::id(),
                'total_price'     => (int) $totalPrice,
                'status'          => 'pending',
                'payment_status'  => 'pending',
                'invoice_number'  => $invoiceNumber,
                'addon_price'     => $totalAddon,
                'discount_amount' => $discountAmount,
                'due_date'        => now()->addHours(1),
            ]);

            foreach ($cart as $item) {
                $timeSlots = explode('-', $item['time_slot']);
                $slotStartDateTime = $item['date'] . ' ' . trim($timeSlots[0]) . ':00';
                $slotEndDateTime   = $item['date'] . ' ' . trim($timeSlots[1]) . ':00';

                OrderItem::create([
                    'order_id'   => $order->id,
                    'field_id'   => $item['field_id'],
                    'slot_start' => $slotStartDateTime,
                    'slot_end'   => $slotEndDateTime,
                    'price'      => (int) $item['price'],
                ]);
            }

            // ✅ Gunakan API Key langsung, bukan dari .env
            $apiKey = 'xnd_development_eJLTLkdkNY2HDlEiNwd84gcU1u7J2ELCkr3kflcJ4JnMa7SLRYPY2QZoTp6xf6c';
            \Xendit\Configuration::setXenditKey($apiKey);
            $apiInstance = new \Xendit\Invoice\InvoiceApi();

            $create_invoice_request = new \Xendit\Invoice\CreateInvoiceRequest([
                'external_id'           => $order->invoice_number,
                'payer_email'           => Auth::user()->email ?? 'guest@example.com',
                'description'           => 'Pembayaran untuk Order ' . $order->invoice_number,
                'amount'                => (int) $totalPrice,
                'invoice_duration'      => 3600,
                'currency'              => 'IDR',
                'reminder_time'         => 30,
                'success_redirect_url'  => route('payment.success', ['invoice' => $order->invoice_number]),
                'failure_redirect_url'  => route('payment.failure'),
            ]);

            $invoice = $apiInstance->createInvoice($create_invoice_request);

            Payment::create([
                'order_id'          => $order->id,
                'payment_method'    => 'xendit',
                'amount'            => $totalPrice,
                'payment_status'    => 'pending',
                'xendit_payment_id' => $invoice->getId(),
            ]);

            session()->forget(['cart', 'selected_addons', 'applied_discount']);

            return redirect($invoice->getInvoiceUrl());
        } catch (\Xendit\XenditSdkException $e) {
            Log::error("❌ Gagal membuat pembayaran: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }



    public function checkExpiredOrders()
    {
        $expiredOrders = Order::where('payment_status', 'pending')
            ->where('due_date', '<', now())
            ->get();

        foreach ($expiredOrders as $order) {
            $order->update(['payment_status' => 'expired', 'status' => 'cancelled']);
            Payment::where('order_id', $order->id)->update(['payment_status' => 'expired']);
        }

        Log::info("✅ Semua order yang expired sudah diperbarui oleh JavaScript Fetch.");

        return response()->json(['message' => 'Expired orders have been checked and updated.']);
    }





    public function paymentSuccess(Request $request)
    {
        Log::info("✅ Halaman pembayaran sukses dikunjungi.");

        // Ambil invoice_number dari query parameter
        $invoiceNumber = $request->query('invoice');

        if (!$invoiceNumber) {
            return redirect()->route('home.index')->with('error', 'Tidak ada order yang ditemukan.');
        }

        // Cari order berdasarkan invoice_number
        $order = Order::where('invoice_number', $invoiceNumber)->first();

        if (!$order) {
            Log::error("❌ Order tidak ditemukan untuk invoice: " . $invoiceNumber);
            return redirect()->route('home')->with('error', 'Order tidak ditemukan.');
        }

        // Update status pembayaran jika belum paid
        if ($order->payment_status !== 'paid') {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'completed',
            ]);

            // Update payment juga
            Payment::where('order_id', $order->id)->update([
                'payment_status' => 'completed',
            ]);

            Log::info("✅ Order berhasil diperbarui menjadi PAID.", ['order_id' => $order->id]);
        }

        // Kirim data order ke view
        return view('payments.success', compact('order'));
    }



    public function paymentFailure()
    {
        return view('payments.failure');
    }
    public function webhook(Request $request)
    {
        $data = $request->all();

        if ($data['status'] === 'PAID') {
            $payment = Payment::where('xendit_payment_id', $data['id'])->first();

            if ($payment) {
                $payment->update(['payment_status' => 'paid', 'payment_date' => now()]);
                $payment->order->update(['status' => 'completed', 'payment_status' => 'paid']);
            }
        }

        return response()->json(['success' => true]);
    }
    public function handleXenditWebhook(Request $request)
    {
        Log::info("📡 Menerima webhook dari Xendit", $request->all());

        // Validasi request dari Xendit
        $request->validate([
            'external_id' => 'required|string',
            'status' => 'required|string',
        ]);

        // Ambil invoice berdasarkan `external_id`
        $order = Order::where('invoice_number', $request->external_id)->first();

        if (!$order) {
            Log::error("❌ Order tidak ditemukan untuk invoice: " . $request->external_id);
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }

        // Jika pembayaran berhasil (PAID), update status di database
        if ($request->status === 'PAID') {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'completed',
            ]);

            // Update status di tabel payments
            Payment::where('order_id', $order->id)->update([
                'payment_status' => 'paid',
            ]);

            Log::info("✅ Pembayaran sukses! Order dan Payment telah diperbarui menjadi PAID.", ['order_id' => $order->id]);

            return response()->json(['message' => 'Pembayaran sukses, data diperbarui'], 200);
        }

        Log::warning("⚠️ Status pembayaran tidak dikenali: " . $request->status);
        return response()->json(['message' => 'Status tidak dikenali'], 400);
    }
}
