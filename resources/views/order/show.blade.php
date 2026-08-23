@extends('layouts.store')

@section('title', 'Detail Pesanan ' . $order->order_number)

@section('content')
<div class="bg-gray-50 py-10 min-h-screen font-sans">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs -->
        <nav class="flex text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="hover:text-emerald-700 transition-colors">Beranda</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        <a href="{{ route('order.index') }}" class="hover:text-emerald-700 transition-colors">Pesanan Saya</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        <span class="text-gray-900 font-medium">Detail</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Kolom Kiri: Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Hero Banner -->
                <div class="bg-emerald-800 rounded-3xl p-8 text-white shadow-md relative overflow-hidden flex flex-col justify-center min-h-[160px]">
                    <!-- Decorative Vegetables (Right) -->
                    <div class="absolute right-6 bottom-2 transform opacity-90 hidden sm:block text-7xl">
                         🛍️🥬
                    </div>
                    
                    <div class="relative z-10 flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full border-2 border-emerald-400 flex items-center justify-center flex-shrink-0 mt-1">
                            @if($order->status === 'pending')
                                <svg class="w-6 h-6 text-emerald-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @else
                                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-emerald-200 text-sm mb-1">Terima kasih sudah berbelanja!</p>
                            <h2 class="text-3xl font-black mb-4">
                                @if($order->status === 'pending') Menunggu Pembayaran
                                @elseif($order->status === 'paid') Pembayaran Berhasil
                                @elseif($order->status === 'preparing') Sedang Disiapkan
                                @elseif($order->status === 'ready_for_pickup') Pesanan Siap Diambil
                                @elseif($order->status === 'completed') Pesanan Selesai
                                @else Pesanan Dibatalkan @endif
                            </h2>
                            <div class="flex items-center gap-3 text-sm text-emerald-100/90 font-medium">
                                <span class="bg-emerald-900/50 px-3 py-1.5 rounded-md">{{ $order->order_number }}</span>
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400/50"></span>
                                <span>{{ $order->created_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress Stepper -->
                @if($order->status !== 'cancelled')
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden p-6 md:p-8">
                    @php
                        $steps = [
                            ['key' => 'pending', 'label' => 'Menunggu Pembayaran', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                            ['key' => 'preparing', 'label' => 'Pesanan Disiapkan', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                            ['key' => 'ready_for_pickup', 'label' => 'Siap Diambil', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
                            ['key' => 'completed', 'label' => 'Selesai', 'icon' => 'M5 13l4 4L19 7'],
                        ];
                        
                        $currentIndex = 0;
                        if(in_array($order->status, ['paid', 'preparing'])) $currentIndex = 1;
                        if($order->status === 'ready_for_pickup') $currentIndex = 2;
                        if($order->status === 'completed') $currentIndex = 3;
                    @endphp
                    
                    <div class="relative">
                        <!-- Connecting Line -->
                        <div class="hidden md:block absolute top-1/2 left-0 w-full h-1 bg-gray-100 -translate-y-1/2 rounded-full z-0"></div>
                        <div class="hidden md:block absolute top-1/2 left-0 h-1 bg-emerald-500 -translate-y-1/2 rounded-full z-0 transition-all duration-500" style="width: {{ ($currentIndex / (count($steps) - 1)) * 100 }}%"></div>
                        
                        <div class="flex flex-col md:flex-row justify-between relative z-10 gap-6 md:gap-0">
                            @foreach($steps as $index => $step)
                                @php
                                    $isCompleted = $index < $currentIndex;
                                    $isActive = $index === $currentIndex;
                                    $isPending = $index > $currentIndex;
                                @endphp
                                <div class="flex flex-row md:flex-col items-center md:justify-center gap-4 md:gap-3 text-left md:text-center w-full md:w-1/4">
                                    <!-- Icon Circle -->
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0 transition-all duration-300 {{ $isCompleted ? 'bg-emerald-500 text-white shadow-md' : ($isActive ? 'bg-white border-2 border-emerald-500 text-emerald-600 shadow-md ring-4 ring-emerald-50' : 'bg-gray-100 text-gray-400') }}">
                                        @if($isCompleted)
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        @else
                                            <svg class="w-5 h-5 {{ $isActive && $step['key'] === 'pending' ? 'animate-spin-slow' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"></path></svg>
                                        @endif
                                    </div>
                                    <!-- Label -->
                                    <div>
                                        <p class="text-sm font-bold {{ $isActive ? 'text-emerald-700' : ($isCompleted ? 'text-gray-900' : 'text-gray-400') }}">
                                            {{ $step['label'] }}
                                        </p>
                                        @if($isActive)
                                            <span class="text-xs font-semibold text-emerald-600/80 mt-0.5 block">Sedang Berlangsung</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Product List Card -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden p-6">
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-dashed border-gray-200">
                        <h2 class="text-lg font-extrabold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            Daftar Produk
                        </h2>
                        <span class="text-sm font-bold text-gray-500">{{ $order->orderItems->sum('quantity') }} Barang</span>
                    </div>
                    
                    <ul role="list" class="divide-y divide-gray-50">
                        @foreach($order->orderItems as $item)
                            <li class="py-4 flex items-center gap-5">
                                <!-- Image -->
                                <div class="w-16 h-16 flex-shrink-0 bg-gray-50 rounded-xl overflow-hidden border border-gray-100">
                                    @if($item->productVariant && $item->productVariant->product->image)
                                        <img src="{{ asset('storage/' . $item->productVariant->product->image) }}" alt="{{ $item->productVariant->product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Details -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-base font-bold text-gray-900 mb-1">
                                        {{ $item->productVariant ? $item->productVariant->product->name . ' - ' . $item->productVariant->unit : 'Produk Dihapus' }}
                                    </h3>
                                    <div class="flex items-center gap-3 text-sm">
                                        <span class="font-bold text-emerald-700">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                        <span class="text-gray-300">×</span>
                                        <span class="font-bold text-gray-500">{{ $item->quantity }}</span>
                                    </div>
                                </div>
                                
                                <!-- Subtotal -->
                                <div class="text-right">
                                    <p class="text-base font-black text-gray-900">
                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Order Notes -->
                @if($order->notes || true)
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-base font-extrabold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Catatan Pesanan
                    </h4>
                    <div class="bg-yellow-50/50 rounded-2xl p-4 text-sm text-gray-600 border border-yellow-100/50">
                        {{ $order->notes ?? 'Tidak ada catatan khusus dari pembeli.' }}
                    </div>
                </div>
                @endif
                
                <!-- Pickup Instruction Banner -->
                <div class="bg-emerald-50 rounded-3xl p-6 sm:p-8 flex flex-col sm:flex-row items-center gap-6 border border-emerald-100/50 shadow-sm relative overflow-hidden">
                    <div class="flex-1 relative z-10">
                        <h4 class="text-lg font-black text-gray-900 mb-2">Ambil Pesanan di Warung</h4>
                        <p class="text-sm text-gray-600 mb-4">Tunjukkan kode invoice Anda ke petugas saat pengambilan.</p>
                        
                        <ul class="space-y-2 text-sm text-emerald-800 font-medium">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Datang sesuai jam operasional
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Bawa kode invoice
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Pesanan disiapkan dengan segar
                            </li>
                        </ul>
                    </div>
                    <!-- Illustration Placeholder -->
                    <div class="w-28 h-28 flex-shrink-0 sm:flex hidden relative z-10 items-center justify-center bg-white rounded-full text-7xl border-4 border-emerald-100 shadow-sm">
                        🏪
                    </div>
                </div>
                
                <!-- Help Support Section -->
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 relative overflow-hidden">
                    <div>
                        <h4 class="text-base font-extrabold text-gray-900 mb-1">Butuh Bantuan?</h4>
                        <p class="text-sm text-gray-500">Hubungi kami jika ada pertanyaan mengenai pesanan Anda.</p>
                    </div>
                    <button class="px-6 py-2.5 bg-white border border-gray-200 rounded-xl shadow-sm text-sm font-bold text-gray-700 hover:bg-gray-50 flex items-center gap-2 relative z-10">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        Hubungi Kami
                    </button>
                    <!-- Decorative right corner -->
                    <div class="absolute -right-4 -bottom-4 opacity-40 sm:opacity-100 pointer-events-none text-8xl">
                        🧑‍🌾
                    </div>
                </div>
                
            </div>

            <!-- Kolom Kanan: Sticky Invoice Receipt -->
            <div class="lg:col-span-1">
                <div class="sticky top-6">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative">
                        <!-- Awning Top Border (CSS Scallops or Stripes) -->
                        <div class="w-full h-4 bg-emerald-200 flex">
                            @for($i=0; $i<12; $i++)
                            <div class="flex-1 bg-emerald-600 rounded-b-full h-3 mx-0.5"></div>
                            @endfor
                        </div>

                        <div class="p-8">
                            <div class="text-center mb-6">
                                <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-2">Invoice</p>
                                <h2 class="text-xl sm:text-2xl font-black text-gray-900 break-words mb-2 leading-none">{{ $order->order_number }}</h2>
                                <p class="text-xs text-gray-500 font-medium">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>

                            <!-- Status Badge -->
                            <div class="flex justify-center mb-8">
                                @php
                                    $statusColor = 'gray';
                                    if($order->status === 'pending') $statusColor = 'yellow';
                                    elseif($order->status === 'paid') $statusColor = 'blue';
                                    elseif($order->status === 'preparing') $statusColor = 'purple';
                                    elseif($order->status === 'ready_for_pickup') $statusColor = 'emerald';
                                    elseif($order->status === 'cancelled') $statusColor = 'red';
                                @endphp
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide bg-{{ $statusColor }}-50 text-{{ $statusColor }}-600 border border-{{ $statusColor }}-200/60">
                                    @if($order->status === 'pending')
                                        <span class="w-2 h-2 rounded-full bg-yellow-500 mr-2 animate-pulse"></span>
                                    @elseif($order->status === 'paid' || $order->status === 'ready_for_pickup' || $order->status === 'completed')
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @endif
                                    {{ str_replace('_', ' ', $order->status) }}
                                </span>
                            </div>

                            <div class="border-t border-dashed border-gray-200 my-6"></div>

                            <!-- Info Grid -->
                            <div class="space-y-4 mb-6">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1.5">Metode Pengiriman</p>
                                    <div class="flex items-center gap-2 text-sm font-extrabold text-gray-900">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        Ambil di Toko (Self-Pickup)
                                    </div>
                                </div>
                            </div>

                            <!-- Totals -->
                            <div class="space-y-3 mb-8">
                                <p class="text-xs text-gray-500 mb-2">Rincian Pembayaran</p>
                                
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Subtotal</span>
                                    <span class="font-bold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Biaya Layanan</span>
                                    <span class="font-bold text-emerald-600">Gratis</span>
                                </div>
                                
                                <div class="pt-5 mt-5">
                                    <p class="text-xs text-gray-500 mb-1">Total Keseluruhan</p>
                                    <div class="text-right">
                                        <span class="text-3xl font-black text-emerald-700 tracking-tight">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Thank You Box -->
                            <div class="bg-emerald-50/50 rounded-2xl p-4 text-center border border-emerald-100 relative">
                                <div class="absolute -top-3 -right-2">
                                     <svg class="w-6 h-6 text-emerald-400 opacity-60 transform rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                </div>
                                <p class="text-sm font-bold text-emerald-900 mb-1">Terima kasih telah berbelanja di Warung Mamah!</p>
                                <p class="text-xs text-emerald-700/80 leading-relaxed">Kami selalu siap menyediakan sayuran segar terbaik untuk Anda.</p>
                            </div>

                            <!-- Payment Actions if Pending -->
                            @if($order->status === 'pending' && $order->payment_url)
                                <div class="mt-6 space-y-3">
                                    <a href="{{ $order->payment_url }}" class="w-full flex items-center justify-center py-3.5 px-4 rounded-xl text-sm font-black text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-500/30 transform hover:-translate-y-0.5">
                                        Bayar Sekarang
                                    </a>
                                    
                                    <!-- Manual Sync for Local Testing -->
                                    <form action="{{ route('order.sync', $order->order_number) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center justify-center py-3 px-4 rounded-xl text-[13px] font-bold text-gray-700 bg-white hover:bg-gray-50 transition-colors border-2 border-gray-100">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                            Sudah Bayar? Cek Status
                                        </button>
                                    </form>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
