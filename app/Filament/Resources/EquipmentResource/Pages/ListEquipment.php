<?php

namespace App\Filament\Resources\EquipmentResource\Pages;

use App\Filament\Resources\EquipmentResource;
use Carbon\Carbon;
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
            ->label('Бүгд')
            ->modifyQueryUsing(function (Builder $query) {
                $query->where('status', 'active'); 
            }),

        'new_equipment' => Tab::make()
            ->label('Шинээр бүртгэгдсэн')
            ->modifyQueryUsing(function (Builder $query) {
                $startOfMonth = Carbon::now()->startOfMonth(); 
                $endOfMonth = Carbon::now()->endOfMonth();  
                $query->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                      ->where('status', 'active');
         }),
         
        'not_active' => Tab::make()
            ->label('Хасалт хийгдсэн') 
            ->modifyQueryUsing(function (Builder $query) {
                $query->where('status', '<>','active'); 
         }),

         'expired' => Tab::make()
         ->label('Хугацаа дуусах дөхсөн') 
         ->modifyQueryUsing(function (Builder $query) {
            $oneMonthAgo = Carbon::now()->subMonth();
            $query->where('end_date','>', $oneMonthAgo)
            ->where('status','active');
        }),
    ];
}
}