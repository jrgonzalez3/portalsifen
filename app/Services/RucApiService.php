<?php

namespace App\Services;

class RucApiService extends SifenApiService
{
    protected string $apiName = 'ruc';
    
    /**
     * Get the API URL for RUC endpoint
     */
    protected function getApiUrl(string $endpoint = ''): string
    {
        return config("sifen.environments.{$this->environment}.ruc_url");
    }
    
    /**
     * Consult taxpayer information by RUC
     */
    public function consultRuc(string $ruc): array
    {
        $url = $this->getApiUrl();
        
        $credentials = $this->getCredentials();
        
        $arrParams = [
            'idCsc' => $credentials['idCsc'],
            'csc' => $credentials['csc'],
            'claveCertificado' => $credentials['claveCertificado'],
            'nombreCertificado' => $credentials['nombreCertificado'],
            'id' => $credentials['id'],
            'ruc' => $ruc,
        ];
        
        $data = [
            'params' => $arrParams,
        ];
        
        return $this->httpPost($url, $data);
    }
}
