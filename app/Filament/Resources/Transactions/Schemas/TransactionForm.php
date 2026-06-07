<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('invoice_number')
                    ->label('Invoice'),

                Select::make('customer_id')
                    ->label('Pelanggan')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload(),

                Select::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options([
                        'cash' => 'Cash',
                        'qris' => 'QRIS',
                        'transfer' => 'Transfer',
                    ])
                    ->required(),

                Select::make('payment_status')
                    ->options([
                        'paid' => 'Paid',
                        'unpaid' => 'Unpaid',
                    ])
                    ->default('paid'),

                Repeater::make('details')
                    ->relationship()
                    ->live() 
                    ->afterStateUpdated(function (callable $get, callable $set) {
                        // Dipanggil saat baris ditambah/dihapus (dari luar repeater item)
                        self::updateGrandTotal($get, $set, false);
                    })
                    ->schema([
                        Select::make('product_id')
                            ->label('Produk')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $product = Product::find($state);

                                if ($product) {
                                    $price = $product->price;
                                    $set('price', $price);
                                    $qty = (int) ($get('qty') ?: 1);
                                    $set('subtotal', $price * $qty); // Set subtotal per baris
                                }
                                
                                // Panggil fungsi hitung total global (true = dari dalam repeater)
                                self::updateGrandTotal($get, $set, true);
                            })
                            ->required(),

                        TextInput::make('qty')
                            ->numeric()
                            ->default(1)
                            ->live()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $price = (float) $get('price');
                                $set('subtotal', $price * (int) $state); // Set subtotal per baris
                                
                                // Panggil fungsi hitung total global
                                self::updateGrandTotal($get, $set, true);
                            })
                            ->required(),

                        TextInput::make('price')
                            ->numeric()
                            ->extraInputAttributes(['readonly' => true]),

                        TextInput::make('subtotal')
                            ->numeric()
                          ->extraInputAttributes(['readonly' => true]),

                    ])
                    ->columns(4),

                TextInput::make('discount')
                    ->numeric()
                    ->default(0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (callable $get, callable $set) {
                        self::updateGrandTotal($get, $set, false);
                    }),

                TextInput::make('tax')
                    ->numeric()
                    ->default(0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (callable $get, callable $set) {
                        self::updateGrandTotal($get, $set, false);
                    }),

          TextInput::make('subtotal')
                    ->numeric()
                    ->extraInputAttributes(['readonly' => true])
                    ->dehydrated(), 

                TextInput::make('total')
                    ->numeric()
                    ->extraInputAttributes(['readonly' => true])
                    ->dehydrated(),
            ]);
    }

    /**
     * Fungsi pembantu untuk menghitung Grand Total.
     * * @param bool $isInsideRepeater Menandakan apakah fungsi ini dipanggil dari dalam row repeater
     */
    public static function updateGrandTotal(callable $get, callable $set, bool $isInsideRepeater = false): void
    {
        // Jika dipanggil dari dalam repeater, gunakan '../../' untuk naik ke form utama
        $details = $isInsideRepeater ? $get('../../details') : $get('details');
        $details = $details ?? [];

        $subtotal = 0;

        foreach ($details as $row) {
            $price = (float) ($row['price'] ?? 0);
            $qty = (int) ($row['qty'] ?? 1);
            $subtotal += ($price * $qty);
        }

        // Ambil diskon dan pajak
        $discount = (float) ($isInsideRepeater ? $get('../../discount') : $get('discount')) ?: 0;
        $tax = (float) ($isInsideRepeater ? $get('../../tax') : $get('tax')) ?: 0;

        $total = $subtotal - $discount + $tax;

        // Tembak nilai ke field form utama
        if ($isInsideRepeater) {
            $set('../../subtotal', $subtotal);
            $set('../../total', $total);
        } else {
            $set('subtotal', $subtotal);
            $set('total', $total);
        }
    }
}