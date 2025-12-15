<?php

namespace App\Filament\Resources\RucQueries\Pages;

use App\Filament\Resources\RucQueries\RucQueryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRucQuery extends CreateRecord
{
    protected static string $resource = RucQueryResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $service = app(\App\Services\RucApiService::class);
        
        try {
            // Call API with RUC and selected environment
            $response = $service->consultRuc($data['ruc_number'], $data['environment']);
            
            // Check for API internal error or failure
            if (!isset($response['success']) || !$response['success']) {
                $msg = 'La API no retornó una respuesta exitosa.';
                if (isset($response['error'])) {
                    $msg .= ' ' . $response['error'];
                }
                
                \Filament\Notifications\Notification::make()
                    ->title('Error en la Consulta')
                    ->body($msg)
                    ->danger()
                    ->send();
                    
                $this->halt();
            }
            
            // Parse SET response structure
            // Structure: "resultadoSET" -> "ns2:rResEnviConsRUC" -> "ns2:xContRUC"
            $resultadoSet = $response['resultadoSET'] ?? [];
            $resEnvi = $resultadoSet['ns2:rResEnviConsRUC'] ?? [];
            
            // Check specific status code from SET if available (dCodRes)
            if (($resEnvi['ns2:dCodRes'] ?? '') !== '0502') { // 0502 = RUC encontrado
                 $msg = $resEnvi['ns2:dMsgRes'] ?? 'RUC no encontrado o error en SET.';
                 \Filament\Notifications\Notification::make()
                    ->title('Aviso del SIFEN')
                    ->body($msg)
                    ->warning()
                    ->send();
                 
                 // User might want to save failed queries too? 
                 // If payload data is missing, we can't save taxpayer info.
                 // Let's halt if not found.
                 $this->halt();
            }

            $taxpayerData = $resEnvi['ns2:xContRUC'] ?? null;
            
            if ($taxpayerData) {
                // Map API fields to DB columns
                $data['taxpayer_name'] = $taxpayerData['ns2:dRazCons'] ?? null;
                $data['status'] = $taxpayerData['ns2:dDesEstCons'] ?? null; // e.g., ACTIVO
                $data['taxpayer_data'] = $taxpayerData; // Store full object as JSON
                
                \Filament\Notifications\Notification::make()
                    ->title('Consulta Exitosa')
                    ->body("Contribuyente: " . $data['taxpayer_name'])
                    ->success()
                    ->send();
            } else {
                 \Filament\Notifications\Notification::make()
                    ->title('Datos Vacíos')
                    ->body('La respuesta no contiene datos del contribuyente.')
                    ->danger()
                    ->send();
                 $this->halt();
            }

        } catch (\Exception $e) {
            \Filament\Notifications\Notification::make()
                ->title('Error de Conexión')
                ->body($e->getMessage())
                ->danger()
                ->send();
            
            $this->halt();
        }

        return $data;
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}