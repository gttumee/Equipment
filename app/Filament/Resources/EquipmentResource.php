<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EquipmentResource\Pages;
use App\Models\Category;
use App\Models\Equipment;
use Dompdf\Css\Color;
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
use Filament\Forms\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\Alignment;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Filament\Infolists\Components\Section as infosection;

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
                Section::make('Бүртгэх')
                ->description('Хөрөнгийн үндсэн мэдээлэл бүртгэх')
                ->schema([
            Select::make('category_id')
                ->label('Төрлийн нэр')
                ->options(Category::all()->pluck('name', 'id')) 
                ->searchable()
                ->required(), 
            TextInput::make('name')
                ->label('Хөрөнгийн нэр'),
            TextInput::make('percentage')
                ->label('Тоо ширхэг')
                ->numeric()
                ->inputMode('decimal'),
            TextInput::make('price')
                ->label('Үнэ')
                ->numeric()
                ->inputMode('decimal'),
            TextInput::make('location')
                ->label('Байршил'),
            TextInput::make('owner')
                ->label('Эзэмшигч'),
            DatePicker::make('buy_date')
                ->label('Худалдаж авсан огноо'),
            Hidden::make('user_id')
                ->default(auth()->id())
                ])
                
                ->columns(2),
            Section::make('Нэмэлт бүртгэл')
                ->description('Дагалдах хэрэгсэлийн бүртгэл')
                ->schema([
                    Repeater::make('relate')
                        ->relationship('relate')
                        ->label('Дагалдах хэрэгсэл нэмэх')
                        ->schema([
                            TextInput::make('name')
                                ->label('Нэр')
                                ->nullable(),
                            TextInput::make('pieces')
                                ->label('Тоо ширхэг')
                                ->nullable()
                                ->default('1')
                        ])->columns(2)
                        ->defaultItems(0)
                        ->addAction(fn (Forms\Components\Actions\Action $action) => $action
                        ->icon('heroicon-c-plus-circle')
                        ->label('Нэмэлтээр оруулах'))
                        ]),
        ]);
          
    }

    public static function table(Table $table): Table
    {

        return $table
            ->heading('Хөрөнгийн жагсаалт')
            ->paginated([5, 10, 20, 50, 100, 'all'])
            ->columns([
                TextColumn::make('category.name')
                    ->label('Төрөл')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Нэр')
                    ->wrap()
                    ->sortable(),
                TextColumn::make('owner')
                    ->label('Эзэмшигч')
                    ->sortable(),
                TextColumn::make('buy_date')
                    ->label('Огноо')
                    ->sortable(),
                BadgeColumn::make('status')
                    ->label('Статус')
                    ->sortable()
                    ->formatStateUsing(function ($state, $record) {
                    $register = $record->registers()->latest()->first();
                    if ($register) {
                    return match ($register->status) {
                         1 => 'Бүртгэгдсэн',  
                         2 => 'Тоологдсон',
                         3 => 'Зарсагдсан', 
                         4 => 'Эвдэрсэн',
                         5 => 'Хугацаа дууссан', 
                    default => 'Бүртгэгдсэн',
            };
        }
                return 'Бүртгэгдсэн';
    })
                     ->colors([
                      'success' => 1,
                       'primary' => 2,
                       'info' => 3, 
                       'danger' => 4,
                       'gray' => 5,
    ])
    ->color(function ($state, $record) {
        $register = $record->registers()->latest()->first();
        if ($register) {
            return match ($register->status) {
                1 => 'success',
                2 => 'primary', 
                3 => 'info',
                4 => 'danger',
                5 => 'gray',
                default => 'success',
            };
        }
        return 'success';
    }),
                TextColumn::make('user.name')
                    ->label('Бүртгэгч')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Бүртгэcэн')
                    ->sortable(),
                ])
        
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Qrcode')
                ->label('QR код')
                ->modalContent(function (Equipment $record){
                    $url = route('equipment.show', $record->id); 
                     $qrCode = QrCode::size(300)->generate($url); 
                     return view('qrcode',['qrCode' => $qrCode,'code' => $record->code,]);
                    } )
                    ->modalAlignment(Alignment::Center)
                    ->icon('heroicon-m-qr-code')
                    ->modalWidth('md')
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
                infosection::make()
                ->heading('Үндсэн мэдээлэл')
                ->schema([
                    Grid::make(4)
                    ->schema([  
                    TextEntry::make('category.name')
                    ->label('Төрөл'),
                    TextEntry::make('name')
                    ->label('Нэр'),
                    TextEntry::make('price')
                    ->label('Анхны үнэ'),
                    TextEntry::make('location')
                    ->label('Байршил'),
                    ])
                  
                ]),
    
                infosection::make()
                ->heading('Дагалдах хэрэгсэлийн мэдээлэл')
                ->schema([
                    Grid::make(2)
                    ->schema([ 
                        TextEntry::make('relate.name')
                        ->label('Дагалдах хэрэгсэлийн нэр'),
                        TextEntry::make('relate.serial_number')
                        ->label('Тоо хэмжээ'),
                        ])
                ]),
    
                infosection::make()
                ->heading('Хасалт шалтгаан')
                ->schema([
                    Grid::make(2)
                    ->schema([ 
                        TextEntry::make('registers.reason')
                        ->label('Хасалт хийсэн шалтгаан'),
                        TextEntry::make('user.name')
                        ->badge()
                        ->label('Хасалт хийсэн ажилтан'),
                        TextEntry::make('registers.created_at')
                        ->label('Тоо хэмжээ'),
                        ])
                ])
    
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