<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\TransactionResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction; // Pastikan ini di-import
use App\Filament\Exports\TransactionExporter; // Pastikan class ini sudah ada
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tombol Export akan muncul di sini
            ExportAction::make()
                ->exporter(TransactionExporter::class)
                ->label('Export Excel'),
            
            CreateAction::make(),
        ];
    }
}