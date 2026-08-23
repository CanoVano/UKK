<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Tampilkan daftar pesanan milik kustomer (Riwayat Belanja)
     */
    public function index(Request $request)
    {
        // Cancel pending orders older than 2 hours
        Order::where('status', 'pending')
            ->where('created_at', '<', now()->subHours(2))
            ->update([
                'status' => 'cancelled'
            ]);

        $status = $request->query('status');

        $query = Order::where('user_id', Auth::id())->with(['orderItems.productVariant.product']);

        if ($status && in_array($status, ['ready_for_pickup', 'pending', 'paid', 'preparing', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }

        $orders = $query->latest()->paginate(10);
            
        return view('order.index', compact('orders', 'status'));
    }

    /**
     * Tampilkan detail pesanan / faktur
     */
    public function show($order_number)
    {
        // Cancel pending orders older than 2 hours
        Order::where('status', 'pending')
            ->where('created_at', '<', now()->subHours(2))
            ->update([
                'status' => 'cancelled'
            ]);

        $order = Order::where('order_number', $order_number)
            ->where('user_id', Auth::id())
            ->with(['orderItems.productVariant.product'])
            ->firstOrFail();

        return view('order.show', compact('order'));
    }

    /**
     * Sinkronisasi status manual untuk Midtrans (Khusus Local Testing)
     */
    public function syncStatus($order_number)
    {
        $order = Order::where('order_number', $order_number)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->status !== 'pending') {
            return redirect()->route('order.show', $order->order_number)
                ->with('success', 'Status pesanan sudah tersinkronisasi.');
        }

        try {
            $serverKey = env('MIDTRANS_SERVER_KEY');
            if (empty($serverKey)) {
                return redirect()->back()->with('error', 'Kunci server Midtrans belum dikonfigurasi.');
            }

            // Panggil API Midtrans untuk cek status
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.sandbox.midtrans.com/v2/" . $order->order_number . "/status",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "accept: application/json",
                    "authorization: Basic " . base64_encode($serverKey . ":")
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return redirect()->back()->with('error', 'Gagal menghubungi server pembayaran.');
            }

            $result = json_decode($response);
            
            // Perbarui status jika ditemukan respons valid
            if (isset($result->transaction_status)) {
                $status = $result->transaction_status;
                
                if ($status == 'capture' || $status == 'settlement') {
                    $order->update([
                        'status' => 'paid'
                    ]);
                    
                    return redirect()->route('order.show', $order->order_number)
                        ->with('success', 'Pembayaran berhasil! Pesanan Anda segera disiapkan.');
                } elseif ($status == 'deny' || $status == 'expire' || $status == 'cancel') {
                    $order->update([
                        'status' => 'cancelled'
                    ]);
                    
                    return redirect()->route('order.show', $order->order_number)
                        ->with('error', 'Pembayaran kedaluwarsa atau dibatalkan.');
                } elseif ($status == 'pending') {
                    return redirect()->route('order.show', $order->order_number)
                        ->with('info', 'Menunggu pembayaran diselesaikan.');
                }
            } else {
                // Jika transaksi tidak ditemukan (misalnya belum klik bayar di modal)
                if (isset($result->status_code) && $result->status_code == '404') {
                    return redirect()->route('order.show', $order->order_number)
                        ->with('info', 'Silakan klik Bayar Sekarang terlebih dahulu.');
                }
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memeriksa pembayaran.');
        }

        return redirect()->route('order.show', $order->order_number);
    }
}
