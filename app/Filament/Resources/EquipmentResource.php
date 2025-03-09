<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EquipmentResource\Pages;
use App\Models\Category;
use App\Models\Equipment;
use App\Models\Reason;
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
use Filament\Support\Enums\FontFamily;
use Illuminate\Container\Attributes\DB;

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
        return (string) static::$model::where('status', 'active')->count();
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
                    ->options(Category::all()->mapWithKeys(function ($category) {
                        return [$category->id => $category->name];
                    }))
                    ->relationship(name: 'category', titleAttribute: 'name') 
                    ->createOptionForm([
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
                    ])
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
            DatePicker::make('end_date')
                ->label('Дуусах хугацаа'),
            Hidden::make('status')
            ->default('active'),
            Hidden::make('user_id')
                ->default(auth()->id())
                ])
                ->columns(2),
            Section::make('Нэмэлт бүртгэл')
                ->description('Дагалдах хэрэгсэл бүртгэл')
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
                                ->default('1'),
                            TextInput::make('price')
                                ->label('Үнэ')
                                ->nullable()
                        ])->columns(3)
                        ->defaultItems(0)
                        ->addAction(fn (Forms\Components\Actions\Action $action) => $action
                        ->icon('heroicon-c-plus-circle')
                        ->label('Нэмэх'))
                        ]),
        ]);
          
    }

    public static function table(Table $table): Table
    {

        return $table
            ->heading('Хөрөнгийн жагсаалт')
            ->paginated([5, 10, 20, 50, 100, 'all'])
            ->columns([
                TextColumn::make('code')
                ->label('Код')
                ->sortable(),
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
                    Tables\Columns\BadgeColumn::make('status')
                    ->label('Төлөв')
                    ->sortable()
                    ->getStateUsing(fn($record) => config('status')[$record->status] ?? $record->status),
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
                    TextEntry::make('code')
                    ->label('Код')
                    ->fontFamily(FontFamily::Mono),
                    TextEntry::make('status')
                    ->label('Төлөв')
                    ->badge()
                    ->getStateUsing(fn($record) => config('status')[$record->status] ?? $record->status),
                    TextEntry::make('category.name')
                    ->label('Төрөл'),
                    TextEntry::make('name')
                    ->label('Нэр'),
                    TextEntry::make('price')
                    ->label('Анхны үнэ'),
                    TextEntry::make('location')
                    ->label('Байршил'),
                    TextEntry::make('owner')
                    ->label('Эзэмшигч'),
                    TextEntry::make('percentage')
                    ->label('Тоо ширхэг'),
                    TextEntry::make('buy_date')
                    ->label('Худалдаж авсан огноо'),
                    TextEntry::make('end_date')
                    ->label('Дуусах он огноо'),
                    ])
                  
                ]),
    
                infosection::make()
                ->heading('Дагалдах хэрэгсэлийн мэдээлэл')
                ->schema([
                    Grid::make(5)
                    ->schema([ 
                        TextEntry::make('relate.name')
                        ->listWithLineBreaks()
                        ->label('Дагалдах хэрэгсэлийн нэр'),
                        TextEntry::make('relate.pieces')
                        ->listWithLineBreaks()
                        ->label('Тоо хэмжээ'),
                        TextEntry::make('relate.price')
                        ->listWithLineBreaks()
                        ->numeric()
                        ->label('Үнэ'),
                        TextEntry::make('relate.created_at')
                        ->listWithLineBreaks()
                        ->numeric()
                        ->label('Хуалдаж авсан огноо'),
                        ])
                ]),
    
                infosection::make()
                ->visible(function ($record) {
                    if ($record->reason()->exists()) {
                        return true;
                    } else {
                        return false;
                    }
                })
                ->heading('Хасалт шалтгаан')
                ->schema([
                    Grid::make(2)
                    ->schema([ 
                        TextEntry::make('reason.reason')
                        ->listWithLineBreaks()
                        ->label('Хасалт хийсэн шалтгаан'),
                        TextEntry::make('user.name')
                        ->listWithLineBreaks()
                        ->badge()
                        ->label('Хасалт хийсэн ажилтан'),
                        TextEntry::make('created_at')
                        ->listWithLineBreaks()

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