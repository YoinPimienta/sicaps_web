@extends('layouts.app')

@section('title', 'Historias Clínicas — SICAPS')
@section('header_title', 'Historias Clínicas y Expedientes')

@section('content')
<div class="space-y-6" x-data="{ search: '' }">
    
    <!-- ENCABEZADO Y CONTEXTO CLÍNICO -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-bold tracking-tight text-sicaps-text">Directorio de Historias Clínicas</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sicaps-primary-soft text-sicaps-primary">
                    {{ count($patients ?? []) }} expedientes
                </span>
            </div>
            <p class="text-sm text-sicaps-muted mt-1">
                Consulta antecedentes, evoluciones SOAP y seguimiento terapéutico de cada paciente.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 text-xs text-sicaps-muted bg-white px-3 py-2 rounded-[10px] border border-sicaps-border shadow-soft">
                <svg class="w-4 h-4 text-sicaps-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                </svg>
                <span>Acceso restringido a personal de salud mental</span>
            </span>
        </div>
    </div>

    <!-- TARJETA PRINCIPAL CON BUSCADOR Y LISTA -->
    <div class="bg-white rounded-card border border-sicaps-border shadow-soft overflow-hidden">
        
        <!-- Barra de Búsqueda -->
        <div class="p-4 sm:p-5 border-b border-sicaps-border bg-[#FAF9F6] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="relative w-full sm:w-80">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-sicaps-muted">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                </div>
                <input 
                    type="text" 
                    x-model="search"
                    placeholder="Buscar por paciente o documento..." 
                    class="w-full pl-10 pr-4 py-2 bg-white border border-sicaps-border rounded-[10px] text-xs sm:text-sm text-sicaps-text placeholder-sicaps-muted/60 focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                >
            </div>

            <span class="text-xs text-sicaps-muted">
                Evoluciones registradas con formato SOAP y firma electrónica
            </span>
        </div>

        <!-- Tabla de Expedientes -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-sicaps-sand-light/50 text-sicaps-muted text-xs font-semibold uppercase tracking-wider border-b border-sicaps-border">
                        <th class="py-3.5 px-5">Paciente</th>
                        <th class="py-3.5 px-5">Documento</th>
                        <th class="py-3.5 px-5">Contacto</th>
                        <th class="py-3.5 px-5">Estado Clínico</th>
                        <th class="py-3.5 px-5 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sicaps-border/70 text-sm">
                    @forelse($patients as $patient)
                        @php
                            $fullName = ($patient['user']['name'] ?? '') . ' ' . ($patient['user']['lastName'] ?? '');
                            $initials = strtoupper(substr($patient['user']['name'] ?? 'P', 0, 1) . substr($patient['user']['lastName'] ?? 'P', 0, 1));
                            $docType = $patient['user']['documentType'] ?? 'CC';
                            $docNum = $patient['user']['documentNumber'] ?? '';
                            $email = $patient['user']['email'] ?? 'No registrado';
                            $searchQuery = strtolower($fullName . ' ' . $docNum . ' ' . $email);
                        @endphp
                        <tr 
                            x-show="search === '' || '{{ $searchQuery }}'.includes(search.toLowerCase())"
                            class="hover:bg-[#FAF9F6] transition"
                        >
                            <!-- Paciente -->
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-sicaps-primary-soft text-sicaps-primary font-bold text-sm flex items-center justify-center flex-shrink-0 border border-sicaps-primary/20">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <a href="{{ route('patients.show', $patient['id'] ?? 0) }}" class="font-bold text-sicaps-text hover:text-sicaps-primary transition block">
                                            {{ $fullName ?: 'Paciente Sin Nombre' }}
                                        </a>
                                        <span class="text-xs text-sicaps-muted">Expediente Clínico #{{ $patient['id'] ?? 0 }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Documento -->
                            <td class="py-4 px-5">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-semibold bg-sicaps-sand-light text-sicaps-text border border-sicaps-border">
                                    <span class="text-sicaps-muted">{{ $docType }}</span>
                                    <span>{{ $docNum }}</span>
                                </span>
                            </td>

                            <!-- Contacto -->
                            <td class="py-4 px-5">
                                <p class="text-xs text-sicaps-text font-medium">{{ $email }}</p>
                                <p class="text-xs text-sicaps-muted">{{ $patient['user']['phone'] ?? 'Sin teléfono registrado' }}</p>
                            </td>

                            <!-- Estado Clínico -->
                            <td class="py-4 px-5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-sicaps-success-soft text-sicaps-success">
                                    <span class="w-1.5 h-1.5 rounded-full bg-sicaps-success"></span>
                                    Tratamiento Activo
                                </span>
                            </td>

                            <!-- Acciones -->
                            <td class="py-4 px-5 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a 
                                        href="{{ route('patients.show', $patient['id'] ?? 0) }}" 
                                        class="px-3 py-1.5 rounded-[8px] bg-sicaps-primary-soft text-sicaps-primary hover:bg-sicaps-primary hover:text-white text-xs font-semibold transition flex items-center gap-1"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                                        <span>Abrir Historia</span>
                                    </a>

                                    <a 
                                        href="{{ route('clinical.evolutions.create', $patient['id'] ?? 0) }}" 
                                        class="px-3 py-1.5 rounded-[8px] bg-white border border-sicaps-border hover:bg-sicaps-sand-light text-sicaps-text text-xs font-medium transition"
                                        title="Registrar nueva sesión SOAP"
                                    >
                                        + Evolución
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 px-4 text-center">
                                <div class="max-w-sm mx-auto flex flex-col items-center">
                                    <div class="w-14 h-14 rounded-full bg-sicaps-primary-soft text-sicaps-primary flex items-center justify-center mb-4">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-bold text-sicaps-text">No hay expedientes clínicos disponibles</h3>
                                    <p class="text-xs text-sicaps-muted mt-1 mb-5">
                                        Primero debes registrar un paciente en el sistema para habilitar su historia clínica.
                                    </p>
                                    <a 
                                        href="{{ route('patients.create') }}" 
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-[10px] bg-sicaps-primary text-white text-xs font-semibold hover:bg-sicaps-primary-hover transition"
                                    >
                                        + Registrar Paciente
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
