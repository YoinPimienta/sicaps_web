@extends('layouts.app')

@section('title', 'Pacientes — SICAPS')
@section('header_title', 'Directorio de Pacientes')

@section('content')
<div class="space-y-6" x-data="{ search: '' }">
    
    <!-- ENCABEZADO Y ACCIONES -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-bold tracking-tight text-sicaps-text">Directorio Clínico de Pacientes</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sicaps-primary-soft text-sicaps-primary">
                    {{ count($patients ?? []) }} registrados
                </span>
            </div>
            <p class="text-sm text-sicaps-muted mt-1">
                Consulta expedientes, antecedentes clínicos y datos de contacto de cada paciente.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a 
                href="{{ route('patients.create') }}" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-[10px] bg-sicaps-primary hover:bg-sicaps-primary-hover active:scale-[0.98] text-white font-medium text-sm shadow-sm transition"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                <span>Nuevo Paciente</span>
            </a>
        </div>
    </div>

    <!-- TARJETA PRINCIPAL CON FILTRO Y TABLA -->
    <div class="bg-white rounded-card border border-sicaps-border shadow-soft overflow-hidden">
        
        <!-- Barra de herramientas: Búsqueda y Filtros -->
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
                    placeholder="Buscar por nombre, documento o correo..." 
                    class="w-full pl-10 pr-4 py-2 bg-white border border-sicaps-border rounded-[10px] text-xs sm:text-sm text-sicaps-text placeholder-sicaps-muted/60 focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                >
            </div>

            <div class="text-xs text-sicaps-muted flex items-center gap-2">
                <span class="inline-block w-2 h-2 rounded-full bg-sicaps-success"></span>
                <span>Base de datos confidencial y sincronizada</span>
            </div>
        </div>

        <!-- Tabla de Pacientes -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-sicaps-sand-light/50 text-sicaps-muted text-xs font-semibold uppercase tracking-wider border-b border-sicaps-border">
                        <th class="py-3.5 px-5">Paciente</th>
                        <th class="py-3.5 px-5">Documento</th>
                        <th class="py-3.5 px-5">Contacto</th>
                        <th class="py-3.5 px-5">Contacto Emergencia</th>
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
                            $phone = $patient['user']['phone'] ?? 'Sin teléfono';
                            $emContact = $patient['emergencyContactName'] ?? 'No registrado';
                            $emPhone = $patient['emergencyContactPhone'] ?? '';
                            $searchHaystack = strtolower($fullName . ' ' . $docNum . ' ' . $email . ' ' . $phone);
                        @endphp
                        <tr 
                            x-show="search === '' || '{{ $searchHaystack }}'.includes(search.toLowerCase())"
                            class="hover:bg-[#FAF9F6] transition"
                        >
                            <!-- Paciente y Avatar -->
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-sicaps-primary-soft text-sicaps-primary font-bold text-sm flex items-center justify-center flex-shrink-0 border border-sicaps-primary/20">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <a href="{{ route('patients.show', $patient['id'] ?? 0) }}" class="font-bold text-sicaps-text hover:text-sicaps-primary transition block">
                                            {{ $fullName ?: 'Paciente Sin Nombre' }}
                                        </a>
                                        <span class="text-xs text-sicaps-muted">Expediente #{{ $patient['id'] ?? 0 }}</span>
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
                                <div class="space-y-0.5">
                                    <p class="text-xs text-sicaps-text font-medium">{{ $email }}</p>
                                    <p class="text-xs text-sicaps-muted">{{ $phone }}</p>
                                </div>
                            </td>

                            <!-- Contacto de Emergencia -->
                            <td class="py-4 px-5">
                                <div class="space-y-0.5">
                                    <p class="text-xs text-sicaps-text font-medium">{{ $emContact }}</p>
                                    @if(!empty($emPhone))
                                        <p class="text-[11px] text-sicaps-muted">{{ $emPhone }}</p>
                                    @endif
                                </div>
                            </td>

                            <!-- Acciones -->
                            <td class="py-4 px-5 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a 
                                        href="{{ route('patients.show', $patient['id'] ?? 0) }}" 
                                        class="px-3 py-1.5 rounded-[8px] bg-sicaps-primary-soft text-sicaps-primary hover:bg-sicaps-primary hover:text-white text-xs font-semibold transition"
                                    >
                                        Ver Ficha
                                    </a>

                                    @if(session('user_role') === 'ROLE_ADMIN' || session('user_role') === 'ROLE_PROFESSIONAL')
                                        <a 
                                            href="{{ route('patients.edit', $patient['id'] ?? 0) }}" 
                                            class="p-1.5 text-sicaps-muted hover:text-sicaps-text hover:bg-sicaps-sand-light rounded-md transition"
                                            title="Editar información"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                                            </svg>
                                        </a>

                                        <form action="{{ route('patients.destroy', $patient['id'] ?? 0) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Confirmas que deseas archivar/eliminar este paciente? Esta acción requiere autorización clínica.');">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit" 
                                                class="p-1.5 text-sicaps-muted hover:text-sicaps-danger hover:bg-sicaps-danger-soft rounded-md transition"
                                                title="Eliminar paciente"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 px-4 text-center">
                                <div class="max-w-sm mx-auto flex flex-col items-center">
                                    <div class="w-14 h-14 rounded-full bg-sicaps-primary-soft text-sicaps-primary flex items-center justify-center mb-4">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-bold text-sicaps-text">No hay pacientes registrados</h3>
                                    <p class="text-xs text-sicaps-muted mt-1 mb-5">
                                        Registra a tu primer paciente para comenzar a agendar citas y gestionar historias clínicas.
                                    </p>
                                    <a 
                                        href="{{ route('patients.create') }}" 
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-[10px] bg-sicaps-primary text-white text-xs font-semibold hover:bg-sicaps-primary-hover transition"
                                    >
                                        + Registrar Primer Paciente
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
