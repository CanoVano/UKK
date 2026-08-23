<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function index()
    {
        $user = Auth::user();
        $carts = $user->carts()->with(['productVariant.product'])->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $grandTotal = 0;
        foreach ($carts as $cart) {
            $grandTotal += $cart->productVariant->price * $cart->quantity;
        }

        return view('checkout.index', compact('carts', 'grandTotal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'whatsapp_number' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        $carts = $user->carts()->with(['productVariant.product'])->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        try {
            DB::beginTransaction();

            // Hitung total harga dan siapkan item details untuk midtrans
            $totalPrice = 0;
            $itemDetails = [];

            foreach ($carts as $cart) {
                // Validasi ulang stok sebelum checkout
                if ($cart->productVariant->stock < $cart->quantity) {
                    throw new \Exception("Stok varian produk {$cart->productVariant->product->name} ({$cart->productVariant->unit}) tidak mencukupi.");
                }
                
                $subtotal = $cart->productVariant->price * $cart->quantity;
                $totalPrice += $subtotal;

                $itemDetails[] = [
                    'id' => $cart->productVariant->id,
                    'price' => (int) $cart->productVariant->price,
                    'quantity' => $cart->quantity,
                    'name' => mb_strimwidth($cart->productVariant->product->name . ' - ' . $cart->productVariant->unit, 0, 50, "..."),
                ];
            }

            // Generate order number unik
            $orderNumber = 'INV-' . date('Ymd') . '-' . strtoupper(uniqid());

            // Buat record Order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'whatsapp_number' => $request->whatsapp_number,
                'notes' => $request->notes ?? 'Pesanan diambil di warung (Self-Pickup)',
            ]);

            // Pindahkan Cart ke OrderItems dan potong stok
            foreach ($carts as $cart) {
                $subtotal = $cart->productVariant->price * $cart->quantity;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $cart->product_variant_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->productVariant->price,
                    'subtotal' => $subtotal,
                ]);

                // Kurangi stok produk
                $cart->productVariant->decrement('stock', $cart->quantity);
            }

            // Kosongkan keranjang
            $user->carts()->delete();

            // Panggil API Midtrans
            $transactionParams = [
                'transaction_details' => [
                    'order_id' => $orderNumber,
                    'gross_amount' => (int) $totalPrice,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $request->whatsapp_number,
                ],
                'item_details' => $itemDetails,
                'expiry' => [
                    'start_time' => date("Y-m-d H:i:s O"),
                    'unit' => 'minutes',
                    'duration' => 120,
                ],
                'callbacks' => [
                    'finish' => route('order.show', $orderNumber),
                    'error' => route('order.show', $orderNumber),
                    'unfinish' => route('order.show', $orderNumber),
                ],
            ];

            // Dapatkan URL pembayaran dari Midtrans (Redirect Method)
            $paymentUrl = Snap::createTransaction($transactionParams)->redirect_url;

            // Simpan payment URL
            $order->update([
                'payment_url' => $paymentUrl
            ]);

            DB::commit();

            return redirect($paymentUrl);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }
}
