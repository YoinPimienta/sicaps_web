<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class PatientController extends Controller
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

            $patients = $response->successful() ? ($response->json('data') ?? []) : [];
            return view('patients.index', compact('patients'));
        } catch (\Exception $e) {
            $patients = [];
            return view('patients.index', compact('patients'));
        }
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post($this->getApiUrl() . '/patients', $request->all());

            if ($response->successful()) {
                return redirect()->route('patients.index')->with('success', 'Paciente creado exitosamente.');
            }
            
            return back()->withInput()->with('error', 'Error al crear el paciente: ' . ($response->json('message') ?? ''));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error de conexión con el backend.');
        }
    }

    public function show($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->getApiUrl() . '/patients/' . $id);

            $evoResponse = Http::withHeaders($this->getHeaders())
                ->get($this->getApiUrl() . '/clinical/records/' . $id);

            if ($response->successful()) {
                $patient = $response->json('data');
                $evolutions = $evoResponse->successful() ? $evoResponse->json('data') : [];
                return view('patients.show', compact('patient', 'evolutions'));
            }
            
            $msg = $response->json('message') ?? 'Paciente no encontrado.';
            return redirect()->route('patients.index')->with('error', 'Error del servidor: ' . $msg . ' (Status: ' . $response->status() . ')');
        } catch (\Exception $e) {
            return redirect()->route('patients.index')->with('error', 'Error de conexión con el backend: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->getApiUrl() . '/patients/' . $id);

            if ($response->successful()) {
                $patient = $response->json('data');
                return view('patients.edit', compact('patient'));
            }
            
            $msg = $response->json('message') ?? 'Paciente no encontrado.';
            return redirect()->route('patients.index')->with('error', 'Error del servidor: ' . $msg . ' (Status: ' . $response->status() . ')');
        } catch (\Exception $e) {
            return redirect()->route('patients.index')->with('error', 'Error de conexión con el backend: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->put($this->getApiUrl() . '/patients/' . $id, $request->all());

            if ($response->successful()) {
                return redirect()->route('patients.index')->with('success', 'Paciente actualizado exitosamente.');
            }
            
            return back()->withInput()->with('error', 'Error al actualizar el paciente.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error de conexión con el backend.');
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->delete($this->getApiUrl() . '/patients/' . $id);

            if ($response->successful()) {
                return redirect()->route('patients.index')->with('success', 'Paciente eliminado exitosamente.');
            }
            
            return back()->with('error', 'Error al eliminar el paciente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error de conexión con el backend.');
        }
    }
}
