@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<!-- Header Dashboard -->
<div class="mb-8">
    <h2 class="text-3xl font-extrabold text-gray-900 flex items-center gap-2">
        Hai, {{ explode(' ', Auth::user()->name)[0] ?? 'Admin' }}! <span class="text-4xl">👋</span>
    </h2>
    <p class="text-gray-500 text-sm mt-2">Berikut ringkasan aktivitas warung mama hari ini.</p>
</div>

<!-- 4 Kartu Metrik -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Total Pendapatan -->
    <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 p-6 flex flex-col justify-between hover:-translate-y-1 transition-transform duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="w-14 h-14 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            </div>
            <div class="text-right">
                <h3 class="text-sm font-semibold text-gray-500">Total Pendapatan</h3>
                <p class="text-2xl font-black text-gray-900 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2 text-xs">
            <span class="inline-flex items-center px-2 py-1 rounded-md font-bold bg-emerald-100 text-emerald-700">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path></svg>
                100%
            </span>
            <span class="text-gray-400 font-medium">dari kemarin</span>
        </div>
    </div>

    <!-- Total Pesanan -->
    <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 p-6 flex flex-col justify-between hover:-translate-y-1 transition-transform duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <div class="text-right">
                <h3 class="text-sm font-semibold text-gray-500">Total Pesanan</h3>
                <p class="text-2xl font-black text-gray-900 mt-1">{{ $totalOrders }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2 text-xs">
            <span class="text-blue-500 font-medium">Semua pesanan masuk</span>
        </div>
    </div>

    <!-- Pesanan Aktif -->
    <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 p-6 flex flex-col justify-between hover:-translate-y-1 transition-transform duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="w-14 h-14 rounded-full bg-orange-50 flex items-center justify-center text-orange-500">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="text-right">
                <h3 class="text-sm font-semibold text-gray-500">Pesanan Aktif</h3>
                <p class="text-2xl font-black text-gray-900 mt-1">{{ $pendingOrders }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2 text-xs">
            <span class="text-orange-500 font-medium">Menunggu pembayaran/proses</span>
        </div>
    </div>

    <!-- Total Produk -->
    <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 p-6 flex flex-col justify-between hover:-translate-y-1 transition-transform duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="w-14 h-14 rounded-full bg-purple-50 flex items-center justify-center text-purple-500">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <div class="text-right">
                <h3 class="text-sm font-semibold text-gray-500">Total Produk</h3>
                <p class="text-2xl font-black text-gray-900 mt-1">{{ $totalProducts }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2 text-xs">
            <span class="text-purple-500 font-medium">Katalog aktif</span>
        </div>
    </div>
</div>

<!-- Middle Section: Chart & Top Products -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Chart -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Grafik Pendapatan</h3>
                <p class="text-sm text-gray-500">7 hari terakhir</p>
            </div>
            <select class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 p-2.5 outline-none">
                <option>7 Hari Terakhir</option>
            </select>
        </div>
        <div class="relative h-72 w-full">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Top Products -->
    <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Produk Terlaris</h3>
                <p class="text-sm text-gray-500">Produk dengan penjualan tertinggi</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700">Lihat Semua</a>
        </div>
        
        <div class="space-y-4">
            @forelse($topProducts as $index => $item)
            <div class="flex items-center justify-between pb-4 border-b border-gray-50 last:border-0 last:pb-0">
                <div class="flex items-center gap-4">
                    <span class="text-sm font-bold text-gray-900">{{ $index + 1 }}</span>
                    <div class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-100 overflow-hidden flex-shrink-0 p-1">
                        @if($item->productVariant && $item->productVariant->product->image)
                            <img src="{{ asset('storage/' . $item->productVariant->product->image) }}" class="w-full h-full object-contain mix-blend-multiply" alt="">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900">{{ $item->productVariant->product->name ?? 'Produk Dihapus' }}</h4>
                        <p class="text-xs text-gray-500">{{ $item->total_sold }} terjual</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-sm font-bold text-emerald-600">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</span>
                </div>
            </div>
            @empty
            <div class="text-center py-6">
                <p class="text-sm text-gray-500">Belum ada data penjualan.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 overflow-hidden mb-8">
    <div class="px-6 py-5 flex justify-between items-center border-b border-gray-50">
        <h3 class="text-lg font-bold text-gray-900 italic">Pesanan Terbaru</h3>
        <a href="{{ route('admin.orders.index') }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 transition-colors">Lihat Semua</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Pesanan</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 bg-white">
                @forelse($recentOrders as $order)
                <tr class="hover:bg-gray-50/50 transition-colors group">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="font-bold text-gray-900">{{ $order->order_number }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs border border-emerald-100">
                                {{ substr($order->user->name ?? 'A', 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $order->user->name ?? 'User Dihapus' }}</p>
                                <p class="text-xs text-gray-500">{{ $order->whatsapp_number ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                        {{ $order->created_at->translatedFormat('d M Y, H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="font-bold text-emerald-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($order->status === 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-600">Menunggu</span>
                        @elseif($order->status === 'paid')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-600">Dibayar</span>
                        @elseif($order->status === 'preparing')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-50 text-yellow-600">Disiapkan</span>
                        @elseif($order->status === 'ready_for_pickup')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600">Siap Diambil</span>
                        @elseif($order->status === 'completed')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600">Selesai</span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600">Batal</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        <p class="font-medium">Belum ada pesanan masuk.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Info Cards Bottom -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
        </div>
        <div>
            <h4 class="text-sm font-bold text-gray-900">Transaksi Aman</h4>
            <p class="text-[10px] text-gray-500 mt-0.5">Setiap transaksi dijamin keamanannya</p>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
        </div>
        <div>
            <h4 class="text-sm font-bold text-gray-900">Produk Berkualitas</h4>
            <p class="text-[10px] text-gray-500 mt-0.5">Produk pilihan untuk keluarga tercinta</p>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
        </div>
        <div>
            <h4 class="text-sm font-bold text-gray-900">Pengemasan Cepat</h4>
            <p class="text-[10px] text-gray-500 mt-0.5">Pesanan diproses dengan cepat dan aman</p>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div>
            <h4 class="text-sm font-bold text-gray-900">Layanan Ramah</h4>
            <p class="text-[10px] text-gray-500 mt-0.5">Kami siap membantu kapanpun</p>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Data dari Controller
        const labels = {!! json_encode($chartData['labels']) !!};
        const data = {!! json_encode($chartData['data']) !!};
        
        // Konfigurasi Gradien Warna (Emerald)
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)'); // Emerald-500 dengan opacity 0.2
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');   // Transparan di bawah
        
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: data,
                    borderColor: '#10b981', // Emerald 500
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#10b981',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4 // Kurva melengkung (Bezier)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            padding: 20,
                            font: {
                                family: "'Inter', sans-serif",
                                size: 12,
                                weight: '500'
                            },
                            color: '#6b7280'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        padding: 12,
                        titleFont: {
                            family: "'Inter', sans-serif",
                            size: 13
                        },
                        bodyFont: {
                            family: "'Inter', sans-serif",
                            size: 14,
                            weight: 'bold'
                        },
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6',
                            drawBorder: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                family: "'Inter', sans-serif",
                                size: 11
                            },
                            callback: function(value, index, values) {
                                if(value >= 1000) {
                                    return 'Rp ' + value/1000 + 'k';
                                }
                                return 'Rp ' + value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                family: "'Inter', sans-serif",
                                size: 11
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });
    });
</script>
@endpush
