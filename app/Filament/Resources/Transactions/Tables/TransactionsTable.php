<?php

namespace App\Filament\Resources\Transactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\TransactionExporter;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->searchable(),
               TextColumn::make('customer.name')
    ->label('Pelanggan')
    ->searchable(),

TextColumn::make('user.name')
    ->label('Kasir')
    ->searchable(),
               TextColumn::make('subtotal')
    ->money('IDR')
    ->sortable(),

TextColumn::make('discount')
    ->money('IDR')
    ->sortable(),

TextColumn::make('tax')
    ->money('IDR')
    ->sortable(),

TextColumn::make('total')
    ->money('IDR')
    ->sortable(),
                TextColumn::make('payment_method')
                    ->badge(),
                TextColumn::make('payment_status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('periode')
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
                    ->query(function ($query, array $data) {
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
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
          ->toolbarActions([
    ExportAction::make()
        ->label('Export Excel')
        ->exporter(TransactionExporter::class)
        ->formats([
            ExportFormat::Xlsx,
        ]),

    BulkActionGroup::make([
        DeleteBulkAction::make(),
    ]),
]);
    }
}
