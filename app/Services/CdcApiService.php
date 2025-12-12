<?php

namespace App\Services;

class CdcApiService extends SifenApiService
{
    protected string $apiName = 'cdc';
    
    /**
     * Get the API URL for CDC endpoint
     */
    protected function getApiUrl(string $endpoint = ''): string
    {
        return config("sifen.environments.{$this->environment}.cdc_url");
    }
    
    /**
     * Consult CDC document
     */
    public function consultCdc(string $cdc): array
    {
        $url = $this->getApiUrl();
        
        $credentials = $this->getCredentials();
        
        $arrParams = [
            'idCsc' => $credentials['idCsc'],
            'csc' => $credentials['csc'],
            'claveCertificado' => $credentials['claveCertificado'],
            'nombreCertificado' => $credentials['nombreCertificado'],
            'id' => $credentials['id'],
            'cdc' => $cdc,
        ];
        
        $data = [
            'params' => $arrParams,
        ];
        
        return $this->httpPost($url, $data);
    }
}
