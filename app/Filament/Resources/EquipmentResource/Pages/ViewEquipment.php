<?php

namespace App\Filament\Resources\EquipmentResource\Pages;

use App\Filament\Resources\EquipmentResource;
use App\Models\Equipment;
use App\Models\Register;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\Alignment;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ViewEquipment extends ViewRecord
{
    protected static string $resource = EquipmentResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
            ->label('Засах'),
            Actions\Action::make('remove')
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
        ];
    }
}