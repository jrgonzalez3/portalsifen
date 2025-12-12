<?php

namespace App\Services;

class KudeApiService extends SifenApiService
{
    protected string $apiName = 'kude';
    
    /**
     * Get the API URL for Kude endpoint
     */
    protected function getApiUrl(string $endpoint = ''): string
    {
        return config("sifen.environments.{$this->environment}.kude_url");
    }
    
    /**
     * Consult Kude by CDC
     */
    public function consultKude(string $cdc): array
    {
        $url = $this->getApiUrl();
        
        $data = [
            'cdc' => $cdc,
        ];
        
        return $this->httpPost($url, $data);
    }
}
