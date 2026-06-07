<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('No. HP'),

                Tables\Columns\TextColumn::make('email'),

                Tables\Columns\TextColumn::make('point')
                    ->numeric(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ]);
    }
}