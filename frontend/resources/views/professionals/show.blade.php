@extends('layouts.app')

@section('title', 'Ficha Profesional — ' . ($professional['user']['name'] ?? 'Profesional') . ' | SICAPS')

@section('header_title')
    <div class="flex items-center gap-3">
        <a href="{{ route('professionals.index') }}" class="p-2 rounded-xl border border-sicaps-border hover:bg-sicaps-sand-light text-sicaps-muted hover:text-sicaps-text transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
        </a>
        <div>
            <h2 class="text-xl font-bold text-sicaps-text tracking-tight flex items-center gap-2">
                <span>Expediente del Profesional</span>
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-sicaps-primary-soft text-sicaps-primary border border-sicaps-primary/20">
                    ID #{{ $professional['id'] ?? '—' }}
                </span>
            </h2>
            <p class="text-xs text-sicaps-muted mt-0.5">
                Datos de identificación, acreditación legal y estado de habilitación sanitaria.
            </p>
        </div>
    </div>
@endsection

@section('content')
@php
    $user = $professional['user'] ?? [];
    $fullName = trim(($user['name'] ?? '') . ' ' . ($user['lastName'] ?? ''));
    $words = explode(' ', $fullName);
    $initials = '';
    foreach ($words as $w) {
        if (!empty($w)) $initials .= mb_substr($w, 0, 1);
        if (mb_strlen($initials) >= 2) break;
    }
    $initials = strtoupper($initials ?: 'PR');
@endphp

<div class="max-w-5xl mx-auto space-y-6">

    <!-- CABECERA PRINCIPAL / TARJETA DE PERFIL -->
    <div class="bg-white rounded-card p-6 sm:p-8 border border-sicaps-border shadow-soft relative overflow-hidden">
        <div class="absolute -right-12 -top-12 w-48 h-48 rounded-full bg-sicaps-primary/5 blur-2xl pointer-events-none"></div>

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 relative z-10">
            
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-sicaps-primary text-white font-bold text-xl sm:text-2xl flex items-center justify-center flex-shrink-0 shadow-md">
                    {{ $initials }}
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-xl sm:text-2xl font-bold text-sicaps-text">{{ $fullName }}</h3>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-sicaps-success-soft text-sicaps-success">
                            <span class="w-1.5 h-1.5 rounded-full bg-sicaps-success"></span>
                            Habilitado
                        </span>
                    </div>
                    <p class="text-sm text-sicaps-primary font-semibold mt-0.5">{{ $professional['specialty'] ?? 'Psicología Clínica' }}</p>
                    <p class="text-xs text-sicaps-muted mt-1 flex items-center gap-2">
                        <span class="font-mono bg-sicaps-sand-light/60 px-2 py-0.5 rounded border border-sicaps-border text-sicaps-text">
                            ReTHUS: {{ $professional['medicalRegistrationNumber'] ?? 'No registrado' }}
                        </span>
                        <span>•</span>
                        <span>{{ $user['documentType'] ?? 'CC' }}: {{ $user['documentNumber'] ?? '—' }}</span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a 
                    href="{{ route('appointments.create') }}?professional_id={{ $professional['id'] ?? '' }}" 
                    class="w-full sm:w-auto py-2.5 px-4 bg-sicaps-primary hover:bg-sicaps-primary-hover text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm transition flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.253M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5"/>
                    </svg>
                    <span>Asignar Cita</span>
                </a>
            </div>

        </div>

    </div>

    <!-- DETALLES DIVIDIDOS EN 2 COLUMNAS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- COLUMNA 1: ACREDITACIÓN SANITARIA Y DEONTOLÓGICA -->
        <div class="bg-white rounded-card p-6 border border-sicaps-border shadow-soft space-y-5">
            <div class="flex items-center gap-2 pb-3 border-b border-sicaps-border/70">
                <div class="w-8 h-8 rounded-lg bg-sicaps-primary-soft text-sicaps-primary flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-sicaps-text">Habilitación Sanitaria Legal</h4>
                    <p class="text-[11px] text-sicaps-muted">Registro oficial en talento humano en salud</p>
                </div>
            </div>

            <div class="space-y-3.5 text-xs">
                
                <div class="flex justify-between py-2 border-b border-sicaps-border/50">
                    <span class="text-sicaps-muted">Tarjeta Profesional / ReTHUS:</span>
                    <span class="font-mono font-bold text-sicaps-text">{{ $professional['medicalRegistrationNumber'] ?? 'Pendiente' }}</span>
                </div>

                <div class="flex justify-between py-2 border-b border-sicaps-border/50">
                    <span class="text-sicaps-muted">Especialidad Clínica:</span>
                    <span class="font-semibold text-sicaps-primary">{{ $professional['specialty'] ?? 'Psicología General' }}</span>
                </div>

                <div class="flex justify-between py-2 border-b border-sicaps-border/50">
                    <span class="text-sicaps-muted">Rol en SICAPS:</span>
                    <span class="font-semibold text-sicaps-text">{{ $user['role']['description'] ?? 'Especialista Psicólogo' }}</span>
                </div>

                <div class="flex justify-between py-2 border-b border-sicaps-border/50">
                    <span class="text-sicaps-muted">Estado de Cuenta:</span>
                    <span class="text-sicaps-success font-semibold flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-sicaps-success"></span>
                        {{ $user['status'] ?? 'Activo' }}
                    </span>
                </div>

                <div class="flex justify-between py-2">
                    <span class="text-sicaps-muted">Fecha de Alta en Sistema:</span>
                    <span class="text-sicaps-text font-medium">
                        {{ !empty($professional['createdAt']) ? date('d/m/Y', strtotime($professional['createdAt'])) : 'Registrado' }}
                    </span>
                </div>

            </div>
        </div>

        <!-- COLUMNA 2: INFORMACIÓN DE CONTACTO Y CIUDADANA -->
        <div class="bg-white rounded-card p-6 border border-sicaps-border shadow-soft space-y-5">
            <div class="flex items-center gap-2 pb-3 border-b border-sicaps-border/70">
                <div class="w-8 h-8 rounded-lg bg-sicaps-sand text-sicaps-primary flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-sicaps-text">Datos Personales y Contacto</h4>
                    <p class="text-[11px] text-sicaps-muted">Vía de comunicación y sede de atención</p>
                </div>
            </div>

            <div class="space-y-3.5 text-xs">
                
                <div class="flex justify-between py-2 border-b border-sicaps-border/50">
                    <span class="text-sicaps-muted">Correo Electrónico:</span>
                    <span class="font-medium text-sicaps-text">{{ $user['email'] ?? '—' }}</span>
                </div>

                <div class="flex justify-between py-2 border-b border-sicaps-border/50">
                    <span class="text-sicaps-muted">Teléfono / Celular:</span>
                    <span class="font-medium text-sicaps-text">{{ $user['phone'] ?? 'No especificado' }}</span>
                </div>

                <div class="flex justify-between py-2 border-b border-sicaps-border/50">
                    <span class="text-sicaps-muted">Dirección / Consultorio:</span>
                    <span class="font-medium text-sicaps-text">{{ $user['address'] ?? 'Consultorio del centro' }}</span>
                </div>

                <div class="flex justify-between py-2 border-b border-sicaps-border/50">
                    <span class="text-sicaps-muted">Género:</span>
                    <span class="font-medium text-sicaps-text">{{ $user['gender'] ?? 'No registrado' }}</span>
                </div>

                <div class="flex justify-between py-2">
                    <span class="text-sicaps-muted">Fecha de Nacimiento:</span>
                    <span class="font-medium text-sicaps-text">
                        {{ !empty($user['birthDate']) ? date('d/m/Y', strtotime($user['birthDate'])) : 'No registrada' }}
                    </span>
                </div>

            </div>
        </div>

    </div>

    <!-- RESPONSABILIDAD LEGAL DE HISTORIAS CLÍNICAS -->
    <div class="p-5 rounded-2xl bg-sicaps-sand-light/50 border border-sicaps-border text-xs text-sicaps-muted flex items-start gap-3">
        <svg class="w-5 h-5 text-sicaps-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.25-8.25-3.286Zm0 13.036h.008v.008H12v-.008Z"/>
        </svg>
        <p class="leading-relaxed">
            Las evoluciones clínicas, intervenciones psicoterapéuticas y diagnósticos registrados por <strong>{{ $fullName }}</strong> quedan protegidos bajo el secreto profesional de la Ley de Salud Mental y las normas de custodia documental en salud.
        </p>
    </div>

</div>
@endsection
