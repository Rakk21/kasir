<?php

namespace App\Filament\Exports;

use App\Models\Transaction;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;
use Carbon\Carbon;

class TransactionExporter extends Exporter
{
    protected static ?string $model = Transaction::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('invoice_number')
                ->label('No Invoice'),

            ExportColumn::make('created_at')
                ->label('Waktu')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('d-m-Y H:i')),

            ExportColumn::make('subtotal')
                ->label('Subtotal')
                ->formatStateUsing(fn ($state) => Number::format($state, locale: 'id')),

            ExportColumn::make('discount')
                ->label('Diskon')
                ->formatStateUsing(fn ($state) => Number::format($state, locale: 'id')),

            ExportColumn::make('tax')
                ->label('Pajak')
                ->formatStateUsing(fn ($state) => Number::format($state, locale: 'id')),

            ExportColumn::make('total')
                ->label('Total Akhir')
                ->formatStateUsing(fn ($state) => Number::format($state, locale: 'id')),

            ExportColumn::make('payment_status')
                ->label('Status')
                ->formatStateUsing(fn ($state) => match ($state) {
                    'paid' => 'Lunas',
                    'pending' => 'Pending',
                    'failed' => 'Gagal',
                    default => ucfirst($state),
                }),
        ];
    }

  public static function getCompletedNotificationBody(Export $export): string
{
    return 'Laporan transaksi berhasil diekspor.';
}

public static function getCompletedNotification(Export $export): ?\Filament\Notifications\Notification
    {
        $url = $export->getFileUrl();

        if (blank($url)) {
            return null;
        }

        return \Filament\Notifications\Notification::make()
            ->title('Ekspor Transaksi Selesai')
            ->success()
            ->body('Laporan transaksi kamu sudah siap diunduh.')
            ->actions([
                \Filament\Notifications\Actions\Action::make('download')
                    ->label('Download Sekarang')
                    ->url($url)
                    ->button()
                    ->color('primary')
                    ->markAsRead(),
            ])
            ->persistent();
    }
}
