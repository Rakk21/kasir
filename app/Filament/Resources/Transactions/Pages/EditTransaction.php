<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\TransactionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    /**
     * Fungsi agar setelah klik "Save changes", kasir langsung diarahkan 
     * kembali ke halaman tabel depan.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Fungsi ini DIBIARKAN KOSONG/TIDAK DIISI MATEMATIKA.
     * Hanya ditambahkan sebagai tempat jaga-jaga jika ke depannya 
     * kamu ingin mengubah data lain sebelum di-save (misal: mencatat siapa yang mengedit).
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Perhitungan subtotal & total sudah aman dikerjakan oleh TransactionForm.php
        
        return $data;
    }
}