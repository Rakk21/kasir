<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Transaction;

class StatsOverview extends BaseWidget
{
    // Mengatur urutan agar tampil di paling atas
    protected static ?int $sort = 1; 

    protected function getStats(): array
    {
        // 1. Hitung total pendapatan dari seluruh transaksi
        $totalPendapatan = Transaction::sum('total');

        // 2. Hitung jumlah struk/transaksi yang terjadi
        $totalTransaksi = Transaction::count();

        return [
            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalPendapatan, 0, ',', '.'))
                ->description('Seluruh uang masuk')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Transaksi', $totalTransaksi . ' Struk')
                ->description('Total penjualan tercatat')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            // Kalau kamu punya tabel/Model Customer, kamu bisa aktifkan kode di bawah ini:
            /*
            Stat::make('Total Pelanggan', \App\Models\Customer::count() . ' Orang')
                ->description('Pelanggan terdaftar')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
            */
        ];
    }
}