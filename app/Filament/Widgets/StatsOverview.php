<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Transaction;
use Carbon\Carbon; // Wajib import Carbon untuk manipulasi waktu

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1; 

    // Fitur MAGIC: Auto-refresh data setiap 10 detik tanpa reload halaman!
    protected  ?string $pollingInterval = '10s'; 

    protected function getStats(): array
    {
        // Tetapkan batas waktu HARI INI (mulai dari jam 00:00:00)
        $hariIni = Carbon::today();

        // 1. Hitung Pendapatan HARI INI
        $pendapatanHariIni = Transaction::whereDate('created_at', $hariIni)
                                        ->where('payment_status', 'paid')
                                        ->sum('total');

        // 2. Hitung Transaksi HARI INI
        $transaksiHariIni = Transaction::whereDate('created_at', $hariIni)
                                       ->count();

        // 3. Hitung Pendapatan BULAN INI (sebagai perbandingan)
        $pendapatanBulanIni = Transaction::whereMonth('created_at', Carbon::now()->month)
                                         ->whereYear('created_at', Carbon::now()->year)
                                         ->where('payment_status', 'paid')
                                         ->sum('total');

        return [
            Stat::make('Pendapatan Hari Ini', 'Rp ' . number_format($pendapatanHariIni, 0, ',', '.'))
                ->description('Reset otomatis tiap jam 12 malam')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Transaksi Hari Ini', $transaksiHariIni . ' Struk')
                ->description('Penjualan tanggal ' . $hariIni->format('d M Y'))
                ->color('primary'),

            Stat::make('Total Bulan Ini', 'Rp ' . number_format($pendapatanBulanIni, 0, ',', '.'))
                ->description('Akumulasi bulan ' . Carbon::now()->format('F'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info'),
        ];
    }
}