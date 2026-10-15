@extends('layouts.app')

@section('title', 'Detalle del Paciente - SICAPS Web')
@section('header_title')
<div class="flex items-center">
    <a href="{{ route('patients.index') }}" class="text-gray-500 hover:text-blue-600 mr-4">← Volver</a>
    <h2 class="text-xl font-semibold text-gray-800">Detalle del Paciente</h2>
</div>
@endsection

@section('content')
<!-- Requerir Alpine.js para las pestañas -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<div x-data="{ tab: 'personal' }">
    
    <!-- Cabecera Paciente -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center">
        <div>
            <h3 class="text-2xl font-bold text-gray-900">{{ $patient['user']['name'] ?? '' }} {{ $patient['user']['lastName'] ?? '' }}</h3>
            <p class="text-gray-500">{{ $patient['user']['documentType'] ?? '' }}: {{ $patient['user']['documentNumber'] ?? '' }}</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <a href="{{ route('appointments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition inline-block text-center">
                Agendar Cita
            </a>
            @if(session('user_role') === 'ROLE_ADMIN' || session('user_role') === 'ROLE_PROFESSIONAL')
            <a href="{{ route('clinical.evolutions.create', $patient['id'] ?? 0) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition inline-block text-center">
                Nueva Evolución
            </a>
            @endif
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button @click="tab = 'personal'" :class="{'border-blue-500 text-blue-600': tab === 'personal', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'personal'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Información Personal
            </button>
            <button @click="tab = 'historia'" :class="{'border-blue-500 text-blue-600': tab === 'historia', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'historia'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Historia Clínica
            </button>
        </nav>
    </div>

    <!-- Tab Contents -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        
        <!-- Personal Info -->
        <div x-show="tab === 'personal'" x-transition>
            <h4 class="text-lg font-medium text-gray-900 mb-4">Datos de Contacto</h4>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Correo Electrónico</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $patient['user']['email'] ?? 'No registrado' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $patient['user']['phone'] ?? 'No registrado' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Contacto de Emergencia</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $patient['emergencyContactName'] ?? 'No registrado' }} 
                        @if(!empty($patient['emergencyContactPhone']))
                            ({{ $patient['emergencyContactPhone'] }})
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Historia Clínica -->
        <div x-show="tab === 'historia'" x-transition style="display: none;">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-medium text-gray-900">Antecedentes</h4>
            </div>
            <div class="space-y-4">
                <div>
                    <h5 class="text-sm font-bold text-gray-700">Antecedentes Médicos</h5>
                    <p class="text-sm text-gray-600 mt-1 p-3 bg-gray-50 rounded border border-gray-100">
                        {{ $patient['medicalBackground'] ?? 'Ninguno registrado.' }}
                    </p>
                </div>
                <div>
                    <h5 class="text-sm font-bold text-gray-700">Antecedentes Psicológicos</h5>
                    <p class="text-sm text-gray-600 mt-1 p-3 bg-gray-50 rounded border border-gray-100">
                        {{ $patient['psychologicalBackground'] ?? 'Ninguno registrado.' }}
                    </p>
                </div>
            </div>

            <!-- Sección de Evoluciones -->
            <div class="mt-8">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-medium text-gray-900">Evoluciones Clínicas</h4>
                </div>
                
                <div class="space-y-4">
                    @forelse($evolutions ?? [] as $evo)
                    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h5 class="font-semibold text-gray-800">{{ $evo['reason'] ?? 'Evolución' }}</h5>
                                <p class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($evo['createdAt'])->format('d/m/Y H:i') }} - 
                                    Profesional: {{ $evo['professional']['user']['name'] ?? '' }} {{ $evo['professional']['user']['lastName'] ?? '' }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="text-sm text-gray-700"><strong>Notas:</strong> {{ $evo['evolutionNotes'] ?? 'Sin notas' }}</p>
                            @if(!empty($evo['observations']))
                                <p class="text-sm text-gray-700 mt-1"><strong>Observaciones:</strong> {{ $evo['observations'] }}</p>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 italic p-4 bg-gray-50 rounded text-center">No hay evoluciones registradas para este paciente.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
