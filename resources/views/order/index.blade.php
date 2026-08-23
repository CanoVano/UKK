@extends('layouts.store')

@section('title', 'Pesanan Saya')

@section('content')
<div class="bg-gray-50 py-10 min-h-screen font-sans">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section with Decorative Image -->
        <div class="flex justify-between items-center mb-8 relative">
            <div>
                <h1 class="text-3xl font-black text-gray-900 mb-2">Pesanan Saya</h1>
                <p class="text-sm text-gray-500">Lacak status belanjaan sayur segar Anda di sini.</p>
            </div>
            <!-- Decorative Vegetables Emoji -->
            <div class="hidden sm:block absolute right-4 top-1/2 transform -translate-y-1/2 -mt-2 text-5xl">
                🥦🥕🍅
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="flex gap-3 mb-8 overflow-x-auto pb-2 scrollbar-hide">
            <a href="{{ route('order.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-bold flex-shrink-0 transition-transform transform hover:-translate-y-0.5 {{ empty($status) ? 'text-white bg-emerald-700 shadow-md' : 'text-gray-600 bg-white border border-gray-200 hover:bg-gray-50' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Semua Pesanan
            </a>
            <a href="{{ route('order.index', ['status' => 'ready_for_pickup']) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-bold flex-shrink-0 transition-transform transform hover:-translate-y-0.5 {{ $status == 'ready_for_pickup' ? 'text-white bg-emerald-700 shadow-md' : 'text-gray-600 bg-white border border-gray-200 hover:bg-gray-50' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Ready for pickup
            </a>
            <a href="{{ route('order.index', ['status' => 'pending']) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-bold flex-shrink-0 transition-transform transform hover:-translate-y-0.5 {{ $status == 'pending' ? 'text-white bg-emerald-700 shadow-md' : 'text-gray-600 bg-white border border-gray-200 hover:bg-gray-50' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Pending
            </a>
        </div>

        @if($orders->isEmpty())
            <div class="text-center py-20 bg-white rounded-3xl border border-gray-100 shadow-sm max-w-3xl mx-auto">
                <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                <h3 class="text-lg font-bold text-gray-900">Belum Ada Pesanan</h3>
                <p class="mt-1 text-sm text-gray-500">Anda belum pernah melakukan pemesanan.</p>
                <div class="mt-6">
                    <a href="{{ route('home') }}" class="inline-flex px-8 py-3 rounded-xl text-white bg-emerald-700 hover:bg-emerald-800 transition-colors font-bold shadow-lg">
                        Belanja Sekarang
                    </a>
                </div>
            </div>
        @else
            <!-- Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                @foreach($orders as $order)
                @php
                    $statusColor = 'gray';
                    if($order->status === 'pending') $statusColor = 'yellow';
                    elseif($order->status === 'paid') $statusColor = 'blue';
                    elseif($order->status === 'preparing') $statusColor = 'purple';
                    elseif($order->status === 'ready_for_pickup') $statusColor = 'emerald';
                    elseif($order->status === 'cancelled') $statusColor = 'red';
                    
                    $firstItem = $order->orderItems->first();
                    $otherProductsCount = $order->orderItems->count() - 1;
                @endphp
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow flex flex-col p-6">
                    <!-- Card Header -->
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-2 text-sm text-gray-500 font-medium">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $order->created_at->format('d M Y') }}
                        </div>
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold tracking-wide uppercase bg-{{ $statusColor }}-50 text-{{ $statusColor }}-600 border border-{{ $statusColor }}-200/60">
                            {{ str_replace('_', ' ', $order->status) }}
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="flex gap-4 items-center mb-5">
                        <!-- Image -->
                        <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-50 border border-gray-100 flex-shrink-0">
                            @if($firstItem && $firstItem->productVariant && $firstItem->productVariant->product->image)
                                <img src="{{ asset('storage/' . $firstItem->productVariant->product->image) }}" alt="{{ $firstItem->productVariant->product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <h3 class="font-extrabold text-gray-900 text-[15px] truncate">{{ $order->order_number }}</h3>
                            <div class="flex items-center text-sm text-gray-500 mt-0.5">
                                @if($firstItem && $firstItem->productVariant)
                                    <span class="truncate max-w-[120px]">{{ $firstItem->productVariant->product->name }} ({{ $firstItem->productVariant->unit }})</span>
                                    <span class="mx-1">×</span>
                                    <span>{{ $firstItem->quantity }}</span>
                                @else
                                    <span>Produk Dihapus</span>
                                @endif
                            </div>
                            @if($otherProductsCount > 0)
                                <p class="text-xs text-gray-400 mt-0.5">+ {{ $otherProductsCount }} produk lainnya</p>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-dashed border-gray-100 my-1"></div>

                    <!-- Card Footer -->
                    <div class="flex items-center justify-between mt-4">
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Total Belanja</p>
                            <p class="font-black text-lg text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        </div>
                        <a href="{{ route('order.show', $order->order_number) }}" class="inline-flex justify-center items-center px-5 py-2.5 rounded-lg text-sm font-bold text-white bg-emerald-800 hover:bg-emerald-900 transition-colors shadow-sm">
                            Lihat Detail &rarr;
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination (if applicable) -->
            <div class="mb-12">
                {{ $orders->links() }}
            </div>
            
            <!-- Bottom Call to Action Banner -->
            <div class="bg-emerald-50 rounded-3xl p-8 flex flex-col md:flex-row items-center justify-between border border-emerald-100 shadow-sm relative overflow-hidden mb-12">
                <!-- Abstract Leaves -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-100/50 rounded-full blur-2xl -mt-10 -mr-10"></div>
                <div class="absolute bottom-0 left-10 w-24 h-24 bg-white/50 rounded-full blur-xl -mb-10"></div>
                
                <div class="flex flex-col md:flex-row items-center gap-6 relative z-10 w-full">
                    <!-- Veggie basket -->
                    <div class="w-24 h-24 hidden md:flex items-center justify-center flex-shrink-0 text-6xl bg-white rounded-full shadow-sm transform -rotate-12">
                        🧺
                    </div>
                    
                    <div class="text-center md:text-left flex-1">
                        <h3 class="text-xl font-black text-emerald-900 mb-1">Sayur Segar, Hidup Lebih Sehat 🥬</h3>
                        <p class="text-sm text-emerald-700">Belanja mudah, ambil sendiri di warung terdekat.</p>
                    </div>
                    
                    <div class="mt-4 md:mt-0 flex-shrink-0">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold text-white bg-emerald-700 hover:bg-emerald-800 transition-colors shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Belanja Lagi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 px-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-[13px] font-bold text-gray-900">Segar Setiap Hari</h4>
                        <p class="text-[11px] text-gray-500 mt-0.5">Dipilih langsung dari pasar pagi</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-[13px] font-bold text-gray-900">Tanpa Pengawet</h4>
                        <p class="text-[11px] text-gray-500 mt-0.5">100% alami & sehat</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-[13px] font-bold text-gray-900">Dukung Pedagang Lokal</h4>
                        <p class="text-[11px] text-gray-500 mt-0.5">Bersama membangun ekonomi lokal</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.242-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-[13px] font-bold text-gray-900">Ambil Sendiri (Self-Pickup)</h4>
                        <p class="text-[11px] text-gray-500 mt-0.5">Hemat ongkir, lebih cepat</p>
                    </div>
                </div>
            </div>
            
        @endif

    </div>
</div>
@endsection
