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
use App\Services\RucApiService;
use App\Models\RucQuery;
use Filament\Schemas\Schema;

class RucConsultationWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.widgets.ruc-consultation-widget';
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
                TextInput::make('ruc_number')
                    ->label('Consultar RUC')
                    ->placeholder('Ingrese RUC sin DV')
                    ->prefixIcon('heroicon-m-identification')
                    ->required()
                    ->numeric()
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
                ->verticalAlignment(VerticalAlignment::End), // Alineación abajo
            ])
            ->columns(12)
            ->statePath('data');
    }

    public function consult()
    {
        $data = $this->form->getState();
        $ruc_number = $data['ruc_number'];
        $environment = $data['environment'];

        $service = app(RucApiService::class);

        try {
            $responseWrapper = $service->consultRuc($ruc_number, $environment);

            if (!isset($responseWrapper['success']) || !$responseWrapper['success']) {
                $errorMsg = $responseWrapper['error'] ?? 'Error desconocido';
                Notification::make()->title('Fallo conexión API')->body($errorMsg)->danger()->send();
                return;
            }

            $responseData = $responseWrapper['data'] ?? [];
            $resultadoSet = $responseData['resultadoSET'] ?? [];
            $resEnvi = $resultadoSet['ns2:rResEnviConsRUC'] ?? [];

            if (empty($resEnvi)) {
                 $retDe = $resultadoSet['ns2:rRetEnviDe'] ?? null;
                 if ($retDe) {
                      $msg = $retDe['ns2:rProtDe']['ns2:gResProc']['ns2:dMsgRes'] ?? 'Error SIFEN';
                      Notification::make()->title('Rechazo SIFEN')->body($msg)->danger()->send();
                      return;
                 }
                 Notification::make()->title('Respuesta Inesperada')->body('Estructura desconocida')->warning()->send();
                 return;
            }

            if (($resEnvi['ns2:dCodRes'] ?? '') !== '0502') {
                 Notification::make()->title('Aviso SIFEN')->body($resEnvi['ns2:dMsgRes'] ?? 'RUC no encontrado')->warning()->send();
                 return;
            }

            $taxpayerData = $resEnvi['ns2:xContRUC'] ?? null;

            if ($taxpayerData) {
                RucQuery::create([
                    'ruc_number' => $ruc_number,
                    'environment' => $environment,
                    'taxpayer_name' => $taxpayerData['ns2:dRazCons'] ?? null,
                    'status' => $taxpayerData['ns2:dDesEstCons'] ?? null,
                    'taxpayer_data' => $taxpayerData,
                    'user_id' => auth()->id(),
                ]);

                Notification::make()->title('Consulta Exitosa')->body($taxpayerData['ns2:dRazCons'] ?? '')->success()->send();
                
                $this->form->fill(['environment' => $environment]); // Reset RUC but keep env
                $this->dispatch('ruc-consulted');
            }

        } catch (\Exception $e) {
            Notification::make()->title('Error')->body($e->getMessage())->danger()->send();
        }
    }
}
