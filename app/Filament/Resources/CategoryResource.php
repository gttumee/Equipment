<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationGroup = 'Бүртгэл';
    protected static ?string $pluralModelLabel = 'Бүртэлийн төрөл нэмэх';
    protected static bool $hasTitleCaseModelLabel = false;
    protected static ?string $navigationLabel = 'Бүртэлийн төрөл нэмэх';
    protected static ?string $navigationIcon = 'heroicon-s-bars-arrow-up';
    public static function getNavigationBadge(): ?string
    {
        return (string) static::$model::where('status', '1')->count();
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                ->label('Төрөлийн нэр'),
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
                TextColumn::make('name')
                ->label('Нэр'),
                BadgeColumn::make('status')
                ->label('Статус')
                ->formatStateUsing(fn ($state) => match ($state) {
                    1 => 'Идэвхтэй', 
                    2 => 'Идэвхтгүй', 
                })
                ->colors([
                    'success' => 1,
                    'primary' => 2,
                ])

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()
                ->label('Бүгдийг устгах'),
                ]) ->label('Бүгдийг устгах'),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}