@extends('layouts.app')

@section('title', 'Citas y Agenda — SICAPS')
@section('header_title', 'Gestión de Citas y Agenda')

@section('content')
<div class="space-y-6" x-data="{ currentView: 'list', statusFilter: 'all', search: '' }">
    
    <!-- ENCABEZADO Y ACCIONES -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-bold tracking-tight text-sicaps-text">Agenda de Consultas</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sicaps-primary-soft text-sicaps-primary">
                    {{ count($appointments ?? []) }} citas registradas
                </span>
            </div>
            <p class="text-sm text-sicaps-muted mt-1">
                Monitorea tus sesiones de atención psicológica, confirma asistencias y registra evoluciones.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Alternador de Vista (Lista / Semanal) -->
            <div class="inline-flex p-1 bg-white rounded-lg border border-sicaps-border text-xs font-medium shadow-soft">
                <button 
                    @click="currentView = 'list'" 
                    :class="currentView === 'list' ? 'bg-sicaps-primary text-white shadow-sm' : 'text-sicaps-muted hover:text-sicaps-text'"
                    class="px-3 py-1.5 rounded-md transition flex items-center gap-1.5"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                    <span>Lista</span>
                </button>
                <button 
                    @click="currentView = 'calendar'" 
                    :class="currentView === 'calendar' ? 'bg-sicaps-primary text-white shadow-sm' : 'text-sicaps-muted hover:text-sicaps-text'"
                    class="px-3 py-1.5 rounded-md transition flex items-center gap-1.5"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.253M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5"/></svg>
                    <span>Semanal</span>
                </button>
            </div>

            <!-- Botón Agendar Cita -->
            <a 
                href="{{ route('appointments.create') }}" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-[10px] bg-sicaps-primary hover:bg-sicaps-primary-hover active:scale-[0.98] text-white font-medium text-sm shadow-sm transition"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                <span>Agendar Cita</span>
            </a>
        </div>
    </div>

    <!-- BARRA DE FILTROS Y ESTADOS -->
    <div class="bg-white rounded-card border border-sicaps-border shadow-soft p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        
        <!-- Filtros por estado -->
        <div class="flex flex-wrap items-center gap-2 text-xs">
            <span class="text-sicaps-muted font-semibold mr-1">Estado:</span>
            <button 
                @click="statusFilter = 'all'" 
                :class="statusFilter === 'all' ? 'bg-sicaps-primary text-white' : 'bg-sicaps-sand-light text-sicaps-text hover:bg-sicaps-sand'"
                class="px-3 py-1.5 rounded-full font-medium transition"
            >
                Todas
            </button>
            <button 
                @click="statusFilter = 'programada'" 
                :class="statusFilter === 'programada' ? 'bg-sicaps-primary text-white' : 'bg-sicaps-primary-soft text-sicaps-primary hover:bg-sicaps-primary/20'"
                class="px-3 py-1.5 rounded-full font-medium transition"
            >
                Programadas
            </button>
            <button 
                @click="statusFilter = 'completada'" 
                :class="statusFilter === 'completada' ? 'bg-sicaps-success text-white' : 'bg-sicaps-success-soft text-sicaps-success hover:bg-sicaps-success/20'"
                class="px-3 py-1.5 rounded-full font-medium transition"
            >
                Completadas
            </button>
            <button 
                @click="statusFilter = 'cancelada'" 
                :class="statusFilter === 'cancelada' ? 'bg-sicaps-danger text-white' : 'bg-sicaps-danger-soft text-sicaps-danger hover:bg-sicaps-danger/20'"
                class="px-3 py-1.5 rounded-full font-medium transition"
            >
                Canceladas
            </button>
        </div>

        <!-- Buscador rápido -->
        <div class="relative w-full sm:w-64">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-sicaps-muted">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
            </div>
            <input 
                type="text" 
                x-model="search"
                placeholder="Buscar paciente o motivo..." 
                class="w-full pl-9 pr-3 py-1.5 bg-sicaps-bg border border-sicaps-border rounded-[8px] text-xs text-sicaps-text placeholder-sicaps-muted/60 focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
            >
        </div>
    </div>

    <!-- VISTA 1: LISTA TABULAR DETALLADA -->
    <div x-show="currentView === 'list'" class="bg-white rounded-card border border-sicaps-border shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-sicaps-sand-light/50 text-sicaps-muted text-xs font-semibold uppercase tracking-wider border-b border-sicaps-border">
                        <th class="py-3.5 px-5">Fecha y Hora</th>
                        <th class="py-3.5 px-5">Paciente</th>
                        <th class="py-3.5 px-5">Especialista</th>
                        <th class="py-3.5 px-5">Motivo de Consulta</th>
                        <th class="py-3.5 px-5">Estado</th>
                        <th class="py-3.5 px-5 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sicaps-border/70 text-sm">
                    @forelse($appointments as $apt)
                        @php
                            $st = strtoupper($apt['status'] ?? 'PROGRAMADA');
                            $normStatus = in_array($st, ['PROGRAMADA', 'SCHEDULED']) ? 'programada' : (in_array($st, ['COMPLETADA', 'COMPLETED']) ? 'completada' : 'cancelada');
                            $pName = ($apt['patient']['user']['name'] ?? '') . ' ' . ($apt['patient']['user']['lastName'] ?? '');
                            $initials = strtoupper(substr($apt['patient']['user']['name'] ?? 'P', 0, 1) . substr($apt['patient']['user']['lastName'] ?? 'P', 0, 1));
                            $profName = ($apt['professional']['user']['name'] ?? '') . ' ' . ($apt['professional']['user']['lastName'] ?? '');
                            $searchQuery = strtolower($pName . ' ' . $profName . ' ' . ($apt['reason'] ?? ''));
                        @endphp
                        <tr 
                            x-show="(statusFilter === 'all' || statusFilter === '{{ $normStatus }}') && (search === '' || '{{ $searchQuery }}'.includes(search.toLowerCase()))"
                            class="hover:bg-[#FAF9F6] transition"
                        >
                            <!-- Fecha y Hora -->
                            <td class="py-4 px-5">
                                <div class="space-y-0.5">
                                    <span class="font-bold text-sicaps-text block">
                                        {{ \Carbon\Carbon::parse($apt['date'])->locale('es')->isoFormat('D MMM YYYY') }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 text-xs text-sicaps-muted bg-sicaps-sand-light/70 px-2 py-0.5 rounded-md">
                                        <svg class="w-3 h-3 text-sicaps-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                        {{ substr($apt['time'], 0, 5) }} (45 min)
                                    </span>
                                </div>
                            </td>

                            <!-- Paciente -->
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-sicaps-primary-soft text-sicaps-primary font-bold text-xs flex items-center justify-center flex-shrink-0 border border-sicaps-primary/20">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <a href="{{ route('patients.show', $apt['patient']['id'] ?? 0) }}" class="font-bold text-sicaps-text hover:text-sicaps-primary transition block">
                                            {{ $pName ?: 'Paciente Registrado' }}
                                        </a>
                                        <span class="text-xs text-sicaps-muted">Expediente #{{ $apt['patient']['id'] ?? 0 }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Profesional -->
                            <td class="py-4 px-5">
                                <span class="text-xs font-medium text-sicaps-text block">{{ $profName ?: 'Dra. Camila Santos' }}</span>
                                <span class="text-[11px] text-sicaps-muted">Psicología Clínica</span>
                            </td>

                            <!-- Motivo de Consulta -->
                            <td class="py-4 px-5">
                                <div class="max-w-xs">
                                    <p class="text-xs font-medium text-sicaps-text truncate">{{ $apt['reason'] ?? 'Consulta general' }}</p>
                                    @if(!empty($apt['observations']))
                                        <p class="text-[11px] text-sicaps-muted truncate mt-0.5">{{ $apt['observations'] }}</p>
                                    @endif
                                </div>
                            </td>

                            <!-- Estado -->
                            <td class="py-4 px-5">
                                @if($normStatus === 'programada')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-sicaps-primary-soft text-sicaps-primary">
                                        <span class="w-1.5 h-1.5 rounded-full bg-sicaps-primary animate-pulse"></span>
                                        Programada
                                    </span>
                                @elseif($normStatus === 'completada')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-sicaps-success-soft text-sicaps-success">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                        Completada
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-sicaps-danger-soft text-sicaps-danger">
                                        Cancelada
                                    </span>
                                @endif
                            </td>

                            <!-- Acciones -->
                            <td class="py-4 px-5 text-right">
                                <div class="inline-flex items-center gap-2">
                                    @if($normStatus === 'programada')
                                        <!-- Completar Cita -->
                                        <form action="{{ route('appointments.updateStatus', $apt['id']) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Marcar esta cita como completada? Podrás redactar la nota de evolución de inmediato.');">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="COMPLETADA">
                                            <button 
                                                type="submit" 
                                                class="px-2.5 py-1.5 rounded-[8px] bg-sicaps-success-soft text-sicaps-success hover:bg-sicaps-success hover:text-white text-xs font-semibold transition"
                                                title="Completar cita"
                                            >
                                                ✓ Atendida
                                            </button>
                                        </form>

                                        <!-- Cancelar Cita -->
                                        <form action="{{ route('appointments.updateStatus', $apt['id']) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Confirmas que deseas cancelar esta cita?');">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="CANCELADA">
                                            <button 
                                                type="submit" 
                                                class="p-1.5 text-sicaps-muted hover:text-sicaps-danger hover:bg-sicaps-danger-soft rounded-md transition"
                                                title="Cancelar cita"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @elseif($normStatus === 'completada')
                                        <a 
                                            href="{{ route('clinical.evolutions.create', $apt['patient']['id'] ?? 0) }}" 
                                            class="text-xs font-semibold text-sicaps-primary hover:text-sicaps-primary-hover hover:underline"
                                        >
                                            Evolución SOAP →
                                        </a>
                                    @else
                                        <span class="text-xs text-sicaps-muted italic">Archivada</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 px-4 text-center">
                                <div class="max-w-sm mx-auto flex flex-col items-center">
                                    <div class="w-14 h-14 rounded-full bg-sicaps-primary-soft text-sicaps-primary flex items-center justify-center mb-4">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.253M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-bold text-sicaps-text">No hay citas en la agenda</h3>
                                    <p class="text-xs text-sicaps-muted mt-1 mb-5">
                                        Programa citas de valoración inicial o de seguimiento para tus pacientes.
                                    </p>
                                    <a 
                                        href="{{ route('appointments.create') }}" 
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-[10px] bg-sicaps-primary text-white text-xs font-semibold hover:bg-sicaps-primary-hover transition"
                                    >
                                        + Agendar Nueva Cita
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- VISTA 2: CALENDARIO SEMANAL INTERACTIVO -->
    <div x-show="currentView === 'calendar'" x-cloak class="bg-white rounded-card border border-sicaps-border shadow-soft p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-base font-bold text-sicaps-text">Semana en Curso</h3>
                <p class="text-xs text-sicaps-muted">Visualización semanal por bloques horarios</p>
            </div>
            <span class="text-xs font-semibold text-sicaps-primary bg-sicaps-primary-soft px-3 py-1 rounded-full">
                Consultorios 1 y 2 habilitados
            </span>
        </div>

        @php
            $startOfWeek = \Carbon\Carbon::now()->startOfWeek();
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            @for($i = 0; $i < 5; $i++)
                @php
                    $currentDay = $startOfWeek->copy()->addDays($i);
                    $dayDateStr = $currentDay->format('Y-m-d');
                    $dayAppointments = array_values(array_filter($appointments ?? [], function($a) use ($dayDateStr) {
                        return ($a['date'] ?? '') === $dayDateStr;
                    }));
                @endphp
                <div class="bg-sicaps-bg rounded-xl border border-sicaps-border p-3.5 flex flex-col min-h-[320px]">
                    <div class="flex items-center justify-between pb-2 mb-3 border-b border-sicaps-border">
                        <span class="text-xs font-bold uppercase tracking-wider text-sicaps-text capitalize">
                            {{ $currentDay->locale('es')->isoFormat('dddd') }}
                        </span>
                        <span class="text-[11px] text-sicaps-muted font-medium">
                            {{ $currentDay->locale('es')->isoFormat('D MMM') }}
                        </span>
                    </div>

                    <div class="space-y-2.5 flex-1">
                        @forelse($dayAppointments as $dApt)
                            @php
                                $dpName = ($dApt['patient']['user']['name'] ?? '') . ' ' . ($dApt['patient']['user']['lastName'] ?? '');
                                $dIsVirtual = str_contains(strtolower($dApt['observations'] ?? ''), 'virtual') || str_contains(strtolower($dApt['reason'] ?? ''), 'virtual');
                            @endphp
                            <div class="p-2.5 rounded-lg bg-white border border-sicaps-border/80 shadow-xs hover:border-sicaps-primary transition">
                                <span class="text-[11px] font-bold text-sicaps-primary block">
                                    {{ substr($dApt['time'], 0, 5) }} {{ $dIsVirtual ? '(Virtual)' : '' }}
                                </span>
                                <span class="text-xs font-bold text-sicaps-text block mt-0.5 truncate">{{ $dpName ?: 'Paciente' }}</span>
                                <span class="text-[10px] text-sicaps-muted block truncate">{{ $dApt['reason'] }}</span>
                            </div>
                        @empty
                            <div class="h-full flex items-center justify-center text-[11px] text-sicaps-muted/60 italic py-8 text-center">
                                Sin citas este día
                            </div>
                        @endforelse
                    </div>
                </div>
            @endfor
        </div>
    </div>

</div>
@endsection
