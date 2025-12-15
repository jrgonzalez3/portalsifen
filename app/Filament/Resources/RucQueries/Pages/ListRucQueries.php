<?php

namespace App\Filament\Resources\RucQueries\Pages;

use App\Filament\Resources\RucQueries\RucQueryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRucQueries extends ListRecords
{
    protected static string $resource = RucQueryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('consultar_ruc')
                ->label('Consultar RUC')
                ->icon('heroicon-o-magnifying-glass')
                ->modalHeading('Nueva Consulta RUC')
                ->modalDescription('Ingrese el RUC y el entorno para consultar al SIFEN.')
                ->form([
                    \Filament\Forms\Components\TextInput::make('ruc_number')
                        ->label('Número de RUC')
                        ->helperText('Ingrese solo el número, sin el dígito verificador.')
                        ->required()
                        ->numeric()
                        ->maxLength(20),
                    \Filament\Forms\Components\Select::make('environment')
                        ->label('Entorno')
                        ->options([
                            'test' => 'Testing (Pruebas)',
                            'prod' => 'Producción',
                        ])
                        ->default('test')
                        ->required()
                        ->native(false),
                ])
                ->action(function (array $data) {
                    $service = app(\App\Services\RucApiService::class);
                    
                    try {
                        // Call API with RUC and selected environment
                        $responseWrapper = $service->consultRuc($data['ruc_number'], $data['environment']);
                        // Check for API internal error or failure (Network/Timeout/404/500)
                        if (!isset($responseWrapper['success']) || !$responseWrapper['success']) {
                            $errorMsg = $responseWrapper['error'] ?? 'Error desconocido';
                            \Filament\Notifications\Notification::make()
                                ->title('Fallo conexión API')
                                ->body("Error: {$errorMsg}")
                                ->danger()
                                ->persistent() // Keep it visible
                                ->send();
                                
                            $this->halt();
                        }
                        
                        // Extract actual data from wrapper
                        $responseData = $responseWrapper['data'] ?? [];
                        
                        // Parse SET response structure
                        $resultadoSet = $responseData['resultadoSET'] ?? [];
                        $resEnvi = $resultadoSet['ns2:rResEnviConsRUC'] ?? [];
                        
                        // Check for generic error structure (rRetEnviDe) if RUC structure missing
                        if (empty($resEnvi)) {
                             $retDe = $resultadoSet['ns2:rRetEnviDe'] ?? null;
                             if ($retDe) {
                                  $gResProc = $retDe['ns2:rProtDe']['ns2:gResProc'] ?? [];
                                  $msg = $gResProc['ns2:dMsgRes'] ?? 'Error devuelto por SIFEN';
                                  $cod = $gResProc['ns2:dCodRes'] ?? 'N/A';
                                  
                                  \Filament\Notifications\Notification::make()
                                    ->title('Rechazo SIFEN')
                                    ->body("Mensaje: {$msg} | Código: {$cod}")
                                    ->danger()
                                    ->persistent()
                                    ->send();
                                  $this->halt();
                             }
                        }
                        
                        // If response is empty or unexpected structure, show debug info
                        if (empty($resEnvi)) {
                             \Filament\Notifications\Notification::make()
                                ->title('Respuesta Inesperada')
                                ->body('La API respondió pero no se encontró la estructura esperada: ' . json_encode($responseData))
                                ->warning()
                                ->persistent()
                                ->send();
                             $this->halt();
                        }

                        // Check specific status code from SET if available (dCodRes)
                        if (($resEnvi['ns2:dCodRes'] ?? '') !== '0502') { // 0502 = RUC encontrado
                             $msg = $resEnvi['ns2:dMsgRes'] ?? 'RUC no encontrado o error en SET.';
                             \Filament\Notifications\Notification::make()
                                ->title('Aviso del SIFEN')
                                ->body("Mensaje: {$msg} | Código: " . ($resEnvi['ns2:dCodRes'] ?? 'N/A'))
                                ->warning()
                                ->send();
                             
                             $this->halt();
                        }
            
                        $taxpayerData = $resEnvi['ns2:xContRUC'] ?? null;
                        
                        if ($taxpayerData) {
                            // Map API fields to DB columns
                            $data['taxpayer_name'] = $taxpayerData['ns2:dRazCons'] ?? null;
                            $data['status'] = $taxpayerData['ns2:dDesEstCons'] ?? null; // e.g., ACTIVO
                            $data['taxpayer_data'] = $taxpayerData; 
                            $data['user_id'] = auth()->id();
                            
                            // Create Record
                            \App\Models\RucQuery::create($data);
                            
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
                         // Full exception debug
                        \Filament\Notifications\Notification::make()
                            ->title('Excepción Crítica')
                            ->body($e->getMessage() . ' | Line: ' . $e->getLine())
                            ->danger()
                            ->persistent()
                            ->send();
                        
                        $this->halt();
                    }
                }),
        ];
    }
}
