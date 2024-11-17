<?php

namespace App\Filament\Resources;

use stdClass;
use Filament\Forms;
use Filament\Tables;
use App\Models\Barang;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\BarangResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use App\Filament\Resources\BarangResource\RelationManagers;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('kode')->required()->unique(ignorable: fn($record) => $record),
                        TextInput::make('nama')->required(),
                        TextInput::make('merk'),
                        TextInput::make('harga')->numeric()->prefix('Rp ')->required(),
                        //TextInput::make('berat')->numeric()->suffix('gram')->required(),
                        //TextInput::make('stok')->numeric()->required(),
                        Select::make('kategori')
                            ->options([
                                'Tenda' => 'Tenda',
                                'Bag' => 'Bag',
                                'Perlengkapan Tidur' => 'Perlengkapan Tidur',
                                'Lampu' => 'Lampu',
                                'Alat Masak dan Makan' => 'Alat Masak dan Makan',
                                'Wears' => 'Wears',
                                'Lainnya' => 'Lainnya',
                            ])->required(),
                        SpatieMediaLibraryFileUpload::make('image') // The name of the field in the database
                            ->label('Upload Image'),
                        RichEditor::make('deskripsi'),
                        
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->rowIndex()
                    ->label('No'),
                //TextColumn::make('id')->sortable(),
                TextColumn::make('kode')->sortable()->searchable(),
                TextColumn::make('nama')->sortable()->searchable(),
                TextColumn::make('merk')->sortable()->searchable(),
                TextColumn::make('harga')
                    ->label('Harga/24 Jam')
                    ->prefix('Rp ')
                    ->sortable()
                    ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.')),
                SpatieMediaLibraryImageColumn::make('image')
                    ->label('Gambar'),
                //TextColumn::make('updated_at')->sortable()->label('Terakhir Update'),
                //TextColumn::make('merk')->searchable()->visible(true),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
        ];
    }
}
