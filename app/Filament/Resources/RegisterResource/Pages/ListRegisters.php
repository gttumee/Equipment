<?php

namespace App\Filament\Resources\RegisterResource\Pages;

use App\Filament\Resources\RegisterResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Pages\Actions\Modal\Actions\ButtonAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListRegisters extends ListRecords
{
    protected static string $resource = RegisterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('edit')
                ->label('QR кодоор бүртгэх')
                ->color('primary')
                ->icon('heroicon-m-qr-code')
                ->modalHeading('QRコードをスキャン')
                ->modalWidth('lg')
                ->modalContent(fn () => view('qrcode-scan-modal')),  // 修正
            ];
    }

    public function getTabs(): array
    {
        return [
            'counted' => Tab::make()
                ->label('Тоологдсон'),
    
            'not_counted' => Tab::make()
                ->label('Хасалт хийгдсэн')
                ->modifyQueryUsing(function (Builder $query) {
                     $query->whereDoesntHave('registers');
             }),
        ];
    }
    
}