@extends('layouts.app')

@section('title', 'Equipo Clínico y Profesionales — SICAPS')

@section('header_title')
    <div>
        <h2 class="text-xl font-bold text-sicaps-text tracking-tight flex items-center gap-2">
            <span>Equipo Clínico y Especialistas</span>
            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-sicaps-primary-soft text-sicaps-primary border border-sicaps-primary/20">
                {{ count($professionals) }} {{ count($professionals) === 1 ? 'profesional' : 'profesionales' }}
            </span>
        </h2>
        <p class="text-xs text-sicaps-muted mt-0.5">
            Directorio de psicólogos y terapeutas con habilitación sanitaria legal en el centro de atención.
        </p>
    </div>
@endsection

@section('content')
<div class="space-y-6" x-data="{
    search: '',
    selectedSpecialty: 'all',
    get filteredProfessionals() {
        return {{ Js::from($professionals) }}.filter(p => {
            const user = p.user || {};
            const fullName = `${user.name || ''} ${user.lastName || ''}`.toLowerCase();
            const doc = `${user.documentNumber || ''}`.toLowerCase();
            const reg = `${p.medicalRegistrationNumber || ''}`.toLowerCase();
            const spec = `${p.specialty || ''}`.toLowerCase();
            const query = this.search.toLowerCase().trim();

            const matchesSearch = !query || fullName.includes(query) || doc.includes(query) || reg.includes(query) || spec.includes(query);
            const matchesSpec = this.selectedSpecialty === 'all' || spec.includes(this.selectedSpecialty.toLowerCase());
            return matchesSearch && matchesSpec;
        });
    }
}">

    <!-- TARJETAS DE INDICADORES RÁPIDOS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        
        <!-- Total Profesionales -->
        <div class="bg-white rounded-card p-5 border border-sicaps-border shadow-soft flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-sicaps-primary-soft text-sicaps-primary flex items-center justify-center flex-shrink-0 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </div>
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-sicaps-muted">Cuerpo Profesional</span>
                <p class="text-2xl font-bold text-sicaps-text leading-tight mt-0.5">{{ count($professionals) }}</p>
                <span class="text-[11px] text-sicaps-success font-medium flex items-center gap-1 mt-0.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-sicaps-success"></span>
                    100% Habilitados en salud
                </span>
            </div>
        </div>

        <!-- Cobertura y Especialidades -->
        <div class="bg-white rounded-card p-5 border border-sicaps-border shadow-soft flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-sicaps-sand-light text-sicaps-primary flex items-center justify-center flex-shrink-0 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-sicaps-muted">Especialidades</span>
                <p class="text-2xl font-bold text-sicaps-text leading-tight mt-0.5">
                    {{ count(array_unique(array_filter(array_map(fn($p) => $p['specialty'] ?? null, $professionals)))) ?: 1 }}
                </p>
                <span class="text-[11px] text-sicaps-muted mt-0.5 block">Enfoques clínicos activos</span>
            </div>
        </div>

        <!-- Marco Legal y ReTHUS -->
        <div class="bg-white rounded-card p-5 border border-sicaps-border shadow-soft flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-sicaps-warning-soft text-sicaps-warning flex items-center justify-center flex-shrink-0 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                </svg>
            </div>
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-sicaps-muted">Habilitación Sanitaria</span>
                <p class="text-2xl font-bold text-sicaps-text leading-tight mt-0.5">Vigente</p>
                <span class="text-[11px] text-sicaps-muted mt-0.5 block">Código Deontológico de Salud</span>
            </div>
        </div>

    </div>

    <!-- BARRA DE BÚSQUEDA, FILTROS Y ACCIÓN DE CREAR -->
    <div class="bg-white rounded-card p-4 sm:p-5 border border-sicaps-border shadow-soft flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        <!-- Buscador en tiempo real -->
        <div class="relative flex-1 max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-sicaps-muted">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
            </div>
            <input 
                type="text" 
                x-model="search"
                placeholder="Buscar por nombre, tarjeta profesional, documento o especialidad..."
                class="w-full pl-10 pr-4 py-2 bg-sicaps-bg/60 border border-sicaps-border rounded-xl text-xs sm:text-sm text-sicaps-text placeholder-sicaps-muted/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
            >
        </div>

        <!-- Filtro y Botón de Acción -->
        <div class="flex items-center gap-3">
            
            <select 
                x-model="selectedSpecialty"
                class="py-2 px-3 bg-white border border-sicaps-border rounded-xl text-xs font-medium text-sicaps-text focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
            >
                <option value="all">Todas las especialidades</option>
                <option value="Clínica">Psicología Clínica</option>
                <option value="Neuropsicología">Neuropsicología</option>
                <option value="Infantil">Infanto-Juvenil</option>
                <option value="Cognitivo">Cognitivo-Conductual</option>
                <option value="Salud">Psicología de la Salud</option>
            </select>

            <!-- Botón exclusivo para Administrador -->
            @if(session('user_role') === 'ROLE_ADMIN')
                <a 
                    href="{{ route('professionals.create') }}" 
                    class="py-2.5 px-4 bg-sicaps-primary hover:bg-sicaps-primary-hover active:scale-[0.99] text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm transition flex items-center gap-2 flex-shrink-0"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    <span>Nuevo Profesional</span>
                </a>
            @endif

        </div>

    </div>

    <!-- LISTADO DE PROFESIONALES -->
    <div class="bg-white rounded-card border border-sicaps-border shadow-soft overflow-hidden">
        
        <!-- VISTA TABLA (DESKTOP) -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-sicaps-bg/50 border-b border-sicaps-border text-sicaps-muted uppercase tracking-wider font-semibold text-[11px]">
                        <th class="py-3.5 px-5">Profesional</th>
                        <th class="py-3.5 px-4">Acreditación Legal / ReTHUS</th>
                        <th class="py-3.5 px-4">Especialidad</th>
                        <th class="py-3.5 px-4">Contacto Institucional</th>
                        <th class="py-3.5 px-4">Estado</th>
                        <th class="py-3.5 px-5 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sicaps-border/60">
                    
                    <template x-for="p in filteredProfessionals" :key="p.id">
                        <tr class="hover:bg-sicaps-sand-light/30 transition-colors group">
                            
                            <!-- Nombre y Documento -->
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-sicaps-primary-soft text-sicaps-primary font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-inner">
                                        <span x-text="((p.user?.name || 'P')[0] + (p.user?.lastName || '')[0]).toUpperCase()"></span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-sicaps-text text-sm group-hover:text-sicaps-primary transition-colors" x-text="`${p.user?.name || ''} ${p.user?.lastName || ''}`"></p>
                                        <p class="text-[11px] text-sicaps-muted" x-text="`${p.user?.documentType || 'CC'}: ${p.user?.documentNumber || '—'}`"></p>
                                    </div>
                                </div>
                            </td>

                            <!-- Tarjeta Profesional / ReTHUS -->
                            <td class="py-4 px-4">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-sicaps-sand-light/60 border border-sicaps-border text-sicaps-text font-mono text-[11px]">
                                    <svg class="w-3.5 h-3.5 text-sicaps-primary flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                    <span class="font-semibold" x-text="p.medicalRegistrationNumber || 'En trámite'"></span>
                                </div>
                            </td>

                            <!-- Especialidad -->
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-sicaps-primary-soft/60 text-sicaps-primary" x-text="p.specialty || 'Psicología General'"></span>
                            </td>

                            <!-- Contacto -->
                            <td class="py-4 px-4 text-sicaps-muted">
                                <p class="text-xs text-sicaps-text font-medium" x-text="p.user?.email || '—'"></p>
                                <p class="text-[11px]" x-text="p.user?.phone ? `Tel: ${p.user?.phone}` : 'Sin teléfono'"></p>
                            </td>

                            <!-- Estado de Habilitación -->
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-sicaps-success-soft text-sicaps-success">
                                    <span class="w-1.5 h-1.5 rounded-full bg-sicaps-success animate-pulse"></span>
                                    Habilitado
                                </span>
                            </td>

                            <!-- Acciones -->
                            <td class="py-4 px-5 text-right">
                                <a 
                                    :href="`{{ url('professionals') }}/${p.id}`" 
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-sicaps-border hover:border-sicaps-primary hover:text-sicaps-primary hover:bg-sicaps-primary-soft/30 text-xs font-semibold text-sicaps-muted transition"
                                >
                                    <span>Ficha</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>

                        </tr>
                    </template>

                </tbody>
            </table>
        </div>

        <!-- ESTADO VACÍO (EMPTY STATE AMABLE) -->
        <div x-show="filteredProfessionals.length === 0" class="py-12 px-6 text-center">
            <div class="w-14 h-14 rounded-2xl bg-sicaps-primary-soft text-sicaps-primary flex items-center justify-center mx-auto mb-3 shadow-sm">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>
            </div>
            <h3 class="text-sm font-bold text-sicaps-text">No se encontraron profesionales</h3>
            <p class="text-xs text-sicaps-muted max-w-sm mx-auto mt-1">
                No hay profesionales clínicos que coincidan con los criterios de búsqueda o aún no se han registrado especialistas en el centro.
            </p>
            @if(session('user_role') === 'ROLE_ADMIN')
                <div class="mt-4">
                    <a 
                        href="{{ route('professionals.create') }}" 
                        class="inline-flex items-center gap-1.5 py-2 px-4 bg-sicaps-primary hover:bg-sicaps-primary-hover text-white text-xs font-semibold rounded-xl shadow-sm transition"
                    >
                        <span>Registrar primer profesional</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    </a>
                </div>
            @endif
        </div>

    </div>

    <!-- NOTA DE CUMPLIMIENTO LEGAL EN SALUD MENTAL -->
    <div class="p-4 rounded-xl bg-sicaps-sand-light/50 border border-sicaps-border/70 flex items-start gap-3 text-xs text-sicaps-muted">
        <svg class="w-5 h-5 text-sicaps-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
        </svg>
        <div class="leading-relaxed">
            <span class="font-semibold text-sicaps-text">Marco Normativo y Habilitación Sanitaria:</span>
            Conforme a las leyes de ejercicio profesional y de salud mental (Ley 1090 de 2006 / Código Deontológico y Bioético de Psicología), todo acto clínico, diagnóstico y nota de evolución SOAP registrada en SICAPS queda suscrita bajo el número de tarjeta profesional y responsabilidad del especialista a cargo.
        </div>
    </div>

</div>
@endsection
