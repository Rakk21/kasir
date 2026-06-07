<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Transaction;

class LatestTransaction extends BaseWidget
{
    // Tampilkan di urutan ke-3 (di bawah grafik)
    protected static ?int $sort = 3;
    
    // Buat ukurannya full width
    protected int | string | array $columnSpan = 'full';
    
    // Judul tabel
    protected static ?string $heading = '5 Transaksi Terakhir';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Ambil data dari yang paling baru, batasi cuma 5 baris
                Transaction::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Invoice')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i'),
                    
                Tables\Columns\TextColumn::make('total')
                    ->money('IDR')
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Status')
                    ->badge(),
            ])
            // Matikan fitur halaman (pagination) karena kita cuma butuh sekilas
            ->paginated(false); 
    }
}