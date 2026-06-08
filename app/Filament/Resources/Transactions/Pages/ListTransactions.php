<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Exports\TransactionExporter;
use App\Filament\Resources\Transactions\TransactionResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
          ExportAction::make()
    ->exporter(TransactionExporter::class)
    ->label('Export Excel')
    ->columnMapping(false)
                ->form([
                    Select::make('periode')
                        ->label('Periode Laporan')
                        ->options([
                            'hari' => 'Hari Ini',
                            'minggu' => 'Minggu Ini',
                            'bulan' => 'Bulan Ini',
                        ])
                        ->required(),
                ])
                ->modifyQueryUsing(function ($query, array $data) {

                    if (($data['periode'] ?? null) === 'hari') {
                        $query->whereDate('created_at', today());
                    }

                    if (($data['periode'] ?? null) === 'minggu') {
                        $query->whereBetween('created_at', [
                            now()->startOfWeek(),
                            now()->endOfWeek(),
                        ]);
                    }

                    if (($data['periode'] ?? null) === 'bulan') {
                        $query->whereMonth('created_at', now()->month)
                              ->whereYear('created_at', now()->year);
                    }

                    return $query;
                }),

            CreateAction::make(),
        ];
    }
}