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
                ->label('QR кодоор бүртгэх')  // ボタンのラベル
                ->color('primary')
                ->icon('heroicon-m-qr-code')
                ->modalHeading('QRコードをスキャン')  // モーダルの見出し
                ->modalWidth('lg')
                ->modalContent(fn () => view('qrcode-scan-modal'))  // モーダルのコンテンツ
                // モーダルが開かれるときにイベントを発火
                ->after(function () {
                    // Livewireからイベントを発火させてカメラを初期化
                    $this->dispatchBrowserEvent('modalOpened');
                }),
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