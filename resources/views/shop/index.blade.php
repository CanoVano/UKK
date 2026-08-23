@extends('layouts.store')

@section('title', 'Beranda')

@section('content')
<!-- Hero Section -->
<section class="relative bg-emerald-900 text-white overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-emerald-800 to-teal-900 mix-blend-multiply"></div>
    <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1600&q=80" alt="Fresh Produce" class="absolute inset-0 w-full h-full object-cover opacity-40">
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight mb-4">
            Beli Sayur Segar <br class="hidden sm:block"> Segar Setiap Harinya.
        </h1>
        <p class="mt-4 max-w-2xl text-xl text-emerald-100 mb-8">
            Penuhi kebutuhan dapur harian Anda dengan sayuran, buah, dan bumbu berkualitas tinggi. Bebas repot, diantar langsung ke rumah atau ambil di toko.
        </p>
        <a href="#katalog" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold rounded-full text-emerald-900 bg-emerald-400 hover:bg-emerald-300 transition-colors shadow-lg hover:shadow-xl transform hover:-translate-y-1">
            Belanja Sekarang
        </a>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" id="katalog">
    
    <!-- Category Chips (Horizontal Scrollable) -->
    <div class="mb-10">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Kategori Pilihan</h2>
        <div class="flex overflow-x-auto gap-3 pb-4 hide-scrollbar -mx-4 px-4 sm:mx-0 sm:px-0">
            <a href="{{ route('home') }}" class="whitespace-nowrap px-6 py-2.5 rounded-full text-sm font-medium transition-colors border {{ !request('category') ? 'bg-emerald-600 border-emerald-600 text-white shadow-md' : 'bg-white border-gray-200 text-gray-700 hover:border-emerald-600 hover:text-emerald-600' }}">
                Semua Kategori
            </a>
            @foreach($categories as $category)
                <a href="{{ route('home', ['category' => $category->id]) }}" class="whitespace-nowrap px-6 py-2.5 rounded-full text-sm font-medium transition-colors border {{ request('category') == $category->id ? 'bg-emerald-600 border-emerald-600 text-white shadow-md' : 'bg-white border-gray-200 text-gray-700 hover:border-emerald-600 hover:text-emerald-600' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Product Grid -->
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">
            @if(request('category'))
                Katalog: {{ $categories->find(request('category'))->name }}
            @elseif(request('search'))
                Hasil Pencarian: "{{ request('search') }}"
            @else
                Katalog Produk
            @endif
        </h2>
        <span class="text-sm text-gray-500">{{ $products->total() }} produk</span>
    </div>

    @if($products->isEmpty())
        <div class="text-center py-20 bg-white rounded-2xl border border-gray-100 border-dashed">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            <h3 class="text-lg font-medium text-gray-900">Tidak Ada Produk</h3>
            <p class="mt-1 text-gray-500">Kami tidak dapat menemukan produk yang Anda cari.</p>
            @if(request('category') || request('search'))
                <div class="mt-6">
                    <a href="{{ route('home') }}" class="text-emerald-600 font-medium hover:text-emerald-500">
                        &larr; Kembali ke Semua Produk
                    </a>
                </div>
            @endif
        </div>
    @else
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6">
            @foreach($products as $product)
                <div class="group bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col h-full overflow-hidden transform hover:-translate-y-1">
                    
                    <!-- Image Container -->
                    <a href="{{ route('shop.show', $product->slug) }}" class="relative w-full overflow-hidden block bg-white border-b border-gray-100 aspect-square p-4 flex items-center justify-center">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="object-contain w-full h-full transition-transform duration-300 group-hover:scale-105">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        
                        <!-- Category Badge -->
                        <div class="absolute top-2 left-2">
                            <span class="px-2.5 py-1 text-xs font-bold bg-white/90 backdrop-blur-sm text-emerald-700 rounded-lg shadow-sm">
                                {{ $product->category->name }}
                            </span>
                        </div>
                        
                        <!-- Out of stock overlay -->
                        @php $totalStock = $product->variants->sum('stock'); @endphp
                        @if($totalStock <= 0)
                            <div class="absolute inset-0 bg-white/60 backdrop-blur-[2px] flex items-center justify-center">
                                <span class="px-3 py-1.5 bg-red-600 text-white text-sm font-bold rounded-lg shadow-sm">Stok Habis</span>
                            </div>
                        @endif
                    </a>
                    
                    <!-- Content -->
                    <div class="bg-white p-4 flex-1 flex flex-col">
                        <div class="flex-grow">
                            <a href="{{ route('shop.show', $product->slug) }}" class="text-gray-900 font-semibold line-clamp-2 leading-tight group-hover:text-emerald-700 transition-colors">
                                {{ $product->name }}
                            </a>
                            <p class="text-sm text-gray-500 mt-1">{{ $product->variants->count() }} Pilihan Varian</p>
                        </div>
                        
                        <div class="mt-4 flex items-end justify-between">
                            <div>
                                <p class="text-xs text-gray-500 mb-0.5">Mulai dari</p>
                                <span class="text-lg font-extrabold text-emerald-600">Rp {{ number_format($product->variants->min('price') ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Add to Cart Button (Now links to show to pick variant) -->
                        <div class="mt-4">
                            <a href="{{ route('shop.show', $product->slug) }}" class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-sm font-bold transition-all {{ $totalStock > 0 ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white border border-emerald-100 hover:border-emerald-600' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Pilih Varian
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-10">
            {{ $products->withQueryString()->links() }}
        </div>
    @endif
</div>

<!-- Features Section -->
<section class="bg-white py-16 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="p-6 rounded-2xl bg-emerald-50 border border-emerald-100 transition-transform transform hover:-translate-y-1">
                <div class="w-16 h-16 mx-auto bg-emerald-600 rounded-full flex items-center justify-center text-white mb-4 shadow-md">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Selalu Segar</h3>
                <p class="text-gray-600">Dipilih langsung dari pasar pagi setiap harinya untuk menjamin kualitas terbaik.</p>
            </div>
            <div class="p-6 rounded-2xl bg-teal-50 border border-teal-100 transition-transform transform hover:-translate-y-1">
                <div class="w-16 h-16 mx-auto bg-teal-600 rounded-full flex items-center justify-center text-white mb-4 shadow-md">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Harga Terbaik</h3>
                <p class="text-gray-600">Menawarkan harga kompetitif yang ramah di kantong karena memotong rantai distribusi panjang.</p>
            </div>
            <div class="p-6 rounded-2xl bg-blue-50 border border-blue-100 transition-transform transform hover:-translate-y-1">
                <div class="w-16 h-16 mx-auto bg-blue-600 rounded-full flex items-center justify-center text-white mb-4 shadow-md">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Praktis & Mudah</h3>
                <p class="text-gray-600">Pesan dari rumah, bayar dengan sistem aman, dan tinggal ambil di toko tanpa antre.</p>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="bg-gradient-to-b from-white to-emerald-50 py-20 relative overflow-hidden border-t border-gray-100">
    <div class="absolute top-0 left-0 w-full h-full opacity-10 bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:20px_20px]"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 mb-4">Dapatkan Info Diskon & Produk Baru!</h2>
        <p class="text-gray-600 mb-10 text-lg max-w-2xl mx-auto">Jadilah yang pertama tahu ketika ada sayuran musim baru, panen raya, atau promo spesial akhir pekan dari Warung Mamah.</p>
        <form class="flex flex-col sm:flex-row gap-3 justify-center max-w-xl mx-auto relative z-10">
            <input type="email" placeholder="Masukkan alamat email Anda..." class="flex-1 px-6 py-4 rounded-full text-gray-900 bg-white border border-gray-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 shadow-lg placeholder-gray-400 transition-shadow">
            <button type="button" class="px-8 py-4 rounded-full bg-emerald-600 text-white font-bold hover:bg-emerald-700 transition-colors shadow-lg transform hover:-translate-y-1">
                Berlangganan
            </button>
        </form>
    </div>
</section>

@endsection
