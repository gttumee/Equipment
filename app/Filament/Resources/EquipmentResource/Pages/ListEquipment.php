<?php

namespace App\Filament\Resources\EquipmentResource\Pages;

use App\Filament\Resources\EquipmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListEquipment extends ListRecords
{
    protected static string $resource = EquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Шинээр бүртгэх'),
        ];
    }
    
    public function getTabs(): array
{
    return [
        'all' => Tab::make()
            ->label('Бүгд'),

        'status' => Tab::make()
            ->label('Шинээр бүртгэгдсэн')
            ->modifyQueryUsing(function (Builder $query) {
                 $query->whereDoesntHave('registers');
         }),
         
        'count' => Tab::make()
            ->label('Тоологдсон') 
             ->modifyQueryUsing(function (Builder $query) {
                    $query->whereHas('registers', function ($query) {
                    $query->where('status', 2);
             });
         }),

         'remove' => Tab::make()
         ->label('Хасалт хийгдсэн') 
          ->modifyQueryUsing(function (Builder $query) {
                 $query->whereHas('registers', function ($query) {
                 $query->where('status', 2);
          });
      }),
    ];
}
}