@extends('layouts.app')

@section('title', 'Historia Clínica — ' . ($patient['user']['name'] ?? 'Paciente') . ' ' . ($patient['user']['lastName'] ?? ''))
@section('header_title', 'Expediente Clínico del Paciente')

@section('content')
@php
    $fullName = ($patient['user']['name'] ?? '') . ' ' . ($patient['user']['lastName'] ?? '');
    $initials = strtoupper(substr($patient['user']['name'] ?? 'P', 0, 1) . substr($patient['user']['lastName'] ?? 'P', 0, 1));
    $docType = $patient['user']['documentType'] ?? 'CC';
    $docNum = $patient['user']['documentNumber'] ?? '';
    $email = $patient['user']['email'] ?? 'No registrado';
    $phone = $patient['user']['phone'] ?? 'Sin teléfono registrado';
    $emName = $patient['emergencyContactName'] ?? 'No registrado';
    $emPhone = $patient['emergencyContactPhone'] ?? '';
    $evoCount = count($evolutions ?? []);
@endphp

<div class="space-y-6" x-data="{ tab: 'evoluciones' }">
    
    <!-- ENLACE DE RETORNO Y SEGURIDAD -->
    <div class="flex items-center justify-between">
        <a 
            href="{{ route('patients.index') }}" 
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-sicaps-muted hover:text-sicaps-text transition"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
            <span>Volver al Directorio</span>
        </a>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 text-xs text-sicaps-muted bg-white px-3 py-1.5 rounded-full border border-sicaps-border shadow-soft">
                <svg class="w-3.5 h-3.5 text-sicaps-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                </svg>
                <span>Historia Clínica Protegida</span>
            </span>
        </div>
    </div>

    <!-- BANNER PRINCIPAL DEL PACIENTE (DOSSIER HEADER) -->
    <div class="bg-white rounded-card border border-sicaps-border shadow-soft p-6 sm:p-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            
            <!-- Datos e Identidad del Paciente -->
            <div class="flex items-start sm:items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-sicaps-primary-soft text-sicaps-primary font-bold text-xl flex items-center justify-center flex-shrink-0 border-2 border-sicaps-primary/20 shadow-sm">
                    {{ $initials }}
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h1 class="text-2xl font-bold tracking-tight text-sicaps-text">
                            {{ $fullName ?: 'Paciente Sin Nombre' }}
                        </h1>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-semibold bg-sicaps-sand-light text-sicaps-text border border-sicaps-border">
                            {{ $docType }}: {{ $docNum }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sicaps-success-soft text-sicaps-success">
                            <span class="w-1.5 h-1.5 rounded-full bg-sicaps-success"></span>
                            Tratamiento Activo
                        </span>
                    </div>

                    <div class="mt-2 flex flex-wrap items-center gap-y-1 gap-x-5 text-xs text-sicaps-muted">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-sicaps-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                            {{ $email }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-sicaps-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                            {{ $phone }}
                        </span>
                        @if(!empty($emName) && $emName !== 'No registrado')
                            <span class="flex items-center gap-1 text-sicaps-text font-medium bg-sicaps-bg px-2 py-0.5 rounded-md border border-sicaps-border">
                                <span class="text-sicaps-muted">Emergencia:</span> {{ $emName }} {{ $emPhone ? "($emPhone)" : '' }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Acciones Clínicas Principales -->
            <div class="flex flex-wrap items-center gap-3">
                <a 
                    href="{{ route('appointments.create') }}" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-[10px] bg-white border border-sicaps-border hover:bg-sicaps-sand-light text-sicaps-text font-medium text-xs shadow-soft transition"
                >
                    <svg class="w-3.5 h-3.5 text-sicaps-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.253M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5"/>
                    </svg>
                    <span>Agendar Cita</span>
                </a>

                @if(session('user_role') === 'ROLE_ADMIN' || session('user_role') === 'ROLE_PROFESSIONAL')
                    <a 
                        href="{{ route('clinical.evolutions.create', $patient['id'] ?? 0) }}" 
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-[10px] bg-sicaps-primary hover:bg-sicaps-primary-hover active:scale-[0.98] text-white font-semibold text-xs shadow-sm transition"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        <span>Nueva Evolución SOAP</span>
                    </a>

                    <a 
                        href="{{ route('patients.edit', $patient['id'] ?? 0) }}" 
                        class="p-2.5 rounded-[10px] border border-sicaps-border hover:bg-sicaps-sand-light text-sicaps-muted hover:text-sicaps-text transition"
                        title="Modificar datos del paciente"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                        </svg>
                    </a>
                @endif
            </div>

        </div>
    </div>

    <!-- PESTAÑAS CLÍNICAS (TABS NAVIGATION) -->
    <div class="border-b border-sicaps-border flex items-center gap-8">
        <button 
            @click="tab = 'evoluciones'" 
            :class="tab === 'evoluciones' ? 'border-sicaps-primary text-sicaps-primary font-bold' : 'border-transparent text-sicaps-muted hover:text-sicaps-text font-medium'"
            class="py-3 px-1 border-b-2 text-sm transition flex items-center gap-2"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
            <span>Evoluciones Clínicas (SOAP)</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-sicaps-primary-soft text-sicaps-primary">
                {{ $evoCount }}
            </span>
        </button>

        <button 
            @click="tab = 'antecedentes'" 
            :class="tab === 'antecedentes' ? 'border-sicaps-primary text-sicaps-primary font-bold' : 'border-transparent text-sicaps-muted hover:text-sicaps-text font-medium'"
            class="py-3 px-1 border-b-2 text-sm transition flex items-center gap-2"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/></svg>
            <span>Antecedentes Médicos y Psicológicos</span>
        </button>

        <button 
            @click="tab = 'contacto'" 
            :class="tab === 'contacto' ? 'border-sicaps-primary text-sicaps-primary font-bold' : 'border-transparent text-sicaps-muted hover:text-sicaps-text font-medium'"
            class="py-3 px-1 border-b-2 text-sm transition flex items-center gap-2"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z"/></svg>
            <span>Datos Personales y Filiación</span>
        </button>
    </div>

    <!-- CONTENIDO DE PESTAÑA 1: EVOLUCIONES CLÍNICAS (SOAP) -->
    <div x-show="tab === 'evoluciones'" class="space-y-4">
        
        <div class="flex items-center justify-between pb-2">
            <h3 class="text-base font-bold text-sicaps-text">Historial Cronológico de Sesiones</h3>
            @if(session('user_role') === 'ROLE_ADMIN' || session('user_role') === 'ROLE_PROFESSIONAL')
                <a 
                    href="{{ route('clinical.evolutions.create', $patient['id'] ?? 0) }}" 
                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-sicaps-primary hover:text-sicaps-primary-hover hover:underline"
                >
                    + Registrar Nueva Sesión
                </a>
            @endif
        </div>

        @forelse($evolutions ?? [] as $evo)
            @php
                $profUser = $evo['professional']['user']['name'] ?? 'Profesional Asignado';
                $profLast = $evo['professional']['user']['lastName'] ?? '';
            @endphp
            <div class="bg-white rounded-card border border-sicaps-border shadow-soft p-6 hover:border-sicaps-primary/30 transition">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 mb-4 border-b border-sicaps-border/70">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-sicaps-primary-soft text-sicaps-primary flex items-center justify-center font-bold text-xs">
                            SOAP
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-sicaps-text">{{ $evo['reason'] ?? 'Sesión Terapéutica' }}</h4>
                            <span class="text-xs text-sicaps-muted">
                                Atendido por: {{ $profUser }} {{ $profLast }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs text-sicaps-muted bg-sicaps-sand-light px-2.5 py-1 rounded-md">
                            {{ \Carbon\Carbon::parse($evo['createdAt'])->locale('es')->isoFormat('D [de] MMMM [de] YYYY • H:mm a') }}
                        </span>
                    </div>
                </div>

                <!-- Detalle de Notas de Evolución -->
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-sicaps-muted block mb-1">
                            Notas de Evolución Clínica
                        </span>
                        <div class="p-3.5 rounded-lg bg-sicaps-bg border border-sicaps-border/70 text-sicaps-text leading-relaxed whitespace-pre-line text-xs sm:text-sm">
                            {{ $evo['evolutionNotes'] ?? 'Sin notas registradas' }}
                        </div>
                    </div>

                    @if(!empty($evo['observations']))
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-sicaps-muted block mb-1">
                                Diagnóstico / Plan Terapéutico (CIE-10)
                            </span>
                            <div class="p-3 rounded-lg bg-sicaps-primary-soft/30 border border-sicaps-primary/20 text-xs font-medium text-sicaps-text">
                                {{ $evo['observations'] }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-card border border-sicaps-border shadow-soft py-16 px-6 text-center">
                <div class="max-w-sm mx-auto flex flex-col items-center">
                    <div class="w-14 h-14 rounded-full bg-sicaps-primary-soft text-sicaps-primary flex items-center justify-center mb-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-sicaps-text">No hay evoluciones registradas todavía</h3>
                    <p class="text-xs text-sicaps-muted mt-1 mb-5">
                        Aún no se ha realizado ninguna sesión o nota clínica para este paciente.
                    </p>
                    @if(session('user_role') === 'ROLE_ADMIN' || session('user_role') === 'ROLE_PROFESSIONAL')
                        <a 
                            href="{{ route('clinical.evolutions.create', $patient['id'] ?? 0) }}" 
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-[10px] bg-sicaps-primary text-white text-xs font-semibold hover:bg-sicaps-primary-hover shadow-sm transition"
                        >
                            + Redactar Primera Evolución SOAP
                        </a>
                    @endif
                </div>
            </div>
        @endforelse

    </div>

    <!-- CONTENIDO DE PESTAÑA 2: ANTECEDENTES MÉDICOS Y PSICOLÓGICOS -->
    <div x-show="tab === 'antecedentes'" x-cloak class="space-y-6">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Antecedentes Médicos -->
            <div class="bg-white rounded-card border border-sicaps-border shadow-soft p-6">
                <div class="flex items-center gap-2.5 mb-4 pb-3 border-b border-sicaps-border">
                    <div class="w-8 h-8 rounded-lg bg-sicaps-primary-soft text-sicaps-primary flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-sicaps-text">Antecedentes Médicos y Farmacológicos</h4>
                        <span class="text-[11px] text-sicaps-muted">Enfermedades crónicas y medicación</span>
                    </div>
                </div>

                <div class="p-4 rounded-lg bg-sicaps-bg border border-sicaps-border/70 text-xs sm:text-sm text-sicaps-text leading-relaxed min-h-[120px]">
                    {{ $patient['medicalBackground'] ?? 'No se registraron antecedentes médicos relevantes durante la admisión.' }}
                </div>
            </div>

            <!-- Antecedentes Psicológicos -->
            <div class="bg-white rounded-card border border-sicaps-border shadow-soft p-6">
                <div class="flex items-center gap-2.5 mb-4 pb-3 border-b border-sicaps-border">
                    <div class="w-8 h-8 rounded-lg bg-sicaps-sand text-sicaps-text flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-sicaps-text">Antecedentes Psicológicos y Terapéuticos</h4>
                        <span class="text-[11px] text-sicaps-muted">Procesos anteriores y motivos de consulta</span>
                    </div>
                </div>

                <div class="p-4 rounded-lg bg-sicaps-bg border border-sicaps-border/70 text-xs sm:text-sm text-sicaps-text leading-relaxed min-h-[120px]">
                    {{ $patient['psychologicalBackground'] ?? 'Sin procesos psicoterapéuticos o antecedentes de salud mental previos registrados.' }}
                </div>
            </div>

        </div>

    </div>

    <!-- CONTENIDO DE PESTAÑA 3: DATOS PERSONALES Y FILIACIÓN -->
    <div x-show="tab === 'contacto'" x-cloak class="bg-white rounded-card border border-sicaps-border shadow-soft p-6 sm:p-8">
        <h3 class="text-base font-bold text-sicaps-text mb-6 pb-2 border-b border-sicaps-border">
            Ficha de Identificación del Paciente
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-xs sm:text-sm">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-sicaps-muted block">Nombre Completo</span>
                <span class="font-bold text-sicaps-text mt-1 block">{{ $fullName }}</span>
            </div>

            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-sicaps-muted block">Documento de Identidad</span>
                <span class="font-bold text-sicaps-text mt-1 block">{{ $docType }} {{ $docNum }}</span>
            </div>

            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-sicaps-muted block">Correo Electrónico</span>
                <span class="font-medium text-sicaps-text mt-1 block">{{ $email }}</span>
            </div>

            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-sicaps-muted block">Teléfono Móvil</span>
                <span class="font-medium text-sicaps-text mt-1 block">{{ $phone }}</span>
            </div>

            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-sicaps-muted block">Contacto de Emergencia</span>
                <span class="font-medium text-sicaps-text mt-1 block">{{ $emName }}</span>
            </div>

            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-sicaps-muted block">Teléfono de Emergencia</span>
                <span class="font-medium text-sicaps-text mt-1 block">{{ $emPhone ?: 'No registrado' }}</span>
            </div>
        </div>
    </div>

</div>
@endsection
