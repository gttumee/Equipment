<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EquipmentResource\Pages;
use App\Filament\Resources\EquipmentResource\RelationManagers;
use App\Models\Category;
use App\Models\Equipment;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EquipmentResource extends Resource
{
    protected static ?string $model = Equipment::class;
    protected static ?string $navigationGroup = 'Бүртгэл';
    protected static ?string $pluralModelLabel = 'Үндсэн хөрөнгө бүртгэх';
    protected static bool $hasTitleCaseModelLabel = false;
    protected static ?string $navigationLabel = 'Үндсэн хөрөнгө бүртгэх';
    protected static ?string $navigationIcon = 'heroicon-m-squares-plus';
    
    public static function getNavigationBadge(): ?string
    {
        return (string) static::$model::where('status', '1')->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('category_id')
                ->label('Төрлийн нэр')
                ->options(Category::all()->pluck('name', 'id')) 
                ->searchable()
                ->required(), 
                TextInput::make('name')
                ->label('Хөрөнгө нэр'),
                TextInput::make('price')
                ->label('Үнэ'),
                DatePicker::make('buy_date')
                ->label('Худалдаж авсан огноо'),
                Select::make('status')
                ->label('Статус')
                ->options([
                    1 => 'Идэхтэй',
                    2 => 'Идэвхгүй',
                ])
                ->default(1)
                ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListEquipment::route('/'),
            'create' => Pages\CreateEquipment::route('/create'),
            'edit' => Pages\EditEquipment::route('/{record}/edit'),
        ];
    }
}