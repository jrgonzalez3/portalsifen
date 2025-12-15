<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Filament\Notifications\Notification;
use App\Services\RucApiService;
use App\Models\RucQuery;
use Livewire\Component;

class RucConsultationWidget extends Widget
{
    protected string $view = 'filament.widgets.ruc-consultation-widget';
    
    // Configurar para que ocupe todo el ancho
    protected int | string | array $columnSpan = 'full';

    // Properties
    public $ruc_number = '';
    public $environment = 'test';

    public function consult()
    {
        $this->validate([
            'ruc_number' => 'required|numeric|digits_between:5,15',
            'environment' => 'required|in:test,prod',
        ]);

        $service = app(RucApiService::class);

        try {
            // Call API
            $responseWrapper = $service->consultRuc($this->ruc_number, $this->environment);

            // Network/HTTP Error
            if (!isset($responseWrapper['success']) || !$responseWrapper['success']) {
                $errorMsg = $responseWrapper['error'] ?? 'Error desconocido';
                Notification::make()
                    ->title('Fallo conexión API')
                    ->body("Error: {$errorMsg}")
                    ->danger()
                    ->persistent()
                    ->send();
                return;
            }

            $responseData = $responseWrapper['data'] ?? [];
            $resultadoSet = $responseData['resultadoSET'] ?? [];
            $resEnvi = $resultadoSet['ns2:rResEnviConsRUC'] ?? [];

            // XML Mal formado / Error estructura SIFEN (rRetEnviDe)
             if (empty($resEnvi)) {
                 $retDe = $resultadoSet['ns2:rRetEnviDe'] ?? null;
                 if ($retDe) {
                      $gResProc = $retDe['ns2:rProtDe']['ns2:gResProc'] ?? [];
                      $msg = $gResProc['ns2:dMsgRes'] ?? 'Error devuelto por SIFEN';
                      $cod = $gResProc['ns2:dCodRes'] ?? 'N/A';
                      
                      Notification::make()
                        ->title('Rechazo SIFEN')
                        ->body("Mensaje: {$msg} | Código: {$cod}")
                        ->danger()
                        ->persistent()
                        ->send();
                      return;
                 }
                 
                 // Generic Unexpected Structure
                 Notification::make()
                    ->title('Respuesta Inesperada')
                    ->body('La API respondió pero no se encontró la estructura esperada.')
                    ->warning()
                    ->persistent()
                    ->send();
                 return;
            }

            // Check dCodRes (0502 = Found)
            if (($resEnvi['ns2:dCodRes'] ?? '') !== '0502') {
                 $msg = $resEnvi['ns2:dMsgRes'] ?? 'RUC no encontrado o error en SET.';
                 Notification::make()
                    ->title('Aviso del SIFEN')
                    ->body("Mensaje: {$msg}")
                    ->warning()
                    ->send();
                 return;
            }

            $taxpayerData = $resEnvi['ns2:xContRUC'] ?? null;

            if ($taxpayerData) {
                // Create Record
                RucQuery::create([
                    'ruc_number' => $this->ruc_number,
                    'environment' => $this->environment,
                    'taxpayer_name' => $taxpayerData['ns2:dRazCons'] ?? null,
                    'status' => $taxpayerData['ns2:dDesEstCons'] ?? null,
                    'taxpayer_data' => $taxpayerData,
                    'user_id' => auth()->id(),
                ]);

                Notification::make()
                    ->title('Consulta Exitosa')
                    ->body("Contribuyente: " . ($taxpayerData['ns2:dRazCons'] ?? ''))
                    ->success()
                    ->send();
                
                // Clear input
                $this->reset(['ruc_number']);
                
                // Dispatch event to refresh table
                $this->dispatch('ruc-consulted');
            } else {
                 Notification::make()
                    ->title('Datos Vacíos')
                    ->body('La respuesta no contiene datos del contribuyente.')
                    ->danger()
                    ->send();
            }

        } catch (\Exception $e) {
            Notification::make()
                ->title('Excepción Crítica')
                ->body($e->getMessage())
                ->danger()
                ->persistent()
                ->send();
        }
    }
}
