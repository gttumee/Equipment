<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegisterResource\Pages;
use App\Models\Equipment;
use App\Models\Register;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Table;
class RegisterResource extends Resource
{
    protected static ?string $model = Equipment::class;
    protected static ?string $navigationGroup = 'Тооллого';
    protected static ?string $pluralModelLabel = 'Тооллого хийх';
    protected static bool $hasTitleCaseModelLabel = false;
    protected static ?string $navigationLabel = 'Тооллого хийх';
    protected static ?string $navigationIcon = 'heroicon-m-arrow-up-on-square-stack';
    public static function getNavigationBadge(): ?string
    {
        return (string) static::$model::where('status', '1')->count();
    }

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
            ->columns([
                Split::make([
                    TextColumn::make('code')
                    ->label('Код')
                    ->searchable(),
                TextColumn::make('name')
                    ->wrap()
                    ->label('Нэр')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Төрөл')
                    ->searchable(),
                ])
                
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\Action::make('add')
                    ->label('Тоолох')
                    ->button()
                    ->color('success')
                    ->icon('heroicon-c-plus-circle')
                    ->action(function ($record) {
                        $userId = auth()->id();
                        $equipmentId = $record->id;
                        $status = '2';
                        $registerDate = now();
                        Register::create([
                            'user_id' => $userId,
                            'equipment_id' => $equipmentId,
                            'status' => $status,
                            'register_date' => $registerDate,
                        ]);
                    })
                    ->modalHeading('Хасалт хийх') 
                    ->modalDescription('Хасалт хийх төлөвийг сонгон хасалт хийнэ үү') 
                    ->modalSubmitActionLabel('Баталгаажуулах'),
                    Tables\Actions\Action::make('remove')
                    ->label('Хасах')
                    ->button()
                    ->color('danger')
                    ->icon('heroicon-s-minus-circle')
                    ->action(function ($record, array $data) {
                        $userId = auth()->id();
                        $equipmentId = $record->id;
                        $status =  $data['status'];
                        $registerDate = now();
                        $reason =  $data['reason'];
                        Register::create([
                            'user_id' => $userId,
                            'equipment_id' => $equipmentId,
                            'status' => $status,
                            'register_date' => $registerDate,
                            'reason' => $reason,

                        ]);
                    })
                    ->form([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                '3' => 'Зарсагдсан', 
                                '4' => 'Эвдэрсэн',   
                                '5' => 'Хугацаа дууссан',
                            ]) 
                            ->required(),
                            Textarea::make('reason')
                            ->label('Шалтгаан')
                    ])
                    ->modalHeading('Хасалт хийх') 
                    ->modalDescription('Хасалт хийх төлөвийг сонгон хасалт хийнэ үү') 
                    ->modalSubmitActionLabel('Баталгаажуулах')  
                 
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
            'index' => Pages\ListRegisters::route('/'),
            'create' => Pages\CreateRegister::route('/create'),
            'edit' => Pages\EditRegister::route('/{record}/edit'),
        ];
    }
}