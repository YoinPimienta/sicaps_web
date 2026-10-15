<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ClinicalController extends Controller
{
    private function getApiUrl() {
        return config('services.backend.api_url', env('API_BASE_URL', 'http://backend:8080/api'));
    }

    private function getHeaders() {
        return [
            'Authorization' => 'Bearer ' . Session::get('jwt_token'),
            'Accept' => 'application/json',
        ];
    }

    public function index()
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->getApiUrl() . '/patients');

            if ($response->successful()) {
                $patients = $response->json('data') ?? [];
                return view('clinical.index', compact('patients'));
            }
            
            return back()->with('error', 'No se pudieron cargar los pacientes.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error de conexión con el backend.');
        }
    }

    public function createEvolution($patientId)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->getApiUrl() . '/patients/' . $patientId);

            if ($response->successful()) {
                $patient = $response->json('data');
                return view('clinical.evolutions.create', compact('patient'));
            }
            return redirect()->back()->with('error', 'Paciente no encontrado.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error de conexión.');
        }
    }

    public function storeEvolution(Request $request, $patientId)
    {
        try {
            $profId = 1; // ID fijo del profesional de pruebas
            
            $response = Http::withHeaders($this->getHeaders())
                ->post($this->getApiUrl() . '/clinical/evolutions/' . $patientId . '/' . $profId, [
                    'reason' => $request->reason,
                    'evolutionNotes' => $request->description,
                    'observations' => $request->diagnosis ?? ''
                ]);

            if ($response->successful()) {
                return redirect()->route('patients.show', $patientId)->with('success', 'Evolución guardada.');
            }
            
            return redirect()->back()->withInput()->with('error', 'Error al guardar: ' . ($response->json('message') ?? ''));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error de conexión con el backend.');
        }
    }
}
