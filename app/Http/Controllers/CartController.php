<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Tampilkan halaman keranjang belanja.
     */
    public function index()
    {
        $carts = Auth::user()->carts()->with(['productVariant.product'])->latest()->get();
        return view('cart.index', compact('carts'));
    }

    /**
     * Tambahkan barang ke keranjang.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $variantId = $request->input('product_variant_id');
        $qty = $request->input('quantity', 1);

        $variant = \App\Models\ProductVariant::findOrFail($variantId);

        // Validasi ketersediaan stok dasar
        if ($variant->stock < 1) {
            return back()->with('error', 'Maaf, stok varian ini habis.');
        }

        $cart = Auth::user()->carts()->where('product_variant_id', $variantId)->first();

        if ($cart) {
            // Jika sudah ada, cek apakah kuantitas baru melebihi stok
            if ($cart->quantity + $qty > $variant->stock) {
                return back()->with('error', 'Kuantitas melebihi sisa stok varian.');
            }
            
            $cart->increment('quantity', $qty);
            return back()->with('success', 'Kuantitas produk di keranjang diperbarui.');
        }

        // Jika belum ada, buat baru
        if ($qty > $variant->stock) {
             return back()->with('error', 'Kuantitas melebihi sisa stok varian.');
        }

        Auth::user()->carts()->create([
            'product_variant_id' => $variantId,
            'quantity' => $qty
        ]);

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    /**
     * Update kuantitas barang di keranjang.
     */
    public function update(Request $request, Cart $cart)
    {
        // Pastikan keranjang milik user yang sedang login
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $action = $request->input('action');
        $variant = $cart->productVariant;

        if ($action === 'increase') {
            if ($cart->quantity + 1 > $variant->stock) {
                return back()->with('error', 'Kuantitas melebihi sisa stok.');
            }
            $cart->increment('quantity');
        } elseif ($action === 'decrease') {
            if ($cart->quantity > 1) {
                $cart->decrement('quantity');
            } else {
                $cart->delete();
                return back()->with('success', 'Produk dihapus dari keranjang.');
            }
        }

        return back()->with('success', 'Kuantitas diperbarui.');
    }

    /**
     * Hapus barang dari keranjang.
     */
    public function destroy(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->delete();

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }
}
