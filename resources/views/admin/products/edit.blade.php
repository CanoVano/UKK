@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.products.index') }}" class="text-gray-500 hover:text-teal-600 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Edit Produk</h2>
        <p class="text-gray-600 text-sm mt-1">Ubah detail data barang.</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 max-w-4xl">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required 
                        class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm transition-all @error('name') border-red-500 @enderror">
                    @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" id="category_id" required 
                        class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm transition-all @error('category_id') border-red-500 @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (Opsional)</label>
                    <textarea name="description" id="description" rows="5" 
                        class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm transition-all @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                    @error('description')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Variants Dynamic Form -->
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <div class="flex justify-between items-center mb-3">
                        <label class="block text-sm font-bold text-gray-800">Varian Produk <span class="text-red-500">*</span></label>
                        <button type="button" id="add-variant-btn" class="text-xs px-3 py-1.5 bg-teal-600 text-white font-medium rounded hover:bg-teal-700 transition-colors">
                            + Tambah Varian
                        </button>
                    </div>
                    
                    <div id="variants-container" class="space-y-3">
                        @foreach($product->variants as $index => $variant)
                        <div class="variant-row flex gap-3 items-start bg-white p-3 rounded-lg border border-gray-100 shadow-sm relative">
                            <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Satuan</label>
                                <input type="text" name="variants[{{ $index }}][unit]" required value="{{ old('variants.'.$index.'.unit', $variant->unit) }}"
                                    class="block w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-md focus:ring-1 focus:ring-teal-500 text-sm">
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Harga (Rp)</label>
                                <input type="number" name="variants[{{ $index }}][price]" required min="0" value="{{ old('variants.'.$index.'.price', round($variant->price)) }}"
                                    class="block w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-md focus:ring-1 focus:ring-teal-500 text-sm">
                            </div>
                            <div class="w-20">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Stok</label>
                                <input type="number" name="variants[{{ $index }}][stock]" required min="0" value="{{ old('variants.'.$index.'.stock', $variant->stock) }}"
                                    class="block w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-md focus:ring-1 focus:ring-teal-500 text-sm">
                            </div>
                            <button type="button" class="remove-variant-btn mt-6 text-red-500 hover:bg-red-50 p-1.5 rounded disabled:opacity-50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    @error('variants')<p class="mt-2 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <!-- Image -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Gambar Produk (Opsional)</label>
                    
                    @if($product->image)
                    <div class="mb-3">
                        <p class="text-xs text-gray-500 mb-1">Gambar saat ini:</p>
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-24 w-24 object-cover rounded-lg border border-gray-200">
                    </div>
                    @endif

                    <input type="file" name="image" id="image" accept="image/*"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 transition-colors border border-gray-200 rounded-lg bg-gray-50 @error('image') border-red-500 @enderror">
                    <p class="mt-1.5 text-xs text-gray-500">Biarkan kosong jika tidak ingin mengubah gambar.</p>
                    @error('image')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="mt-8 pt-5 border-t border-gray-100 flex justify-end gap-3">
            <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700 transition-colors shadow-sm">
                Perbarui Produk
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('variants-container');
        const addBtn = document.getElementById('add-variant-btn');
        let variantIndex = {{ $product->variants->count() }};

        function updateRemoveButtons() {
            const rows = container.querySelectorAll('.variant-row');
            rows.forEach((row, index) => {
                const removeBtn = row.querySelector('.remove-variant-btn');
                removeBtn.disabled = rows.length === 1; // Jangan hapus jika hanya 1
            });
        }
        
        updateRemoveButtons();

        addBtn.addEventListener('click', function() {
            const newRow = document.createElement('div');
            newRow.className = 'variant-row flex gap-3 items-start bg-white p-3 rounded-lg border border-gray-100 shadow-sm relative mt-3';
            newRow.innerHTML = `
                <div class="flex-1">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Satuan</label>
                    <input type="text" name="variants[${variantIndex}][unit]" required placeholder="Cth: 1 Kg" 
                        class="block w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-md focus:ring-1 focus:ring-teal-500 text-sm">
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Harga (Rp)</label>
                    <input type="number" name="variants[${variantIndex}][price]" required min="0" placeholder="5000"
                        class="block w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-md focus:ring-1 focus:ring-teal-500 text-sm">
                </div>
                <div class="w-20">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Stok</label>
                    <input type="number" name="variants[${variantIndex}][stock]" required min="0" value="0"
                        class="block w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-md focus:ring-1 focus:ring-teal-500 text-sm">
                </div>
                <button type="button" class="remove-variant-btn mt-6 text-red-500 hover:bg-red-50 p-1.5 rounded">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            `;
            container.appendChild(newRow);
            variantIndex++;
            updateRemoveButtons();
        });

        container.addEventListener('click', function(e) {
            const btn = e.target.closest('.remove-variant-btn');
            if (btn && !btn.disabled) {
                btn.closest('.variant-row').remove();
                updateRemoveButtons();
            }
        });
    });
</script>
@endpush
