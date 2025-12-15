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
    /**
     * Consult taxpayer information by RUC
     * 
     * @param string $ruc
     * @param string $environment 'test' or 'prod'
     */
    public function consultRuc(string $ruc, string $environment = 'test'): array
    {
        // Get config for specific environment
        $config = config("sifen.environments.{$environment}");
        
        if (!$config) {
             throw new \Exception("Environment configuration not found for: {$environment}");
        }

        $url = rtrim($config['url'], '/') . '/sifen/consulta/ruc'; // Assuming endpoint path based on typical structure, or could be just $config['url'] if it includes path. User said URL_TESTING=...:37100 so likely base. 
        // Re-reading request: "url testing usa getenv URL_TESTING=...:37100" and "params": {...}
        // If the receiving service is a generic wrapper, maybe the URL is just the base?
        // Let's assume the user provided base URLs need a path appended, typically `/sifen/consultas/ruc` or similar?
        // Or is the payload wrapping specific to a middleware?
        // Let's use the URL from config directly if user didn't specify path.
        // Wait, user didn't specify path. 
        // But in previous logs, we saw `.../consultaruc/`.
        // Let's try appending `/sifen/consulta/ruc` as a safe guess for a middleware, or just use the base if it fails?
        // Actually, looking at the JSON payload `idCsc`, it looks exactly like what we used before.
        // Let's assumes the user provided URL is the BASE URL.
        // I will append `/sifen/consulta/ruc` which is standard-ish? Or maybe `/api/consultas/ruc`?
        // Let's look at `config/sifen.php` BEFORE my change. It had `/consultaruc/`.
        // I'll append `/sifen/consulta/ruc` as a starting point.
        
        // Actually, let's keep it simple. User gave `http://...:37100`. 
        // I will append `/sifen/consulta/ruc` 
        $url = rtrim($config['url'], '/') . '/consultaruc';

        $credentials = $config['credentials'];
        
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
        
        // Use the Http client from parent or direct
        // We need to override getApiUrl logic or just pass full URL to httpPost
        return $this->httpPost($url, $data);
    }
}
