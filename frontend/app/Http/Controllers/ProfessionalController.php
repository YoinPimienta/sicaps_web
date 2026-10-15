<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ProfessionalController extends Controller
{
    private function getApiUrl()
    {
        return config('services.backend.api_url', env('API_BASE_URL', 'http://backend:8080/api'));
    }

    private function getHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . Session::get('jwt_token'),
            'Accept' => 'application/json',
        ];
    }

    /**
     * Muestra el directorio del equipo clínico de profesionales.
     */
    public function index()
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(5)
                ->get($this->getApiUrl() . '/professionals');

            if ($response->status() === 401) {
                Session::flush();
                return redirect()->route('login')->with('error', 'Tu sesión ha expirado.');
            }

            $professionals = [];
            if ($response->successful()) {
                $professionals = $response->json('data') ?? [];
            }

            // Consultar citas para relacionar actividad si está disponible
            $appointments = [];
            try {
                $aptResp = Http::withHeaders($this->getHeaders())->timeout(3)->get($this->getApiUrl() . '/appointments');
                if ($aptResp->successful()) {
                    $appointments = $aptResp->json('data') ?? [];
                }
            } catch (\Exception $ex) {
                // Silencioso
            }

            return view('professionals.index', compact('professionals', 'appointments'));
        } catch (\Exception $e) {
            Log::error('Error cargando profesionales: ' . $e->getMessage());
            $professionals = [];
            $appointments = [];
            return view('professionals.index', compact('professionals', 'appointments'));
        }
    }

    /**
     * Formulario de acreditación y registro de nuevo profesional de la salud (Solo Administrador).
     */
    public function create()
    {
        $role = Session::get('user_role');
        if ($role !== 'ROLE_ADMIN') {
            return redirect()->route('professionals.index')
                ->with('error', 'Acceso denegado: Únicamente la administración general o dirección médica puede habilitar y registrar nuevos profesionales clínicos.');
        }

        return view('professionals.create');
    }

    /**
     * Procesa y valida el registro del profesional ante la base de datos clínica.
     */
    public function store(Request $request)
    {
        $role = Session::get('user_role');
        if ($role !== 'ROLE_ADMIN') {
            return redirect()->route('professionals.index')
                ->with('error', 'Acceso no autorizado para dar de alta profesionales.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'lastName' => 'required|string|max:100',
            'documentType' => 'required|in:CC,CE,TI,PASSPORT',
            'documentNumber' => 'required|string|max:50',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:20',
            'specialty' => 'required|string|max:100',
            'medicalRegistrationNumber' => 'required|string|max:50',
            'birthDate' => 'nullable|date',
            'gender' => 'nullable|in:MASCULINO,FEMENINO,OTRO',
            'address' => 'nullable|string|max:255',
            'password' => 'required|string|min:6',
            'legal_confirmation' => 'accepted',
        ], [
            'legal_confirmation.accepted' => 'Debes certificar el cumplimiento legal y la habilitación sanitaria vigente del profesional.',
            'medicalRegistrationNumber.required' => 'El número de tarjeta profesional o registro ReTHUS es de carácter legal obligatorio.',
            'specialty.required' => 'Debes indicar el enfoque o especialidad clínica del profesional.',
        ]);

        $payload = [
            'name' => trim($request->name),
            'lastName' => trim($request->lastName),
            'documentType' => $request->documentType,
            'documentNumber' => trim($request->documentNumber),
            'email' => trim(strtolower($request->email)),
            'phone' => $request->phone,
            'specialty' => trim($request->specialty),
            'medicalRegistrationNumber' => trim($request->medicalRegistrationNumber),
            'birthDate' => $request->birthDate,
            'gender' => $request->gender,
            'address' => $request->address,
            'password' => $request->password,
        ];

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(6)
                ->post($this->getApiUrl() . '/professionals', $payload);

            if ($response->status() === 401) {
                Session::flush();
                return redirect()->route('login')->with('error', 'Tu sesión ha expirado.');
            }

            if ($response->successful()) {
                return redirect()->route('professionals.index')
                    ->with('success', '¡Profesional habilitado exitosamente! El o la especialista ya puede iniciar sesión con sus credenciales y recibir citas en la agenda.');
            }

            $errorMsg = $response->json('message') ?? 'No fue posible registrar al profesional. Verifica que el correo, documento o registro profesional no existan previamente.';
            return back()->withInput()->with('error', $errorMsg);

        } catch (\Exception $e) {
            Log::error('Error registrando profesional: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error de comunicación con el servicio de base de datos clínica.');
        }
    }

    /**
     * Muestra la ficha y credenciales del profesional.
     */
    public function show($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(5)
                ->get($this->getApiUrl() . '/professionals/' . $id);

            $professional = null;
            if ($response->successful()) {
                $professional = $response->json('data');
            } else {
                // Fallback: buscar en la lista completa
                $allResp = Http::withHeaders($this->getHeaders())->get($this->getApiUrl() . '/professionals');
                if ($allResp->successful()) {
                    $all = $allResp->json('data') ?? [];
                    foreach ($all as $p) {
                        if (($p['id'] ?? 0) == $id) {
                            $professional = $p;
                            break;
                        }
                    }
                }
            }

            if (!$professional) {
                return redirect()->route('professionals.index')->with('error', 'Profesional no encontrado en el directorio.');
            }

            return view('professionals.show', compact('professional'));

        } catch (\Exception $e) {
            return redirect()->route('professionals.index')->with('error', 'Error de conexión.');
        }
    }
}
