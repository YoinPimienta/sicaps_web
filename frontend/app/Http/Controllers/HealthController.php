<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HealthController
{
    public function checkApi()
    {
        $apiUrl = config('services.backend.api_url', env('API_BASE_URL', 'http://backend:8080/api'));
        
        try {
            $response = Http::get($apiUrl . '/health');
            
            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Comunicación con Spring Boot exitosa.',
                    'backend_data' => $response->json()
                ]);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => 'El backend respondió con error.',
                'status_code' => $response->status()
            ], $response->status());
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo conectar con el backend Java.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
