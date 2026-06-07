<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\TransactionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 1. Set Kasir yang sedang login
        $data['user_id'] = auth()->id();

        // 2. Buat Invoice Number otomatis
        $data['invoice_number'] = 'INV-' . now()->format('Ymd-His');

        // PERHITUNGAN SUBTOTAL DAN TOTAL DIHAPUS.
        // Biarkan angka dari frontend yang masuk ke database.

        return $data;
    }
}