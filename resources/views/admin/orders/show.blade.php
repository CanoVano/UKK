@extends('layouts.admin')

@section('title', 'Detail Pesanan ' . $order->order_number)

@section('header')
<div class="flex items-center gap-4">
    <a href="{{ route('admin.orders.index') }}" class="p-2 rounded-lg hover:bg-white transition-colors text-gray-500">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Pesanan {{ $order->order_number }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $order->created_at->format('d F Y, H:i') }}</p>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Kolom Kiri: Detail Belanja & Kustomer -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Quick Actions Banner (The requested intuitive UI for Mom) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Ubah Status Pesanan (Aksi Cepat)</h2>
            
            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="flex flex-wrap gap-3">
                @csrf
                @method('PUT')
                
                @if($order->status === 'pending')
                    <p class="text-sm text-yellow-600 font-medium mb-2 w-full">Pesanan ini belum dibayar oleh kustomer. Tunggu pembayaran.</p>
                    <button type="submit" name="status" value="cancelled" class="px-6 py-3 rounded-xl font-bold text-red-700 bg-red-50 border border-red-200 hover:bg-red-600 hover:text-white transition-colors">
                        Batalkan Pesanan
                    </button>
                    <!-- Fallback if admin wants to force pay (e.g. manual transfer) -->
                    <button type="submit" name="status" value="paid" class="px-6 py-3 rounded-xl font-bold text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors">
                        Tandai Sudah Dibayar Manual
                    </button>
                @endif
                
                @if($order->status === 'paid')
                    <div class="w-full bg-blue-50 border border-blue-100 p-4 rounded-xl mb-2 flex items-start gap-3">
                        <svg class="w-6 h-6 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <h3 class="font-bold text-blue-900">Pesanan Baru Masuk!</h3>
                            <p class="text-sm text-blue-700">Kustomer sudah membayar. Silakan kumpulkan barang ke dalam kantong belanja.</p>
                        </div>
                    </div>
                    <button type="submit" name="status" value="preparing" class="w-full sm:w-auto px-8 py-4 rounded-xl font-extrabold text-white bg-purple-600 hover:bg-purple-700 transition-colors shadow-lg transform hover:-translate-y-1 text-lg flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Mulai Siapkan Pesanan
                    </button>
                @endif
                
                @if($order->status === 'preparing')
                    <div class="w-full bg-purple-50 border border-purple-100 p-4 rounded-xl mb-2 flex items-start gap-3">
                        <svg class="w-6 h-6 text-purple-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <h3 class="font-bold text-purple-900">Sedang Dikemas</h3>
                            <p class="text-sm text-purple-700">Jika semua barang sudah masuk kantong, klik tombol di bawah agar kustomer bisa mengambilnya.</p>
                        </div>
                    </div>
                    <button type="submit" name="status" value="ready_for_pickup" class="w-full sm:w-auto px-8 py-4 rounded-xl font-extrabold text-white bg-emerald-500 hover:bg-emerald-600 transition-colors shadow-lg transform hover:-translate-y-1 text-lg flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Tandai Barang Siap Diambil
                    </button>
                @endif
                
                @if($order->status === 'ready_for_pickup')
                    <div class="w-full bg-emerald-50 border border-emerald-100 p-4 rounded-xl mb-2 flex items-start gap-3">
                        <svg class="w-6 h-6 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <h3 class="font-bold text-emerald-900">Menunggu Diambil</h3>
                            <p class="text-sm text-emerald-700">Berikan kantong belanjaan ke kustomer saat mereka datang, lalu klik tombol Selesai.</p>
                        </div>
                    </div>
                    <button type="submit" name="status" value="completed" class="w-full sm:w-auto px-8 py-4 rounded-xl font-extrabold text-white bg-gray-800 hover:bg-gray-900 transition-colors shadow-lg transform hover:-translate-y-1 text-lg flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Pesanan Selesai Diambil
                    </button>
                @endif

                @if($order->status === 'completed' || $order->status === 'cancelled')
                    <p class="text-sm font-medium text-gray-500">Pesanan ini telah berada di status akhir (Selesai/Dibatalkan).</p>
                @endif
            </form>
        </div>

        <!-- Daftar Barang -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-800">Daftar Barang (Ceklis Persiapan)</h2>
                <span class="text-sm font-medium text-gray-500">{{ $order->orderItems->count() }} Macam Barang</span>
            </div>
            <ul role="list" class="divide-y divide-gray-100">
                @foreach($order->orderItems as $item)
                    <li class="p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center gap-4 hover:bg-gray-50 transition-colors">
                        
                        <!-- Checkbox Dummy for Mom to track mentally -->
                        <div class="hidden sm:flex items-center justify-center w-8 h-8 rounded border-2 border-gray-200 text-transparent hover:border-emerald-500 hover:text-emerald-500 cursor-pointer transition-colors" onclick="this.classList.toggle('bg-emerald-50')">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        
                        <!-- Image -->
                        <div class="w-16 h-16 sm:w-20 sm:h-20 flex-shrink-0 bg-white rounded-xl overflow-hidden border border-gray-100">
                            @if($item->productVariant && $item->productVariant->product->image)
                                <img src="{{ asset('storage/' . $item->productVariant->product->image) }}" alt="{{ $item->productVariant->product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Details -->
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900">
                                {{ $item->productVariant ? $item->productVariant->product->name . ' - ' . $item->productVariant->unit : 'Produk Telah Dihapus' }}
                            </h3>
                            <p class="text-base text-gray-600 mt-1">Harga Satuan: Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                        
                        <!-- Qty -->
                        <div class="bg-emerald-50 border border-emerald-100 rounded-xl px-6 py-3 text-center min-w-[100px]">
                            <span class="block text-xs font-bold text-emerald-600 uppercase mb-1">Jumlah</span>
                            <span class="text-2xl font-extrabold text-emerald-700">{{ $item->quantity }}</span>
                        </div>
                    </li>
                @endforeach
            </ul>
            <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                <span class="text-gray-500 font-bold">Total Pembayaran</span>
                <span class="text-2xl font-extrabold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>
        
    </div>
    
    <!-- Kolom Kanan: Info Kustomer -->
    <div class="space-y-6">
        
        <!-- Status Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Status Pesanan Saat Ini</h3>
            @if($order->status === 'pending')
                <div class="w-full py-3 rounded-xl bg-yellow-100 text-yellow-800 font-bold text-lg">Menunggu Pembayaran</div>
            @elseif($order->status === 'paid')
                <div class="w-full py-3 rounded-xl bg-blue-100 text-blue-800 font-bold text-lg">Sudah Dibayar</div>
            @elseif($order->status === 'preparing')
                <div class="w-full py-3 rounded-xl bg-purple-100 text-purple-800 font-bold text-lg">Sedang Disiapkan</div>
            @elseif($order->status === 'ready_for_pickup')
                <div class="w-full py-3 rounded-xl bg-emerald-100 text-emerald-800 font-bold text-lg">Siap Diambil</div>
            @elseif($order->status === 'completed')
                <div class="w-full py-3 rounded-xl bg-gray-100 text-gray-800 font-bold text-lg">Selesai</div>
            @else
                <div class="w-full py-3 rounded-xl bg-red-100 text-red-800 font-bold text-lg">Dibatalkan</div>
            @endif
        </div>
        
        <!-- Customer Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-bold text-gray-900 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Informasi Kustomer
            </h3>
            
            <div class="space-y-4">
                <div>
                    <span class="block text-sm text-gray-500 mb-1">Nama</span>
                    <span class="block font-medium text-gray-900">{{ $order->user->name }}</span>
                </div>
                <div>
                    <span class="block text-sm text-gray-500 mb-1">Email</span>
                    <span class="block font-medium text-gray-900">{{ $order->user->email }}</span>
                </div>
                <hr class="border-gray-100">
                <div>
                    <span class="block text-sm text-gray-500 mb-1">Metode Pengiriman</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 text-sm font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Diambil Sendiri
                    </span>
                </div>
                <div>
                    <span class="block text-sm text-gray-500 mb-1">Catatan Pesanan</span>
                    <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg">{{ $order->notes ?? 'Tidak ada catatan.' }}</p>
                </div>
            </div>
        </div>
        
    </div>
</div>

<script>
    // Simple script to toggle visual check state for mental tracking
    document.querySelectorAll('.cursor-pointer').forEach(el => {
        el.addEventListener('click', function() {
            this.classList.toggle('text-transparent');
            this.classList.toggle('text-emerald-500');
            this.classList.toggle('border-gray-200');
            this.classList.toggle('border-emerald-500');
        });
    });
</script>
@endsection
