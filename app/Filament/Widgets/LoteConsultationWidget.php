<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Notifications\Notification;
use App\Services\LoteApiService;
use App\Models\LoteBatch;
use Filament\Schemas\Schema;

class LoteConsultationWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.widgets.lote-consultation-widget';
    protected int | string | array $columnSpan = 'full';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'environment' => 'test',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('batch_number')
                    ->label('Consultar Lote')
                    ->placeholder('Ingrese el Número de Lote')
                    ->prefixIcon('heroicon-m-archive-box')
                    ->required()
                    ->regex('/^[0-9]+$/')
                    ->columnSpan([
                        'md' => 5,
                    ]),

                Radio::make('environment')
                    ->label('Entorno')
                    ->options([
                        'test' => 'Testing',
                        'prod' => 'Producción',
                    ])
                    ->default('test')
                    ->inline()
                    ->required()
                    ->columnSpan([
                        'md' => 4,
                    ]),

                Actions::make([
                    Action::make('consultar')
                        ->label('Consultar')
                        ->icon('heroicon-m-magnifying-glass')
                        ->action('consult')
                        ->color('primary')
                        ->size('lg')
                ])
                ->columnSpan([
                    'md' => 3,
                ])
                ->verticalAlignment(VerticalAlignment::End),
            ])
            ->columns(12)
            ->statePath('data');
    }

    public function consult()
    {
        $data = $this->form->getState();
        $batch_number = $data['batch_number'];
        $environment = $data['environment'];

        $service = app(LoteApiService::class);

        try {
            $service->setEnvironment($environment);
            
            $responseWrapper = $service->consultLote($batch_number);

            if (!isset($responseWrapper['success']) || !$responseWrapper['success']) {
                $errorMsg = $responseWrapper['error'] ?? 'Error desconocido';
                Notification::make()->title('Fallo conexión API')->body($errorMsg)->danger()->send();
                return;
            }

            $responseData = $responseWrapper['data'] ?? [];
            $resultadoSet = $responseData['resultadoSET'] ?? [];
            
            // Try predictable keys for Lote: rResEnviConsLoteDe
            $resEnvi = $resultadoSet['ns2:rResEnviConsLoteDe'] ?? [];

            if (empty($resEnvi)) {
                 $retDe = $resultadoSet['ns2:rRetEnviDe'] ?? null;
                 if ($retDe) {
                      $msg = $retDe['ns2:rProtDe']['ns2:gResProc']['ns2:dMsgRes'] ?? 'Error SIFEN';
                      Notification::make()->title('Rechazo SIFEN')->body($msg)->danger()->send();
                      return;
                 }
                 
                 // DEBUG INFO
                 $keys = implode(', ', array_keys($resultadoSet));
                 $debugJson = substr(json_encode($resultadoSet), 0, 200);
                 
                 Notification::make()
                    ->title('Respuesta Inesperada')
                    ->body("Keys encontradas: [{$keys}]. JSON Start: {$debugJson}")
                    ->warning()
                    ->persistent()
                    ->send();
                 return;
            }

            // Extract status/message
            $codRes = $resEnvi['ns2:dCodResLot'] ?? '';
            $msgRes = $resEnvi['ns2:dMsgResLot'] ?? '';

            // Normalize Status
            $status = $msgRes;
            if ($codRes === '0360') { 
                $status = 'Inexistente';
            } elseif ($codRes === '0364') {
                $status = 'Extemporáneo';
            }
            
            // Save record
            LoteBatch::create([
                'batch_number' => $batch_number,
                'environment' => $environment,
                'status' => substr($status, 0, 255),
                'response_data' => $responseData, 
                'user_id' => auth()->id(),
            ]);
            
            // Clear input
            $this->data['batch_number'] = '';
            $this->form->fill(['batch_number' => '', 'environment' => $environment]);
            
            $this->dispatch('lote-consulted');

            // Generic logic for notification
             Notification::make()
                ->title('Consulta Realizada')
                ->body("Respuesta: {$msgRes}")
                ->success() // Or warning depending on code? Assuming success interaction
                ->send();

        } catch (\Exception $e) {
            Notification::make()->title('Error')->body($e->getMessage())->danger()->send();
        }
    }
}
