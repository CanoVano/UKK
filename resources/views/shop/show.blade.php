@extends('layouts.store')

@section('title', $product->name)

@section('content')
@php 
    $totalStock = $product->variants->sum('stock'); 
    $variantsJson = $product->variants->map(function($v) {
        return [
            'id' => $v->id,
            'unit' => $v->unit,
            'price' => (int) $v->price,
            'formatted_price' => number_format($v->price, 0, ',', '.'),
            'stock' => $v->stock
        ];
    })->toJson();
@endphp

<div class="py-12 min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <nav class="flex mb-8 text-sm text-gray-500 font-medium" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="hover:text-emerald-600 transition-colors">Beranda</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <a href="{{ route('home', ['category' => $product->category_id]) }}" class="ml-2 hover:text-emerald-600 transition-colors">{{ $product->category->name }}</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-2 text-gray-800">{{ $product->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-white rounded-3xl border border-gray-200 shadow-xl overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
                
                <!-- Kiri: Gambar Produk -->
                <div class="p-8 md:p-12 flex items-center justify-center bg-gray-50/50 relative group">
                    @if($product->image)
                        <div class="w-full max-w-md aspect-square bg-gray-100 rounded-2xl border border-gray-200 flex items-center justify-center overflow-hidden">
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="mix-blend-multiply object-contain w-full h-full p-4 transition-transform duration-300 hover:scale-105">
                        </div>
                    @else
                        <div class="w-full max-w-md aspect-square bg-gray-200 rounded-2xl flex items-center justify-center shadow-md border border-gray-200">
                            <span class="text-6xl text-gray-400">🛒</span>
                        </div>
                    @endif
                    
                    @if($totalStock == 0)
                        <div class="absolute top-12 left-12 bg-red-500 text-white px-4 py-2 rounded-full font-bold shadow-lg transform -rotate-12">
                            Habis Terjual
                        </div>
                    @endif
                </div>

                <!-- Kanan: Detail & Aksi -->
                <div class="p-8 md:p-12 flex flex-col justify-center">
                    
                    <div class="mb-6">
                        <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full mb-4">
                            {{ $product->category->name }}
                        </span>
                        <h1 class="text-4xl sm:text-5xl font-black text-gray-900 mb-2 leading-tight">{{ $product->name }}</h1>
                        
                        <div class="flex items-baseline gap-2 mt-4">
                            <span class="text-4xl font-black text-emerald-600" id="display-price">Rp {{ number_format($product->variants->first()->price ?? 0, 0, ',', '.') }}</span>
                            <span class="text-lg text-gray-500 font-medium" id="display-unit">/ {{ $product->variants->first()->unit ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="prose prose-emerald mb-8 text-gray-600 text-lg leading-relaxed">
                        @if($product->description)
                            <p>{{ $product->description }}</p>
                        @else
                            <p>Sayuran segar berkualitas terbaik pilihan pasar pagi. Disortir dan dikemas dengan standar kebersihan tinggi untuk keluarga Anda.</p>
                        @endif
                    </div>

                    <!-- Varian Pilihan -->
                    <div class="mb-6">
                        <p class="text-sm font-bold text-gray-800 mb-3">Pilih Varian Satuan:</p>
                        <div class="flex flex-wrap gap-3" id="variant-pills">
                            @foreach($product->variants as $index => $variant)
                                <label class="cursor-pointer relative {{ $variant->stock == 0 ? 'opacity-50 cursor-not-allowed' : '' }}">
                                    <input type="radio" name="variant_selection" value="{{ $index }}" 
                                        class="peer sr-only variant-radio" 
                                        data-id="{{ $variant->id }}"
                                        data-index="{{ $index }}"
                                        {{ $variant->stock == 0 ? 'disabled' : '' }}
                                        {{ $index === 0 && $variant->stock > 0 ? 'checked' : '' }}>
                                    <span class="block px-4 py-2 rounded-xl border-2 text-sm font-bold transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 border-gray-200 bg-white text-gray-600 hover:border-emerald-300">
                                        {{ $variant->unit }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-8 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Sisa Stok Tersedia (Varian Terpilih)</p>
                                <p class="text-lg font-bold text-gray-900" id="display-stock">
                                    {{ $product->variants->first()->stock ?? 0 }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Tambah ke Keranjang -->
                    @if($totalStock > 0)
                        <form action="{{ route('cart.store') }}" method="POST" class="mt-auto">
                            @csrf
                            <input type="hidden" name="product_variant_id" id="variant-input" value="{{ $product->variants->first()->id ?? '' }}">
                            <input type="hidden" name="quantity" id="qty-input" value="1">
                            
                            <div class="flex flex-col sm:flex-row gap-4">
                                <!-- Stepper Kuantitas -->
                                <div class="flex items-center justify-between border-2 border-gray-200 rounded-full px-2 py-1 h-14 bg-white sm:w-40 shrink-0">
                                    <button type="button" id="btn-minus" class="w-10 h-10 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-100 transition-colors disabled:opacity-50" aria-label="Kurangi">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                    </button>
                                    
                                    <span id="qty-display" class="text-xl font-bold text-gray-900 w-10 text-center select-none">1</span>
                                    
                                    <button type="button" id="btn-plus" class="w-10 h-10 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-100 transition-colors disabled:opacity-50" aria-label="Tambah">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </button>
                                </div>
                                
                                <!-- Tombol Submit -->
                                <button type="submit" id="btn-submit" class="flex-1 h-14 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-lg rounded-full flex items-center justify-center gap-2 shadow-lg shadow-emerald-200 transition-all transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Tambah Keranjang
                                </button>
                            </div>
                        </form>
                    @else
                        <button disabled class="w-full h-14 bg-gray-200 text-gray-400 font-bold text-lg rounded-full flex items-center justify-center cursor-not-allowed mt-auto">
                            Semua Varian Habis
                        </button>
                    @endif

                </div>
            </div>
        </div>
        
        <!-- Produk Terkait (Jika ada) -->
        @if($relatedProducts->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Mungkin Anda juga butuh:</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                @foreach($relatedProducts as $related)
                <a href="{{ route('shop.show', $related->slug) }}" class="group block bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden flex flex-col">
                    <div class="relative w-full overflow-hidden flex justify-center items-center bg-white border-b border-gray-100 aspect-square p-4">
                        @if($related->image)
                            <img src="{{ Storage::url($related->image) }}" alt="{{ $related->name }}" class="object-contain w-full h-full transition-transform duration-300 group-hover:scale-105">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <span class="text-4xl">🛒</span>
                            </div>
                        @endif
                    </div>
                    <div class="bg-white p-4 flex-1 flex flex-col">
                        <p class="text-xs font-bold text-emerald-600 mb-1 tracking-wider uppercase">{{ $related->category->name }}</p>
                        <h3 class="text-gray-900 font-bold text-lg mb-2 truncate">{{ $related->name }}</h3>
                        <p class="text-lg font-black text-emerald-700">Mulai Rp {{ number_format($related->variants->min('price') ?? 0, 0, ',', '.') }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

@if($totalStock > 0)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const variantsData = {!! $variantsJson !!};
        
        const btnMinus = document.getElementById('btn-minus');
        const btnPlus = document.getElementById('btn-plus');
        const qtyDisplay = document.getElementById('qty-display');
        const qtyInput = document.getElementById('qty-input');
        const variantInput = document.getElementById('variant-input');
        const btnSubmit = document.getElementById('btn-submit');
        
        const displayPrice = document.getElementById('display-price');
        const displayUnit = document.getElementById('display-unit');
        const displayStock = document.getElementById('display-stock');
        
        let currentVariant = variantsData.find(v => v.stock > 0) || variantsData[0];
        let currentMaxStock = currentVariant.stock;
        
        function selectVariant(index) {
            currentVariant = variantsData[index];
            currentMaxStock = currentVariant.stock;
            
            // Update UI Details
            displayPrice.textContent = 'Rp ' + currentVariant.formatted_price;
            displayUnit.textContent = '/ ' + currentVariant.unit;
            
            if(currentMaxStock < 5 && currentMaxStock > 0) {
                displayStock.innerHTML = `${currentMaxStock} <span class="text-xs ml-2 px-2 py-1 bg-red-100 text-red-600 rounded-full animate-pulse">Hampir Habis!</span>`;
            } else if(currentMaxStock == 0) {
                displayStock.innerHTML = `<span class="text-red-500">Habis</span>`;
            } else {
                displayStock.textContent = currentMaxStock;
            }
            
            // Update hidden input
            variantInput.value = currentVariant.id;
            
            // Reset qty to 1 or 0
            if (currentMaxStock === 0) {
                updateQty(0);
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = 'Stok Habis';
            } else {
                updateQty(1);
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg> Tambah Keranjang`;
            }
        }
        
        function updateQty(value) {
            qtyDisplay.textContent = value;
            qtyInput.value = value;
            
            // Atur status tombol
            btnMinus.disabled = value <= 1;
            btnPlus.disabled = value >= currentMaxStock || currentMaxStock === 0;
        }

        btnMinus.addEventListener('click', function() {
            let currentValue = parseInt(qtyInput.value);
            if (currentValue > 1) {
                updateQty(currentValue - 1);
            }
        });

        btnPlus.addEventListener('click', function() {
            let currentValue = parseInt(qtyInput.value);
            if (currentValue < currentMaxStock) {
                updateQty(currentValue + 1);
            }
        });
        
        // Add listeners to variant radios
        document.querySelectorAll('.variant-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    selectVariant(parseInt(this.value));
                }
            });
        });
        
        // Init with first available variant
        const firstAvailableIndex = variantsData.findIndex(v => v.stock > 0);
        if (firstAvailableIndex !== -1) {
            const radio = document.querySelector(`.variant-radio[value="${firstAvailableIndex}"]`);
            if(radio) {
                radio.checked = true;
                selectVariant(firstAvailableIndex);
            }
        } else {
            const radio = document.querySelector(`.variant-radio[value="0"]`);
            if(radio) {
                radio.checked = true;
                selectVariant(0);
            }
        }
    });
</script>
@endif
@endsection
