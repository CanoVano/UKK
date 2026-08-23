@extends('layouts.store')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Keranjang Belanja</h1>

        @if($carts->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <div class="w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-6 text-emerald-500">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Keranjang Anda Kosong</h2>
                <p class="text-gray-500 mb-8">Wah, keranjang belanjamu masih kosong nih. Yuk isi dengan sayur dan buah segar!</p>
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-8 py-3 text-base font-bold rounded-full text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-sm">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Cart Items List -->
                <div class="flex-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <ul role="list" class="divide-y divide-gray-100">
                            @php $grandTotal = 0; @endphp
                            @foreach($carts as $cart)
                                @php 
                                    $subtotal = $cart->productVariant->price * $cart->quantity;
                                    $grandTotal += $subtotal;
                                @endphp
                                <li class="p-6 flex flex-col sm:flex-row sm:items-center gap-6">
                                    
                                    <!-- Image -->
                                    <div class="w-24 h-24 flex-shrink-0 bg-gray-50 rounded-xl overflow-hidden border border-gray-100">
                                        @if($cart->productVariant->product->image)
                                            <img src="{{ asset('storage/' . $cart->productVariant->product->image) }}" alt="{{ $cart->productVariant->product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Details -->
                                    <div class="flex-1 flex flex-col justify-between">
                                        <div>
                                            <div class="flex justify-between items-start">
                                                <h3 class="text-lg font-bold text-gray-900">
                                                    {{ $cart->productVariant->product->name }}
                                                </h3>
                                                <p class="text-lg font-bold text-emerald-600 sm:hidden">
                                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                                </p>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">Rp {{ number_format($cart->productVariant->price, 0, ',', '.') }} / {{ $cart->productVariant->unit }}</p>
                                            @if($cart->productVariant->stock < 5)
                                                <p class="text-xs text-red-500 font-medium mt-1">Sisa stok: {{ $cart->productVariant->stock }}</p>
                                            @endif
                                        </div>
                                        
                                        <!-- Controls -->
                                        <div class="mt-4 flex items-center justify-between">
                                            <!-- Qty Input -->
                                            <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="flex items-center border border-gray-200 rounded-lg overflow-hidden bg-white">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" name="action" value="decrease" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 transition-colors" {{ $cart->quantity <= 1 ? 'disabled' : '' }}>
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                                </button>
                                                <input type="text" readonly value="{{ $cart->quantity }}" class="w-12 h-8 text-center text-sm font-bold border-0 bg-transparent focus:ring-0 p-0">
                                                <button type="submit" name="action" value="increase" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 transition-colors" {{ $cart->quantity >= $cart->productVariant->stock ? 'disabled' : '' }}>
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                </button>
                                            </form>
                                            
                                            <!-- Delete -->
                                            <form action="{{ route('cart.destroy', $cart->id) }}" method="POST" id="delete-cart-{{ $cart->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDeleteCart({{ $cart->id }})" class="text-gray-400 hover:text-red-500 p-2 rounded-lg hover:bg-red-50 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <!-- Subtotal (Desktop) -->
                                    <div class="hidden sm:block text-right min-w-[120px]">
                                        <p class="text-lg font-bold text-emerald-600">
                                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:w-96">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                        <h2 class="text-lg font-bold text-gray-900 mb-6">Ringkasan Belanja</h2>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Total Item</span>
                                <span class="font-medium text-gray-900">{{ $carts->sum('quantity') }} Barang</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Total Harga</span>
                                <span class="font-medium text-gray-900">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-100 pt-4 mb-6">
                            <div class="flex justify-between items-end">
                                <span class="text-base font-bold text-gray-900">Grand Total</span>
                                <span class="text-2xl font-extrabold text-emerald-600">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('checkout.index') }}" class="w-full flex items-center justify-center py-3.5 px-4 rounded-xl text-base font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-sm">
                            Lanjut Checkout
                        </a>
                        
                        <div class="mt-4 text-center">
                            <a href="{{ route('home') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium hover:underline">
                                Lanjut Belanja
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    function confirmDeleteCart(id) {
        Swal.fire({
            title: 'Hapus Produk?',
            text: "Produk akan dihapus dari keranjang belanja Anda.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-cart-' + id).submit();
            }
        })
    }
</script>
@endsection
