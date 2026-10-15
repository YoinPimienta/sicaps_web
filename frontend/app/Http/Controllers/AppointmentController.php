<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AppointmentController extends Controller
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
                ->get($this->getApiUrl() . '/appointments');

            $appointments = [];
            if ($response->successful()) {
                $appointments = $response->json('data') ?? [];
            } else {
                \Illuminate\Support\Facades\Log::warning('Error cargando citas: ' . $response->status() . ' - ' . $response->body());
            }
            
            return view('appointments.index', compact('appointments'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Excepción conectando con backend en citas: ' . $e->getMessage());
            $appointments = [];
            return view('appointments.index', compact('appointments'));
        }
    }

    public function create()
    {
        try {
            $patientsResp = Http::withHeaders($this->getHeaders())->get($this->getApiUrl() . '/patients');
            $profResp = Http::withHeaders($this->getHeaders())->get($this->getApiUrl() . '/professionals');
            
            $patients = $patientsResp->successful() ? $patientsResp->json('data') ?? [] : [];
            $professionals = $profResp->successful() ? $profResp->json('data') : [];
            
            return view('appointments.create', compact('patients', 'professionals'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error cargando datos para la cita.');
        }
    }

    public function store(Request $request)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post($this->getApiUrl() . '/appointments', [
                    'patientId' => (int) $request->patientId,
                    'professionalId' => (int) $request->professionalId,
                    'date' => $request->date,
                    'time' => $request->time,
                    'reason' => $request->reason,
                    'observations' => $request->observations,
                    'durationMinutes' => 45
                ]);

            if ($response->successful()) {
                return redirect()->route('appointments.index')
                    ->with('success', 'Cita agendada exitosamente.')
                    ->with('appointment_assigned', 'Se ha programado una nueva cita en la agenda del profesional.');
            }
            
            $msg = $response->json('message') ?? 'Error desconocido';
            return back()->withInput()->with('error', 'Error al agendar la cita: ' . $msg . ' (Status: ' . $response->status() . ')');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error de conexión con el backend: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $status = $request->status; // SCHEDULED, COMPLETED, CANCELLED
            $response = Http::withHeaders($this->getHeaders())
                ->patch($this->getApiUrl() . '/appointments/' . $id . '/status?status=' . $status);

            if ($response->successful()) {
                return back()->with('success', 'Estado de la cita actualizado.');
            }
            
            return back()->with('error', 'Error al actualizar el estado.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error de conexión con el backend.');
        }
    }
}
