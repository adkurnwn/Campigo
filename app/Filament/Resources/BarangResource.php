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
use Faker\Provider\ar_EG\Text;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Filters\SelectFilter;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('kode')->required()->unique(ignorable: fn($record) => $record),
                        TextInput::make('nama')->required(),
                        TextInput::make('merk'),
                        TextInput::make('stok')->numeric(),
                        TextInput::make('kapasitas')->numeric(),
                        TextInput::make('harga')->numeric()->prefix('Rp ')->required(),
                        TextInput::make('berat')->numeric()->suffix('gram'),
                        TextInput::make('stok')->numeric()->required(),
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
                            ->label('Upload Gambar'),
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
                TextColumn::make('berat')->sortable()->label('Berat (g)'),
                TextColumn::make('stok')->sortable(),
                SpatieMediaLibraryImageColumn::make('image')
                    ->label('Gambar'),
                //TextColumn::make('updated_at')->sortable()->label('Terakhir Update'),
                //TextColumn::make('merk')->searchable()->visible(true),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                SelectFilter::make('kategori')
                    ->options([
                        'Tenda' => 'Tenda',
                        'Bag' => 'Bag',
                        'Perlengkapan Tidur' => 'Perlengkapan Tidur',
                        'Lampu' => 'Lampu',
                        'Alat Masak dan Makan' => 'Alat Masak dan Makan',
                        'Wears' => 'Wears',
                        'Lainnya' => 'Lainnya',
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth('max-w-4xl'),
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
        ];
    }
}
