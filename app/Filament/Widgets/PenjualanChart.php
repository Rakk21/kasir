<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Transaction; // Wajib import Model Transaction
use Carbon\Carbon; // Wajib import Carbon untuk mengatur tanggal

class PenjualanChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Pendapatan (7 Hari Terakhir)';
    
    protected static ?int $sort = 2; 

    protected int | string | array $columnSpan = 'full';

    // TAMBAHKAN INI: Membatasi tinggi grafik agar tidak terlalu raksasa
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        // Looping mundur dari 6 hari yang lalu sampai hari ini (total 7 hari)
        for ($i = 6; $i >= 0; $i--) {
            // Ambil tanggal mundur
            $date = Carbon::now()->subDays($i);
            
            // Format label bawahnya jadi tanggal (Contoh: '02 Jun', '03 Jun', dst)
            $labels[] = $date->format('d M'); 
            
            // Query hitung total pendapatan dari database khusus di tanggal tersebut
            $pendapatanPerHari = Transaction::whereDate('created_at', $date->toDateString())
                                ->where('payment_status', 'paid') // Opsional: Hanya hitung yang sudah lunas
                                ->sum('total');

            // Masukkan hasil hitungannya ke dalam array data
            $data[] = $pendapatanPerHari;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Rp)',
                    'data' => $data, // <-- Sekarang pakai data asli dari database
                    'backgroundColor' => '#3b82f6', 
                    'borderColor' => '#3b82f6',
                ],
            ],
            'labels' => $labels, // <-- Labelnya jadi tanggal otomatis
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}