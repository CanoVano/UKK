@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.categories.index') }}" class="text-gray-500 hover:text-teal-600 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Edit Kategori</h2>
        <p class="text-gray-600 text-sm mt-1">Ubah detail data kategori.</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 max-w-2xl">
    <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="p-6 space-y-6">
        @csrf
        @method('PUT')

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required 
                class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm transition-all @error('name') border-red-500 focus:ring-red-500 @enderror">
            @error('name')
                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
            @enderror
            <p class="mt-1.5 text-xs text-gray-500">Slug saat ini: {{ $category->slug }} (akan diperbarui otomatis)</p>
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (Opsional)</label>
            <textarea name="description" id="description" rows="4" 
                class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm transition-all @error('description') border-red-500 focus:ring-red-500 @enderror">{{ old('description', $category->description) }}</textarea>
            @error('description')
                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
            <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700 transition-colors shadow-sm">
                Perbarui Kategori
            </button>
        </div>
    </form>
</div>
@endsection
