<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EquipmentResource\Pages;
use App\Filament\Resources\EquipmentResource\RelationManagers;
use App\Models\Category;
use App\Models\Equipment;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Repeater;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;

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
                ->label('Үнэ')
                ->numeric()
                ->inputMode('decimal'),
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
                TextInput::make('location')
                ->label('Байршил'),
                TextInput::make('percentage')
                ->label('Тоо ширхэг')
                ->numeric()
                ->inputMode('decimal'),
                TextInput::make('owner')
                ->label('Эзэмшигч'),
                Hidden::make('user_id')
                ->default(auth()->id()),
                Repeater::make('relate')
                ->relationship('relate')
                ->label('Дагалдах хэрэгсэл')
                ->schema([
                    TextInput::make('name')
                        ->label('Нэр')
                        ->nullable(),
                    TextInput::make('serial_number')
                        ->label('Аралын дугаар')
                        ->nullable()
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Хөрөнгийн жагсаалт')
            ->paginated([5,10,20,50,100, 'all'])
            ->columns([
                TextColumn::make('category.name')
                ->label('Төрөл'),
                TextColumn::make('name')
                ->label('Нэр'),
                TextColumn::make('owner')
                ->label('Эзэмшигч'),
                TextColumn::make('price')
                ->label('Үнэ')
                ->money(),
                TextColumn::make('percentage')
                ->label('Тоо'),
                TextColumn::make('location')
                ->label('Байршил'),
                TextColumn::make('buy_date')
                ->label('Огноо'),
                TextColumn::make('buy_date')
                ->label('Худлдаж авсан огноо'),
                TextColumn::make('owner')
                ->label('Эзэмшигч'),
                BadgeColumn::make('status')
                ->label('Статус')
                ->formatStateUsing(fn ($state) => match ($state) {
                    1 => 'Идэвхтэй', 
                    2 => 'Идэвхтгүй', 
                })
                ->colors([
                    'success' => 1,
                    'primary' => 2,
                ]),
                TextColumn::make('user.name')
                ->label('Бүртгэгч'),
                TextColumn::make('created_at')
                ->label('Бүртгэcэн'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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

    public static function infolist(Infolist $infolist): Infolist
{
    return $infolist
        ->schema([
            TextEntry::make('category.name'),
            TextEntry::make('name'),
            TextEntry::make('price'),
            TextEntry::make('location'),
            TextEntry::make('relate.name'),

        ]);
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEquipment::route('/'),
            'create' => Pages\CreateEquipment::route('/create'),
            'edit' => Pages\EditEquipment::route('/{record}/edit'),
            'view' => Pages\ViewEquipment::route('/{record}'),
        ];
    }
}