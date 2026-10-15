@extends('layouts.app')

@section('title', 'Panel Principal — SICAPS')
@section('header_title', 'Panel de Control Clínico')

@section('content')
@php
    $todayAppointments = $todayAppointments ?? [];
    $appointments = $appointments ?? [];
    $patients = $patients ?? [];

    $hour = (int) date('H');
    $greeting = ($hour >= 5 && $hour < 12) ? 'Buenos días' : (($hour >= 12 && $hour < 19) ? 'Buenas tardes' : 'Buenas noches');
    $userName = !empty($name) && $name !== 'Usuario' ? $name : session('user_name', 'Profesional');
    
    $totalToday = count($todayAppointments);
    $totalPatients = count($patients);
    $totalAppointments = count($appointments);
    
    // Identificar citas completadas de la base de datos
    $completedAppointments = array_values(array_filter($appointments, function($a) {
        $st = strtoupper($a['status'] ?? '');
        return in_array($st, ['COMPLETADA', 'COMPLETED']);
    }));
@endphp

<div class="space-y-8" x-data="{ filterType: 'all' }">
    
    <!-- SALUDO Y ENCABEZADO SUPERIOR -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-2">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-sicaps-text flex items-center gap-2">
                <span>{{ $greeting }}, {{ $userName }}</span>
                <span class="inline-block">👋</span>
            </h1>
            <p class="mt-1 text-sm text-sicaps-muted">
                Hoy es {{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}. Información en tiempo real de tu consulta.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a 
                href="{{ route('appointments.create') }}" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-[10px] bg-sicaps-primary hover:bg-sicaps-primary-hover active:scale-[0.98] text-white font-medium text-sm shadow-sm transition"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                <span>Nueva Cita</span>
            </a>
            
            <a 
                href="{{ route('patients.create') }}" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-[10px] bg-white border border-sicaps-border hover:bg-sicaps-sand-light active:scale-[0.98] text-sicaps-text font-medium text-sm shadow-soft transition"
            >
                <svg class="w-4 h-4 text-sicaps-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.765Z"/>
                </svg>
                <span>Nuevo Paciente</span>
            </a>
        </div>
    </div>

    <!-- 4 TARJETAS DE MÉTRICAS 100% REALES -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        
        <!-- Tarjeta 1: Citas de Hoy -->
        <div class="bg-white rounded-card p-5 border border-sicaps-border shadow-soft flex flex-col justify-between hover:border-sicaps-primary/40 transition">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-sicaps-muted">Citas de Hoy</span>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-sicaps-text">{{ $totalToday }}</span>
                        <span class="text-xs text-sicaps-muted">para hoy</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-sicaps-primary-soft text-sicaps-primary flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.253M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5"/>
                    </svg>
                </div>
            </div>
            
            <div class="mt-4 pt-3 border-t border-sicaps-border/60 flex items-center justify-between text-xs">
                @if($totalToday > 0)
                    <span class="text-sicaps-primary font-medium">{{ $totalToday }} {{ $totalToday == 1 ? 'sesión programada' : 'sesiones programadas' }}</span>
                @else
                    <span class="text-sicaps-muted">Sin citas agendadas hoy</span>
                @endif
                <a href="{{ route('appointments.index') }}" class="text-xs text-sicaps-primary hover:underline">Ver agenda</a>
            </div>
        </div>

        <!-- Tarjeta 2: Pacientes Registrados -->
        <div class="bg-white rounded-card p-5 border border-sicaps-border shadow-soft flex flex-col justify-between hover:border-sicaps-primary/40 transition">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-sicaps-muted">Pacientes Registrados</span>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-sicaps-text">{{ $totalPatients }}</span>
                        <span class="text-xs text-sicaps-muted">en base de datos</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-sicaps-success-soft text-sicaps-success flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                    </svg>
                </div>
            </div>
            
            <div class="mt-4 pt-3 border-t border-sicaps-border/60 flex items-center justify-between text-xs">
                @if($totalPatients > 0)
                    <span class="text-sicaps-success font-medium">{{ $totalPatients }} {{ $totalPatients == 1 ? 'expediente clínico' : 'expedientes clínicos' }}</span>
                @else
                    <span class="text-sicaps-muted">No hay pacientes aún</span>
                @endif
                <a href="{{ route('patients.index') }}" class="text-xs text-sicaps-primary hover:underline">Directorio</a>
            </div>
        </div>

        <!-- Tarjeta 3: Total Citas en Sistema -->
        <div class="bg-white rounded-card p-5 border border-sicaps-border shadow-soft flex flex-col justify-between hover:border-sicaps-primary/40 transition">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-sicaps-muted">Total Citas en Sistema</span>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-sicaps-text">{{ $totalAppointments }}</span>
                        <span class="text-xs text-sicaps-muted">histórico total</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-sicaps-warning-soft text-sicaps-warning flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                    </svg>
                </div>
            </div>
            
            <div class="mt-4 pt-3 border-t border-sicaps-border/60 flex items-center justify-between text-xs">
                @if($totalAppointments > 0)
                    <span class="text-sicaps-warning font-medium">{{ count($completedAppointments) }} atendidas</span>
                @else
                    <span class="text-sicaps-muted">Sin citas registradas</span>
                @endif
                <a href="{{ route('appointments.index') }}" class="text-xs text-sicaps-primary hover:underline">Gestionar</a>
            </div>
        </div>

        <!-- Tarjeta 4: Rol Asignado -->
        <div class="bg-white rounded-card p-5 border border-sicaps-border shadow-soft flex flex-col justify-between hover:border-sicaps-primary/40 transition">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-sicaps-muted">Perfil de Acceso</span>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-lg font-bold text-sicaps-text">{{ str_replace('ROLE_', '', session('user_role', 'ADMIN')) }}</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-sicaps-info-soft text-sicaps-info flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                    </svg>
                </div>
            </div>
            
            <div class="mt-4 pt-3 border-t border-sicaps-border/60 flex items-center justify-between text-xs">
                <span class="text-sicaps-muted">Permisos completos</span>
                <span class="inline-flex items-center gap-1 font-medium text-sicaps-info bg-sicaps-info-soft px-2 py-0.5 rounded-full">
                    Activo
                </span>
            </div>
        </div>

    </div>

    <!-- DISTRIBUCIÓN PRINCIPAL (COLUMNA IZQUIERDA: AGENDA DE HOY | COLUMNA DERECHA: ACCESOS Y NOTAS) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- COLUMNA IZQUIERDA: AGENDA DE HOY (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-card border border-sicaps-border shadow-soft overflow-hidden">
            
            <div class="p-6 border-b border-sicaps-border flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2.5">
                        <h2 class="text-lg font-bold text-sicaps-text">Agenda de Hoy</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sicaps-primary-soft text-sicaps-primary">
                            {{ $totalToday }} {{ $totalToday == 1 ? 'cita programada' : 'citas programadas' }}
                        </span>
                    </div>
                    <p class="text-xs text-sicaps-muted mt-1">
                        Citas que corresponden a la fecha de hoy ({{ date('d/m/Y') }}).
                    </p>
                </div>

                @if($totalToday > 0)
                <div class="inline-flex p-1 bg-sicaps-bg rounded-lg border border-sicaps-border text-xs font-medium">
                    <button 
                        @click="filterType = 'all'" 
                        :class="filterType === 'all' ? 'bg-white text-sicaps-text shadow-sm' : 'text-sicaps-muted hover:text-sicaps-text'"
                        class="px-3 py-1.5 rounded-md transition"
                    >
                        Todas
                    </button>
                    <button 
                        @click="filterType = 'presencial'" 
                        :class="filterType === 'presencial' ? 'bg-white text-sicaps-text shadow-sm' : 'text-sicaps-muted hover:text-sicaps-text'"
                        class="px-3 py-1.5 rounded-md transition"
                    >
                        Presenciales
                    </button>
                    <button 
                        @click="filterType = 'virtual'" 
                        :class="filterType === 'virtual' ? 'bg-white text-sicaps-text shadow-sm' : 'text-sicaps-muted hover:text-sicaps-text'"
                        class="px-3 py-1.5 rounded-md transition"
                    >
                        Virtuales
                    </button>
                </div>
                @endif
            </div>

            <!-- Listado REAL de Citas de Hoy -->
            <div class="divide-y divide-sicaps-border/70">
                
                @forelse($todayAppointments as $apt)
                    @php
                        $pName = ($apt['patient']['user']['name'] ?? '') . ' ' . ($apt['patient']['user']['lastName'] ?? '');
                        $initials = strtoupper(substr($apt['patient']['user']['name'] ?? 'P', 0, 1) . substr($apt['patient']['user']['lastName'] ?? 'P', 0, 1));
                        $isVirtual = str_contains(strtolower($apt['observations'] ?? ''), 'virtual') || str_contains(strtolower($apt['reason'] ?? ''), 'virtual');
                        $itemType = $isVirtual ? 'virtual' : 'presencial';
                    @endphp
                    <div 
                        x-show="filterType === 'all' || filterType === '{{ $itemType }}'"
                        class="p-5 hover:bg-[#FAF9F6] transition flex flex-col sm:flex-row sm:items-center justify-between gap-4"
                    >
                        <div class="flex items-start gap-4">
                            <!-- Hora -->
                            <div class="w-16 flex-shrink-0 text-left sm:text-right">
                                <span class="block text-sm font-bold text-sicaps-text">{{ substr($apt['time'], 0, 5) }}</span>
                                <span class="text-[11px] text-sicaps-muted">45 min</span>
                            </div>

                            <div class="hidden sm:block w-px self-stretch bg-sicaps-border"></div>

                            <!-- Paciente -->
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-sicaps-primary-soft text-sicaps-primary font-bold text-sm flex items-center justify-center flex-shrink-0 border border-sicaps-primary/20">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-sm font-bold text-sicaps-text hover:text-sicaps-primary transition">
                                            <a href="{{ route('patients.show', $apt['patient']['id'] ?? 0) }}">
                                                {{ $pName ?: 'Paciente Registrado' }}
                                            </a>
                                        </h3>
                                        @if($isVirtual)
                                            <span class="inline-flex items-center gap-1 text-[11px] font-medium text-sicaps-info bg-sicaps-info-soft px-2 py-0.5 rounded-full">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                                                Virtual
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-[11px] font-medium text-sicaps-muted bg-sicaps-sand-light px-2 py-0.5 rounded-full">
                                                <svg class="w-3 h-3 text-sicaps-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                                Presencial
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-sicaps-muted mt-0.5">{{ $apt['reason'] }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Estado y Acciones Rápidas -->
                        <div class="flex items-center gap-3 sm:self-center pl-20 sm:pl-0">
                            @if(in_array($apt['status'], ['COMPLETADA', 'COMPLETED']))
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-sicaps-success-soft text-sicaps-success">
                                    Atendida
                                </span>
                            @elseif(in_array($apt['status'], ['CANCELADA', 'CANCELLED']))
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-sicaps-danger-soft text-sicaps-danger">
                                    Cancelada
                                </span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-sicaps-primary-soft text-sicaps-primary">
                                    Programada
                                </span>
                            @endif

                            <a 
                                href="{{ route('patients.show', $apt['patient']['id'] ?? 0) }}" 
                                class="text-xs font-medium text-sicaps-primary hover:text-sicaps-primary-hover px-3 py-1.5 rounded-md hover:bg-sicaps-primary-soft transition"
                            >
                                Ficha →
                            </a>
                        </div>
                    </div>
                @empty
                    <!-- Estado vacío REAL: Cuando no hay citas agendadas hoy -->
                    <div class="py-16 px-6 text-center">
                        <div class="max-w-sm mx-auto flex flex-col items-center">
                            <div class="w-14 h-14 rounded-full bg-sicaps-primary-soft text-sicaps-primary flex items-center justify-center mb-4">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.253M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5"/>
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-sicaps-text">No tienes citas programadas para hoy</h3>
                            <p class="text-xs text-sicaps-muted mt-1.5 mb-5 leading-relaxed">
                                Tu agenda de hoy está despejada. Puedes agendar una nueva consulta o revisar las citas futuras en el módulo general.
                            </p>
                            <a 
                                href="{{ route('appointments.create') }}" 
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-[10px] bg-sicaps-primary text-white text-xs font-semibold hover:bg-sicaps-primary-hover transition"
                            >
                                + Agendar Nueva Cita
                            </a>
                        </div>
                    </div>
                @endforelse

            </div>

            @if($totalAppointments > 0)
            <div class="p-4 bg-sicaps-sand-light/40 border-t border-sicaps-border text-center">
                <a href="{{ route('appointments.index') }}" class="text-xs font-semibold text-sicaps-primary hover:text-sicaps-primary-hover hover:underline transition">
                    Ver agenda completa con todas las citas ({{ $totalAppointments }}) →
                </a>
            </div>
            @endif

        </div>

        <!-- COLUMNA DERECHA: ACCESOS RÁPIDOS, NOTAS PENDIENTES Y SEGUIMIENTO (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- ACCESOS RÁPIDOS REALES -->
            <div class="bg-white rounded-card p-5 border border-sicaps-border shadow-soft">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-sicaps-muted mb-4">
                    Accesos Rápidos
                </h3>
                
                <div class="grid grid-cols-1 gap-2.5">
                    <a 
                        href="{{ route('appointments.create') }}" 
                        class="flex items-center justify-between p-3 rounded-[10px] border border-sicaps-border hover:border-sicaps-primary/40 hover:bg-sicaps-primary-soft/30 transition group"
                    >
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-sicaps-primary-soft text-sicaps-primary flex items-center justify-center group-hover:scale-105 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            </div>
                            <div>
                                <span class="text-sm font-bold text-sicaps-text block group-hover:text-sicaps-primary transition">Agendar Cita</span>
                                <span class="text-[11px] text-sicaps-muted">Presencial o teleconsulta</span>
                            </div>
                        </div>
                        <span class="text-xs text-sicaps-muted group-hover:translate-x-1 transition">→</span>
                    </a>

                    <a 
                        href="{{ route('patients.create') }}" 
                        class="flex items-center justify-between p-3 rounded-[10px] border border-sicaps-border hover:border-sicaps-primary/40 hover:bg-sicaps-primary-soft/30 transition group"
                    >
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-sicaps-success-soft text-sicaps-success flex items-center justify-center group-hover:scale-105 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.765Z"/></svg>
                            </div>
                            <div>
                                <span class="text-sm font-bold text-sicaps-text block group-hover:text-sicaps-primary transition">Nuevo Paciente</span>
                                <span class="text-[11px] text-sicaps-muted">Crear ficha y antecedentes</span>
                            </div>
                        </div>
                        <span class="text-xs text-sicaps-muted group-hover:translate-x-1 transition">→</span>
                    </a>

                    <a 
                        href="{{ route('clinical.index') }}" 
                        class="flex items-center justify-between p-3 rounded-[10px] border border-sicaps-border hover:border-sicaps-primary/40 hover:bg-sicaps-primary-soft/30 transition group"
                    >
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-sicaps-warning-soft text-sicaps-warning flex items-center justify-center group-hover:scale-105 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                            </div>
                            <div>
                                <span class="text-sm font-bold text-sicaps-text block group-hover:text-sicaps-primary transition">Historias Clínicas</span>
                                <span class="text-[11px] text-sicaps-muted">Expedientes y evoluciones SOAP</span>
                            </div>
                        </div>
                        <span class="text-xs text-sicaps-muted group-hover:translate-x-1 transition">→</span>
                    </a>

                    @if(session('user_role') === 'ROLE_ADMIN')
                    <a 
                        href="{{ route('professionals.create') }}" 
                        class="flex items-center justify-between p-3 rounded-[10px] border border-sicaps-primary/30 bg-sicaps-primary-soft/40 hover:bg-sicaps-primary-soft/80 transition group"
                    >
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-sicaps-primary text-white flex items-center justify-center group-hover:scale-105 transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            </div>
                            <div>
                                <span class="text-sm font-bold text-sicaps-text block group-hover:text-sicaps-primary transition">Habilitar Profesional</span>
                                <span class="text-[11px] text-sicaps-muted">Alta y credenciales de especialista</span>
                            </div>
                        </div>
                        <span class="text-xs text-sicaps-primary group-hover:translate-x-1 transition font-bold">→</span>
                    </a>
                    @endif
                </div>
            </div>

            <!-- NOTAS DE EVOLUCIÓN PENDIENTES (REAL) -->
            <div class="bg-white rounded-card p-5 border border-sicaps-border shadow-soft">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-sicaps-muted">
                        Notas Pendientes (SOAP)
                    </h3>
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-sicaps-sand-light text-sicaps-muted">
                        {{ count($completedAppointments) }} atendidas
                    </span>
                </div>

                @if(count($completedAppointments) > 0)
                    <div class="space-y-3">
                        @foreach(array_slice($completedAppointments, 0, 3) as $cApp)
                            @php
                                $cpName = ($cApp['patient']['user']['name'] ?? '') . ' ' . ($cApp['patient']['user']['lastName'] ?? '');
                            @endphp
                            <div class="p-3 rounded-lg bg-sicaps-sand-light/40 border border-sicaps-border/70">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="text-sm font-bold text-sicaps-text">{{ $cpName ?: 'Paciente Atendido' }}</h4>
                                        <p class="text-xs text-sicaps-muted">{{ $cApp['date'] }} • {{ substr($cApp['time'], 0, 5) }}</p>
                                    </div>
                                    <span class="text-[11px] text-sicaps-primary font-semibold">Atendida</span>
                                </div>
                                <div class="mt-2.5 flex justify-end">
                                    <a href="{{ route('clinical.evolutions.create', $cApp['patient']['id'] ?? 0) }}" class="text-xs font-semibold text-sicaps-primary hover:text-sicaps-primary-hover hover:underline">
                                        Redactar evolución SOAP →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 rounded-lg bg-sicaps-bg border border-sicaps-border/70 text-center">
                        <p class="text-xs text-sicaps-muted leading-relaxed">
                            No tienes notas clínicas pendientes por redactar en este momento.
                        </p>
                    </div>
                @endif
            </div>

            <!-- CONTINUIDAD Y BIENESTAR (REAL) -->
            <div class="bg-white rounded-card p-5 border border-sicaps-border shadow-soft">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-sicaps-muted">
                        Continuidad y Bienestar
                    </h3>
                    <span class="text-[11px] text-sicaps-muted font-medium">Seguimiento</span>
                </div>

                @if($totalPatients > 0)
                    <p class="text-xs text-sicaps-muted mb-3 leading-relaxed">
                        Pacientes en seguimiento en el centro:
                    </p>
                    <div class="space-y-2">
                        @foreach(array_slice($patients, 0, 3) as $pat)
                            @php
                                $patName = ($pat['user']['name'] ?? '') . ' ' . ($pat['user']['lastName'] ?? '');
                                $patDoc = ($pat['user']['documentType'] ?? 'CC') . ': ' . ($pat['user']['documentNumber'] ?? '');
                            @endphp
                            <div class="flex items-center justify-between py-2 border-b border-sicaps-border/50 text-xs">
                                <div>
                                    <span class="font-bold text-sicaps-text block">{{ $patName }}</span>
                                    <span class="text-sicaps-muted text-[11px]">{{ $patDoc }}</span>
                                </div>
                                <a href="{{ route('patients.show', $pat['id'] ?? 0) }}" class="text-xs font-medium text-sicaps-primary hover:text-sicaps-primary-hover">
                                    Ver Ficha
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 rounded-lg bg-sicaps-bg border border-sicaps-border/70 text-center">
                        <p class="text-xs text-sicaps-muted leading-relaxed">
                            Todos tus pacientes se encuentran con seguimiento al día.
                        </p>
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
