<?php

namespace App\Filament\Resources\EquipmentResource\Pages;

use App\Filament\Resources\EquipmentResource;
use App\Models\Equipment;
use App\Models\Reason;
use Filament\Actions;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;

class ViewEquipment extends ViewRecord
{
    protected static string $resource = EquipmentResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
            ->label('Засах'),
            Actions\Action::make('remove')
            ->label('Хасалт хийх')
            ->color('danger')
            ->form([
        Select::make('status')
            ->label('Хасалт хийх')
            ->options(
                collect(config('status'))
                    ->filter(fn($value, $key) => $key !== 'active')
                    ->toArray()
            ),
        Textarea::make('reason')
        ->label('Шалтгаан')
        ->required(),
        Hidden::make('user_id')
            ->default(fn() => auth()->id())
            ->required()
    ])
    ->action(function (array $data, Equipment $record, Reason $reason): void {
        $record->status = $data['status'];
        $record->save(); 
        $reason->reason = $data['reason'];
        $reason->user_id = $data['user_id'];
        $reason->equipment_id = $record->id;
        $reason->save(); 
    })
        ];
    }
}