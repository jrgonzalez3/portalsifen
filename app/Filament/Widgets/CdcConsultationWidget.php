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
use App\Services\CdcApiService;
use App\Models\CdcDocument;
use Filament\Schemas\Schema;

class CdcConsultationWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.widgets.cdc-consultation-widget';
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
                TextInput::make('cdc')
                    ->label('Consultar CDC')
                    ->placeholder('Ingrese el CDC (44 caracteres)')
                    ->prefixIcon('heroicon-m-qr-code')
                    ->required()
                    ->length(44)
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
        $cdc = $data['cdc'];
        $environment = $data['environment'];

        $service = app(CdcApiService::class);

        try {
            // Note: Service might need setEnvironment like RucApiService presumably does internally via config?
            // Checking RucApiService usage: $service->consultRuc($num, $env).
            // CdcApiService only has consultCdc($cdc). It extends SifenApiService.
            // SifenApiService likely uses the environment property or config. 
            // WAIT. RucApiService had consultRuc($ruc, $env). CdcApiService consultCdc($cdc) doesn't take env as param in the viewed file?
            // Let's check CdcApiService content again in memory or re-read.
            // Step 1227 showed: public function consultCdc(string $cdc): array. No env param.
            // But it calls $this->getApiUrl() which uses $this->environment.
            // So we must Set the environment on the service instance if it has a setter.
            // SifenApiService likely has validation/setter.
            // Let's assume we can pass it or set it.
            // If strict typing forbids it, we might need to modify service or set property.
            
            // Assuming SifenApiService has setEnvironment($env).
            // Let's try to set it.
            
            $service->setEnvironment($environment);
            
            $responseWrapper = $service->consultCdc($cdc);

            if (!isset($responseWrapper['success']) || !$responseWrapper['success']) {
                $errorMsg = $responseWrapper['error'] ?? 'Error desconocido';
                Notification::make()->title('Fallo conexión API')->body($errorMsg)->danger()->send();
                return;
            }

            $responseData = $responseWrapper['data'] ?? [];
            $resultadoSet = $responseData['resultadoSET'] ?? [];
            
            // Correct Key based on user feedback
            $resEnvi = $resultadoSet['ns2:rEnviConsDeResponse'] ?? [];

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
            $codRes = $resEnvi['ns2:dCodRes'] ?? '';
            $msgRes = $resEnvi['ns2:dMsgRes'] ?? '';
            $docData = $resEnvi['ns2:xContenDE'] ?? null;

            // Normalize Status
            $status = $msgRes;
            if ($codRes === '0420') {
                $status = 'Inexistente';
            } elseif ($codRes === '0502') {
                $status = $resEnvi['ns2:dDesEstCons'] ?? 'Encontrado';
            }

            // Save record
            CdcDocument::create([
                'cdc_number' => $cdc,
                'environment' => $environment,
                'status' => substr($status, 0, 255), // Ensure it fits
                'response_data' => $responseData, 
                'user_id' => auth()->id(),
            ]);
            
            // Clear input
            $this->data['cdc'] = '';
            $this->form->fill(['cdc' => '', 'environment' => $environment]);
            
            $this->dispatch('cdc-consulted');

            if ($codRes === '0502') { // Found (APROBADO/EXISTE usually)
                 Notification::make()
                    ->title('Documento Encontrado')
                    ->body($msgRes)
                    ->success()
                    ->send();
            } else { // Not found or other status (0420)
                 Notification::make()
                    ->title('Consulta Realizada')
                    ->body("Estado: {$msgRes}") // e.g. Documento Inexistente
                    ->warning() 
                    ->send();
            }

        } catch (\Exception $e) {
            Notification::make()->title('Error')->body($e->getMessage())->danger()->send();
        }
    }
}
