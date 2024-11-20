<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiSewaResource\Pages;
use App\Filament\Resources\TransaksiSewaResource\RelationManagers;
use App\Models\TransaksiSewa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\ViewField;
use Filament\Tables\Actions\Action;

class TransaksiSewaResource extends Resource
{
    protected static ?string $model = TransaksiSewa::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Transaksi Sewa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        'pending' => 'pending',
                        'pembayaran terkonfirmasi' => 'pembayaran terkonfirmasi',
                        'selesai' => 'selesai',
                        'dibatalkan' => 'dibatalkan',
                        'berlangsung' => 'berlangsung',
                        'belum bayar' => 'belum bayar',
                    ]),
                Forms\Components\Section::make('Bukti Pembayaran')
                    ->schema([
                        Forms\Components\ViewField::make('payment_proof')
                            ->view('filament.resources.transaksi-sewa.payment-proof')
                    ])
                    ->visible(fn ($record) => $record && $record->paymentProof)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable()
                    ->label('Customer'),
                Tables\Columns\TextColumn::make('tgl_pinjam')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tgl_kembali')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_harga')
                    ->money('idr')
                    ->sortable(),
                Tables\Columns\TextColumn::make('metode_bayar')
                    ->badge()
                    ->label('Payment Method'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'dibatalkan',
                        'warning' => 'pending',
                        'warning' => 'belum bayar',
                        'success' => 'selesai',
                        'info' => 'pembayaran terkonfirmasi',
                        'info' => 'berlangsung',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'pending',
                        'pembayaran terkonfirmasi' => 'pembayaran terkonfirmasi',
                        'selesai' => 'selesai',
                        'dibatalkan' => 'dibatalkan',
                        'berlangsung' => 'berlangsung',
                        'belum bayar' => 'belum bayar',

                    ]),
            ])
            ->actions([
                Action::make('view_payment')
                    ->label('Bukti Pembayaran')
                    ->icon('heroicon-o-photo')
                    ->modalHeading('Bukti Pembayaran')
                    ->modalContent(fn (TransaksiSewa $record) => view(
                        'filament.resources.transaksi-sewa.payment-proof',
                        ['record' => $record]
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->visible(fn (TransaksiSewa $record) => $record->paymentProof)
                    ->color('success'),
                    
                Action::make('approve_payment')
                    ->label('Terima')
                    ->icon('heroicon-o-check')
                    ->action(fn (TransaksiSewa $record) => $record->update(['status' => 'pembayaran terkonfirmasi']))
                    ->visible(fn (TransaksiSewa $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->color('success'),
                    
                Action::make('reject_payment')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-mark')
                    ->action(fn (TransaksiSewa $record) => $record->update(['status' => 'dibatalkan']))
                    ->visible(fn (TransaksiSewa $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->color('danger'),
                    
                Action::make('view_ktp')
                    ->label('Lihat KTP')
                    ->icon('heroicon-o-identification')
                    ->modalHeading('Jaminan KTP')
                    ->modalContent(fn (TransaksiSewa $record) => view(
                        'filament.resources.transaksi-sewa.ktp-image',
                        ['record' => $record]
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->visible(fn (TransaksiSewa $record) => $record->status === 'berlangsung' && $record->jaminanKtp)
                    ->color('info'),
            ])
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
            'index' => Pages\ListTransaksiSewas::route('/'),
            'create' => Pages\CreateTransaksiSewa::route('/create'),
            'edit' => Pages\EditTransaksiSewa::route('/{record}/edit'),
        ];
    }
}
