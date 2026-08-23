@extends('layouts.store')

@section('content')
<div class="bg-gray-50/50 py-12 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Checkout</h1>
            <p class="text-gray-500 mt-2">Selesaikan pesanan Anda (Ambil di Toko)</p>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST" class="flex flex-col lg:flex-row gap-8">
            @csrf
            
            <!-- Kiri: Form Data Pembeli -->
            <div class="lg:w-2/3 flex flex-col gap-6">
                <!-- Kontak -->
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm">1</span>
                        Informasi Kontak
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="whatsapp_number" class="block text-sm font-semibold text-gray-700 mb-1">Nomor WhatsApp <span class="text-red-500">*</span></label>
                            <input type="text" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number', auth()->user()->phone ?? '') }}" required placeholder="Contoh: 08123456789" class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-colors bg-gray-50 py-3">
                            @error('whatsapp_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-2">Kami akan menghubungi nomor ini jika ada kendala pesanan atau saat pesanan siap diambil.</p>
                        </div>
                    </div>
                </div>

                <!-- Catatan & Pengambilan -->
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm">2</span>
                        Metode Pengambilan & Catatan
                    </h2>
                    
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-4">
                        <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600 mt-0.5">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-emerald-900 text-base">Ambil di Toko (Self-Pickup)</h3>
                            <p class="text-sm text-emerald-700 mt-1">Sistem ini hanya melayani pengambilan langsung di Warung Mamah. Tidak melayani pesan antar / kurir.</p>
                        </div>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-semibold text-gray-700 mb-1">Catatan Tambahan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <textarea name="notes" id="notes" rows="3" placeholder="Contoh: Tolong pisahkan sayurannya, atau akan diambil jam 10 pagi." class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-colors bg-gray-50">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Kanan: Ringkasan -->
            <div class="lg:w-1/3">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <h2 class="text-lg font-bold text-gray-900 mb-6">Ringkasan Belanja</h2>
                    
                    <ul class="space-y-4 mb-6">
                        @foreach($carts as $cart)
                            <li class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-white border border-gray-100 rounded-xl overflow-hidden flex-shrink-0 flex items-center justify-center">
                                    @if($cart->productVariant->product->image)
                                        <img src="{{ Storage::url($cart->productVariant->product->image) }}" alt="{{ $cart->productVariant->product->name }}" class="w-full h-full object-contain p-2">
                                    @else
                                        <span class="text-2xl text-gray-300">🛒</span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-bold text-gray-900 line-clamp-1">{{ $cart->productVariant->product->name }}</h4>
                                    <p class="text-xs text-gray-500">{{ $cart->quantity }} x {{ $cart->productVariant->unit }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-emerald-600">Rp {{ number_format($cart->productVariant->price * $cart->quantity, 0, ',', '.') }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    
                    <div class="border-t border-gray-100 pt-4 mb-6 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Total Harga ({{ $carts->sum('quantity') }} barang)</span>
                            <span class="font-medium text-gray-900">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-100 pt-4 mb-6">
                        <div class="flex justify-between items-end">
                            <span class="text-base font-bold text-gray-900">Total Tagihan</span>
                            <span class="text-2xl font-extrabold text-emerald-600">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-3.5 px-4 rounded-xl text-base font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-sm">
                        <span>Lanjut Pembayaran</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                    
                    <p class="text-xs text-gray-400 text-center mt-4">
                        Dengan menekan tombol ini, Anda menyetujui pesanan Anda dan akan diarahkan ke halaman pembayaran aman.
                    </p>
                </div>
            </div>
            
        </form>
    </div>
</div>
@endsection
