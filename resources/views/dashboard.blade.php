@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container py-4">

    <div class="flex flex-col md:flex-row justify-between items-center mb-8">
        <h2 class="mb-4 md:mb-0 font-bold text-2xl text-gray-800">Ringkasan Penjualan</h2>
        <div class="filter-container">
            <div class="date-picker-wrapper">
                <i class="fas fa-calendar-alt date-picker-icon"></i>
                <input type="text" id="dateRangePicker" name="period"
                       class="date-picker-input"
                       readonly
                       data-start-date="{{ $startDate->format('Y-m-d') }}"
                       data-end-date="{{ $endDate->format('Y-m-d') }}">
            </div>
        </div>
    </div>

    <div class="summary-card-grid mb-6">
        <div class="summary-card" style="background: linear-gradient(135deg, #EF4444, #F87171);">
            <div class="card-content-wrapper">
                <div>
                    <p class="card-title">Pendapatan</p>
                    <h3 class="card-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                </div>
                <div class="card-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
        <div class="summary-card" style="background: linear-gradient(135deg, #22C55E, #4ADE80);">
             <div class="card-content-wrapper">
                <div>
                    <p class="card-title">Pengeluaran</p>
                    <h3 class="card-value">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                </div>
                <div class="card-icon">
                    <i class="fas fa-arrow-trend-down"></i>
                </div>
            </div>
        </div>
        <div class="summary-card" style="background: linear-gradient(135deg, #3B82F6, #60A5FA);">
             <div class="card-content-wrapper">
                <div>
                    <p class="card-title">Transaksi</p>
                    <h3 class="card-value">{{ $totalTransaksi }}</h3>
                </div>
                <div class="card-icon">
                    <i class="fas fa-receipt"></i>
                </div>
            </div>
        </div>
        <div class="summary-card" style="background: linear-gradient(135deg, #8B5CF6, #A78BFA);">
             <div class="card-content-wrapper">
                <div>
                    <p class="card-title">Total Produk</p>
                    <h3 class="card-value">{{ $totalProduk }}</h3>
                </div>
                <div class="card-icon">
                    <i class="fas fa-box-open"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="main-chart-card">
        <div class="main-chart-grid">
            <div class="chart-column">
                <h3 class="font-semibold text-gray-800 mb-2">Grafik Penjualan ({{ $titlePeriod }})</h3>
                <div class="relative" style="height:320px;">
                    <canvas id="penjualanChart"></canvas> 
                </div>
            </div>
            <div class="activity-column">
                <h3 class="font-semibold text-gray-800 mb-2">Aktivitas Terakhir</h3>
                <ul class="space-y-3 text-sm">
                    @forelse($logAktivitas as $log)
                        <li class="flex justify-between items-center border-b pb-2">
                            <div>
                                <span>{{ $log['keterangan'] }}</span>
                                <small class="block text-gray-400">{{ \Carbon\Carbon::parse($log['waktu'])->diffForHumans() }}</small>
                            </div>
                            @if($log['nominal'])
                                <span class="font-semibold {{ $log['arah'] == 'naik' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $log['arah'] == 'naik' ? '+' : '-' }} Rp {{ number_format($log['nominal'], 0, ',', '.') }}
                                </span>
                            @endif
                        </li>
                    @empty
                        <li class="text-gray-500">Belum ada aktivitas.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style>
    .summary-card-grid {
        display: grid;
        gap: 1.5rem;
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
    @media (min-width: 640px) {
        .summary-card-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media (min-width: 1024px) {
        .summary-card-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }
    .summary-card {
        color: white;
        padding: 1.5rem;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    }
    .card-content-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    .card-title {
        font-size: 0.875rem;
        font-weight: 600;
        opacity: 0.9;
    }
    .card-value {
        font-size: 1.875rem;
        font-weight: 700;
        margin-top: 0.25rem;
    }
    .card-icon {
        font-size: 3rem;
        opacity: 0.3;
    }
    .date-picker-wrapper {
        position: relative;
    }
    .date-picker-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #fff;
        z-index: 10;
    }
    .date-picker-input {
        background-color: #3B82F6 !important;
        color: white !important;
        border: none !important;
        border-radius: 0.5rem !important;
        padding: 0.6rem 1rem 0.6rem 2.5rem !important;
        font-weight: 600;
        cursor: pointer;
        min-width: 280px;
    }
    .date-picker-input::placeholder {
        color: white;
        opacity: 0.8;
    }
    .main-chart-card {
        background-color: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    }
    .main-chart-grid {
        display: grid;
        gap: 1.5rem;
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
    @media (min-width: 1024px) {
        .main-chart-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
        .chart-column {
            grid-column: span 2 / span 2;
        }
        .activity-column {
            border-left: 1px solid #e5e7eb;
            padding-left: 1.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/id.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
    $(document).ready(function() {
        
        const startDateString = $('#dateRangePicker').data('start-date');
        const endDateString = $('#dateRangePicker').data('end-date');

        moment.locale('id'); 

        var start = moment(startDateString, "YYYY-MM-DD");
        var end = moment(endDateString, "YYYY-MM-DD");

        function cb(start, end) {
            $('#dateRangePicker').val(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
        }

        $('#dateRangePicker').daterangepicker({
            startDate: start,
            endDate: end,
            locale: {
                format: 'D MMMM YYYY',
                applyLabel: 'Terapkan',
                cancelLabel: 'Batal',
                fromLabel: 'Dari',
                toLabel: 'Hingga',
                customRangeLabel: 'Pilih Tanggal',
                daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                firstDay: 1
            },
            ranges: {
               'Hari Ini': [moment(), moment()],
               'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
               '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
               'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
               'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);

        $('#dateRangePicker').on('apply.daterangepicker', function(ev, picker) {
            var newPeriod = picker.startDate.format('YYYY-MM-DD') + '_' + picker.endDate.format('YYYY-MM-DD');
            var baseUrl = window.location.href.split('?')[0];
            window.location.href = baseUrl + '?period=' + newPeriod;
        });

        const labels = {!! json_encode($chartLabels) !!};
        const data = {!! json_encode($chartData) !!};
        const ctx = document.getElementById('penjualanChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value, index, values) {
                                if (value >= 1000000) return 'Rp ' + (value / 1000000) + 'jt';
                                if (value >= 1000) return 'Rp ' + (value / 1000) + 'rb';
                                return 'Rp ' + value;
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush