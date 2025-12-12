<?php

namespace App\Services;

class LoteApiService extends SifenApiService
{
    protected string $apiName = 'lote';
    
    /**
     * Get the API URL for Lote endpoint
     */
    protected function getApiUrl(string $endpoint = ''): string
    {
        return config("sifen.environments.{$this->environment}.lote_url");
    }
    
    /**
     * Consult batch by batch number
     */
    public function consultLote(string $numerolote): array
    {
        $url = $this->getApiUrl();
        
        $credentials = $this->getCredentials();
        
        $arrParams = [
            'idCsc' => $credentials['idCsc'],
            'csc' => $credentials['csc'],
            'claveCertificado' => $credentials['claveCertificado'],
            'nombreCertificado' => $credentials['nombreCertificado'],
            'id' => $credentials['id'],
            'numerolote' => $numerolote,
        ];
        
        $data = [
            'params' => $arrParams,
        ];
        
        return $this->httpPost($url, $data);
    }
}
