<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use Filament\Tables\Filters\SelectFilter;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                TextColumn::make('index')
                    ->rowIndex()
                    ->label('No'),
                TextColumn::make('name')->sortable()->searchable()->label('Nama'),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('role')->sortable()->searchable(),
                TextColumn::make('statususer')->label('Status User')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'nonactive' => 'warning',
                        'banned' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('statususer')
                    ->label('Status User')
                    ->options([
                        'active' => 'Active',
                        'nonactive' => 'Non Active',
                        'banned' => 'Banned',
                    ])
            ])
            ->actions([
                Action::make('toggleStatus')
                    ->label(fn(User $record): string => $record->statususer === 'active' ? 'Blokir' : 'Aktifkan')
                    ->color(fn(User $record): string => $record->statususer === 'active' ? 'danger' : 'success')
                    ->icon(fn(User $record): string => $record->statususer === 'active' ? 'heroicon-m-no-symbol' : 'heroicon-m-check')
                    ->requiresConfirmation()
                    ->modalDescription(
                        fn(User $record): string =>
                        $record->statususer === 'active'
                            ? 'Apakah anda yakin untuk memblokir user?'
                            : 'Apakah anda yakin untuk mengaktifkan user?'
                    )
                    ->action(function (User $record): void {
                        $record->update([
                            'statususer' => $record->statususer === 'active' ? 'banned' : 'active'
                        ]);
                    }),
            ])
            ->actionsColumnLabel('Aksi')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
