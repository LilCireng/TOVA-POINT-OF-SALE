<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\Barang;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', now()->subDays(6)->format('Y-m-d') . '_' . now()->format('Y-m-d'));
        $dates = explode('_', $period);
        
        try {
            $startDate = Carbon::createFromFormat('Y-m-d', $dates[0])->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $dates[1])->endOfDay();
        } catch (\Exception $e) {
            $startDate = now()->subDays(6)->startOfDay();
            $endDate = now()->endOfDay();
        }
        
        if ($startDate->isSameDay($endDate)) {
            $titlePeriod = $startDate->format('d M Y');
        } else {
            $titlePeriod = $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');
        }

        $penjualan = Penjualan::whereBetween('created_at', [$startDate, $endDate])->get();
        $pembelian = Pembelian::whereBetween('created_at', [$startDate, $endDate])->get();

        $totalPendapatan = $penjualan->sum('total_harga');
        $totalPengeluaran = $pembelian->sum('total_harga');
        $totalTransaksi = $penjualan->count();
        $totalProduk = Barang::count();

        $dateRange = collect();
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateRange->push($date->copy());
        }

        $chartLabels = $dateRange->map(fn ($date) => $date->format('d M'));
        $penjualanHarian = $penjualan->groupBy(fn ($p) => Carbon::parse($p->created_at)->format('Y-m-d'));
        $chartData = $dateRange->map(fn ($date) => $penjualanHarian->get($date->format('Y-m-d'), collect())->sum('total_harga'));

        $logAktivitas = $this->getLogAktivitas();

        return view('dashboard', compact(
            'totalPendapatan', 'totalPengeluaran', 'totalTransaksi', 'totalProduk',
            'logAktivitas', 'chartLabels', 'chartData', 'period', 'titlePeriod',
            'startDate', 'endDate'
        ));
    }

    private function getLogAktivitas(): Collection
    {
        $penjualanTerakhir = Penjualan::latest()->take(5)->get()->map(function ($item) {
            return [
                'keterangan' => 'Penjualan #' . $item->id,
                'waktu' => $item->created_at,
                'nominal' => $item->total_harga,
                'arah' => 'naik'
            ];
        });

        $pembelianTerakhir = Pembelian::latest()->take(5)->get()->map(function ($item) {
            return [
                'keterangan' => 'Pembelian #' . $item->id,
                'waktu' => $item->created_at,
                'nominal' => $item->total_harga,
                'arah' => 'turun'
            ];
        });

        return $penjualanTerakhir->concat($pembelianTerakhir)
            ->sortByDesc('waktu')
            ->take(5)
            ->values();
    }
}