<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ApiLog;

abstract class SifenApiService
{
    protected string $apiName;
    protected string $environment;
    
    public function __construct()
    {
        $this->environment = config('sifen.environment', 'dev');
    }
    
    /**
     * Set the environment for API calls
     */
    public function setEnvironment(string $environment): self
    {
        $this->environment = $environment;
        return $this;
    }
    
    /**
     * Get the API URL for the current environment
     */
    abstract protected function getApiUrl(string $endpoint): string;
    
    /**
     * Get SIFEN credentials
     */
    protected function getCredentials(): array
    {
        return config('sifen.credentials');
    }
    
    /**
     * Make HTTP POST request to SIFEN API
     */
    protected function httpPost(string $url, array $data): array
    {
        $startTime = microtime(true);
        
        try {
            $response = Http::timeout(config('sifen.timeout', 30))
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($url, $data);
            
            $responseTime = (int) ((microtime(true) - $startTime) * 1000);
            
            $responseData = $response->json() ?? ['raw' => $response->body()];
            
            // Log the API call
            $this->logApiCall($url, $data, $responseData, $response->status(), $responseTime);
            
            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'data' => $responseData,
                'error' => $response->successful() ? null : ('HTTP Error ' . $response->status() . ': ' . substr($response->body(), 0, 200)),
                'response_time' => $responseTime,
            ];
            
        } catch (\Exception $e) {
            $responseTime = (int) ((microtime(true) - $startTime) * 1000);
            
            // Log the error
            $this->logApiCall($url, $data, null, 0, $responseTime, $e->getMessage());
            
            return [
                'success' => false,
                'status' => 0,
                'error' => $e->getMessage(),
                'response_time' => $responseTime,
            ];
        }
    }
    
    /**
     * Log API call to database
     */
    protected function logApiCall(
        string $endpoint,
        array $requestData,
        ?array $responseData,
        int $statusCode,
        int $responseTime,
        ?string $errorMessage = null
    ): void {
        try {
            ApiLog::create([
                'api_name' => $this->apiName,
                'environment' => $this->environment,
                'endpoint' => $endpoint,
                'request_data' => $requestData,
                'response_data' => $responseData,
                'status_code' => $statusCode,
                'response_time' => $responseTime,
                'error_message' => $errorMessage,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log API call', [
                'api_name' => $this->apiName,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
