<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiSewaResource\Pages;
use App\Filament\Resources\TransaksiSewaResource\RelationManagers;
use App\Models\TransaksiSewa;
use App\Models\ItemsOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\ViewField;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;

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
                        'pelunasan diperiksa' => 'pelunasan diperiksa', // Add this new status
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
            ->recordUrl(null)
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
                    ->label('Metode Bayar'),
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
            ->defaultSort('updated_at', 'desc')  // Add this line
            ->headerActions([
                Tables\Actions\Action::make('refresh')
                    ->label('Refresh')
                    ->icon('heroicon-o-arrow-path')
                    ->action(fn () => null)
                    ->color('gray'),
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
                    ->modalHeading(fn (TransaksiSewa $record) => 
                        in_array($record->status, ['pelunasan diperiksa', 'selesai']) ? 'Bukti DP & Pelunasan' : 'Bukti Pembayaran'
                    )
                    ->modalContent(fn (TransaksiSewa $record) => view(
                        'filament.resources.transaksi-sewa.payment-proof',
                        [
                            'record' => $record,
                            'showPelunasan' => in_array($record->status, ['pelunasan diperiksa', 'selesai'])
                        ]
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->visible(fn (TransaksiSewa $record) => $record->paymentProof || $record->image_path_lunas)
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

                Action::make('approve_pelunasan')
                    ->label('Selesai')
                    ->icon('heroicon-o-check')
                    ->action(fn (TransaksiSewa $record) => $record->update(['status' => 'selesai']))
                    ->visible(fn (TransaksiSewa $record) => $record->status === 'pelunasan diperiksa')
                    ->requiresConfirmation()
                    ->color('success'),
                    
                Action::make('reject_pelunasan')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-mark')
                    ->action(fn (TransaksiSewa $record) => $record->update(['status' => 'pelunasan']))
                    ->visible(fn (TransaksiSewa $record) => $record->status === 'pelunasan diperiksa')
                    ->requiresConfirmation()
                    ->color('danger'),

                Action::make('inspect')
                    ->label('Periksa')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->form(function (TransaksiSewa $record) {
                        $formFields = [];
                        foreach ($record->itemsOrder as $item) {
                            $formFields[] = Forms\Components\Select::make("data.{$item->id}.kondisi")
                                ->label($item->barang->name . " ({$item->quantity} unit)")
                                ->options([
                                    'normal' => 'Normal',
                                    'rusak ringan' => 'Rusak Ringan',
                                    'rusak berat' => 'Rusak Berat',
                                    'hilang' => 'Hilang',
                                ])
                                ->required();
                        }
                        return $formFields;
                    })
                    ->action(function (array $data, TransaksiSewa $record): void {
                        foreach ($data as $itemId => $values) {
                            if (isset($values['kondisi'])) {
                                ItemsOrder::where('id', $itemId)
                                    ->where('transaksi_sewa_id', $record->id)
                                    ->update([
                                        'kondisi' => $values['kondisi']
                                    ]);
                            }
                        }
                        $record->update(['status' => 'pelunasan']);
                    })
                    ->visible(fn (TransaksiSewa $record) => $record->status === 'diperiksa')
                    ->modalWidth('md')
                    ->color('warning'),
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

    public static function canCreate(): bool
   {
      return false;
   }

   
}
