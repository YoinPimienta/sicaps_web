<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SICAPS - Sistema de Atención Psicológica')</title>
    <link rel="icon" href="data:,">
    @if(config('app.env') === 'production' || !empty(env('RENDER')) || (request()->header('x-forwarded-proto') === 'https') || str_contains(request()->getHost(), 'onrender.com'))
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endif
    
    <!-- Tipografía: Plus Jakarta Sans & DM Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;1,400&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN con configuración de tokens de diseño SICAPS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sicaps: {
                            bg: '#F7F5F0',
                            surface: '#FFFFFF',
                            border: '#E6E2DA',
                            primary: '#2F7F79',
                            'primary-hover': '#256A65',
                            'primary-soft': '#E3F0EE',
                            sand: '#E9DFD0',
                            'sand-light': '#F5EFE6',
                            text: '#23302F',
                            muted: '#5E6B69',
                            success: '#4C9A72',
                            'success-soft': '#EBF7F1',
                            warning: '#D9A441',
                            'warning-soft': '#FDF7E7',
                            danger: '#C65D5D',
                            'danger-soft': '#FBEBEB',
                            info: '#5B8DB8',
                            'info-soft': '#EDF5FB',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', '"DM Sans"', 'system-ui', '-apple-system', 'sans-serif'],
                        serif: ['"DM Serif Display"', 'Georgia', 'serif'],
                    },
                    borderRadius: {
                        'card': '16px',
                        'input': '10px',
                        'chip': '20px',
                    },
                    boxShadow: {
                        'soft': '0 2px 10px rgba(35, 48, 47, 0.04), 0 1px 3px rgba(35, 48, 47, 0.02)',
                        'card': '0 4px 20px rgba(35, 48, 47, 0.05)',
                        'dropdown': '0 10px 25px -5px rgba(35, 48, 47, 0.08), 0 8px 10px -6px rgba(35, 48, 47, 0.03)',
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        [x-cloak] { display: none !important; }
        body {
            background-color: #F7F5F0;
            color: #23302F;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        /* Scrollbar suave */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #D5CEC2; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #B3AAA0; }
        
        /* Foco accesible */
        a:focus-visible, button:focus-visible, input:focus-visible, select:focus-visible, textarea:focus-visible {
            outline: 2px solid #2F7F79;
            outline-offset: 2px;
        }
    </style>
</head>
<body class="h-full flex overflow-hidden text-sicaps-text bg-sicaps-bg"
      x-data="{ 
          sidebarOpen: false, 
          userMenuOpen: false,
          inactivityWarning: false,
          lastActivity: Date.now(),
          toastOpen: {{ (session('appointment_assigned') || session('success') || session('error')) ? 'true' : 'false' }},
          toastTitle: '{{ session('appointment_assigned') ? 'Nueva Cita Asignada' : (session('success') ? 'Operación Exitosa' : (session('error') ? 'Atención' : '')) }}',
          toastMsg: '{{ session('appointment_assigned') ? session('appointment_assigned') : (session('success') ?? session('error') ?? '') }}',
          toastType: '{{ session('error') ? 'danger' : 'success' }}',
          resetInactivity() {
              this.lastActivity = Date.now();
              this.inactivityWarning = false;
          }
      }"
      @mousemove.window.debounce.500ms="resetInactivity()"
      @keydown.window.debounce.500ms="resetInactivity()"
      @click.window="userMenuOpen = false;"
      @toast.window="toastTitle = $event.detail.title || 'Aviso'; toastMsg = $event.detail.msg; toastType = $event.detail.type || 'success'; toastOpen = true; setTimeout(() => toastOpen = false, 6000);"
      x-init="
          if (toastOpen) { setTimeout(() => toastOpen = false, 6000); }
          setInterval(() => {
              const inactiveTime = Date.now() - lastActivity;
              // Aviso a los 14 minutos (840,000 ms)
              if (inactiveTime > 840000 && !inactivityWarning) {
                  inactivityWarning = true;
              }
              // Cierre automático a los 15 minutos (900,000 ms)
              if (inactiveTime > 900000) {
                  document.getElementById('auto-logout-form').submit();
              }
          }, 10000);
      ">

    <!-- Formulario oculto de logout para inactividad -->
    <form id="auto-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    <!-- BACKDROP PARA MÓVILES -->
    <div x-show="sidebarOpen" 
         x-cloak 
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-sicaps-text/30 backdrop-blur-sm z-40 lg:hidden"
         aria-hidden="true"></div>

    <!-- SIDEBAR (260px) -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 w-[260px] bg-sicaps-surface border-r border-sicaps-border flex flex-col transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 shadow-soft">
        
        <!-- Logo SICAPS -->
        <div class="h-20 px-6 flex items-center justify-between border-b border-sicaps-border/50">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                <!-- Isotipo botánico original y estilizado -->
                <div class="w-10 h-10 rounded-xl bg-sicaps-primary-soft text-sicaps-primary flex items-center justify-center transition-transform group-hover:scale-105 duration-200 shadow-sm">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2a9 9 0 0 1 9 9c0 4.97-4.03 9-9 9A9 9 0 0 1 3 11a9 9 0 0 1 9-9Z" fill="#2F7F79" fill-opacity="0.15"/>
                        <path d="M12 6c-3.31 0-6 2.69-6 6 0 2.5 1.5 4.5 3.5 5.5"/>
                        <path d="M12 6c3.31 0 6 2.69 6 6 0 2.5-1.5 4.5-3.5 5.5"/>
                        <path d="M12 2v20"/>
                        <path d="M8 9l4 3 4-3"/>
                    </svg>
                </div>
                <div>
                    <span class="text-xl font-bold tracking-tight text-sicaps-text font-sans block leading-none">SICAPS</span>
                    <span class="text-[10.5px] font-medium text-sicaps-muted tracking-wide block mt-1">Sistema de Atención Psicológica</span>
                </div>
            </a>
            <!-- Botón cerrar sidebar en móvil -->
            <button @click="sidebarOpen = false" class="lg:hidden text-sicaps-muted hover:text-sicaps-text p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Navegación principal -->
        <nav class="flex-1 py-6 px-3 space-y-1.5 overflow-y-auto" aria-label="Navegación principal">
            
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="relative flex items-center px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-sicaps-primary-soft text-sicaps-primary font-semibold' : 'text-sicaps-muted hover:text-sicaps-text hover:bg-sicaps-sand-light/60' }}">
                @if(request()->routeIs('dashboard'))
                    <span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-sicaps-primary rounded-r-full"></span>
                @endif
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-sicaps-primary' : 'text-sicaps-muted/80' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Pacientes -->
            <a href="{{ route('patients.index') }}" 
               class="relative flex items-center px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('patients.*') ? 'bg-sicaps-primary-soft text-sicaps-primary font-semibold' : 'text-sicaps-muted hover:text-sicaps-text hover:bg-sicaps-sand-light/60' }}">
                @if(request()->routeIs('patients.*'))
                    <span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-sicaps-primary rounded-r-full"></span>
                @endif
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('patients.*') ? 'text-sicaps-primary' : 'text-sicaps-muted/80' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span>Pacientes</span>
            </a>

            <!-- Citas -->
            <a href="{{ route('appointments.index') }}" 
               class="relative flex items-center px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('appointments.*') ? 'bg-sicaps-primary-soft text-sicaps-primary font-semibold' : 'text-sicaps-muted hover:text-sicaps-text hover:bg-sicaps-sand-light/60' }}">
                @if(request()->routeIs('appointments.*'))
                    <span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-sicaps-primary rounded-r-full"></span>
                @endif
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('appointments.*') ? 'text-sicaps-primary' : 'text-sicaps-muted/80' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Citas</span>
            </a>

            <!-- Historias Clínicas -->
            <a href="{{ route('clinical.index') }}" 
               class="relative flex items-center px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('clinical.*') ? 'bg-sicaps-primary-soft text-sicaps-primary font-semibold' : 'text-sicaps-muted hover:text-sicaps-text hover:bg-sicaps-sand-light/60' }}">
                @if(request()->routeIs('clinical.*'))
                    <span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-sicaps-primary rounded-r-full"></span>
                @endif
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('clinical.*') ? 'text-sicaps-primary' : 'text-sicaps-muted/80' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Historias Clínicas</span>
            </a>
        </nav>

        <!-- Bloque inferior: Perfil del profesional y Cerrar sesión discreto -->
        <div class="p-3.5 border-t border-sicaps-border/60 bg-sicaps-surface">
            
            @php
                $userName = session('user_name', 'Profesional');
                $userRole = session('user_role', 'ROLE_PSYCHOLOGIST');
                $roleLabel = ($userRole === 'ROLE_ADMIN') ? 'Administrador' : (($userRole === 'ROLE_PSYCHOLOGIST') ? 'Psicóloga / Especialista' : 'Usuario');
                
                // Iniciales para el avatar
                $words = explode(' ', trim($userName));
                $initials = '';
                foreach ($words as $w) {
                    if (!empty($w)) $initials .= mb_substr($w, 0, 1);
                    if (mb_strlen($initials) >= 2) break;
                }
                $initials = strtoupper($initials ?: 'PS');
            @endphp

            <!-- Tarjeta de Perfil -->
            <div class="flex items-center justify-between p-2.5 rounded-xl hover:bg-sicaps-sand-light/60 transition-colors">
                <div class="flex items-center space-x-3 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-sicaps-sand text-sicaps-primary font-bold flex items-center justify-center text-xs tracking-wider shadow-inner flex-shrink-0">
                        {{ $initials }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-semibold text-sicaps-text truncate">{{ $userName }}</p>
                        <p class="text-[11px] text-sicaps-muted truncate">{{ $roleLabel }}</p>
                    </div>
                </div>
            </div>

            <!-- Botón discreto de Cerrar Sesión (Neutral con icono, no rojo agresivo) -->
            <form action="{{ route('logout') }}" method="POST" class="mt-1">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center px-2.5 py-2 text-xs font-medium text-sicaps-muted hover:text-sicaps-danger hover:bg-sicaps-danger-soft/60 rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2.5 text-sicaps-muted/80" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Cerrar sesión</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- ÁREA PRINCIPAL -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-sicaps-bg">
        
        <!-- TOPBAR -->
        <header class="h-20 bg-sicaps-surface/80 backdrop-blur-md border-b border-sicaps-border/70 px-6 flex items-center justify-between z-10">
            
            <!-- Botón Hamburguesa móvil + Saludo / Título -->
            <div class="flex items-center space-x-4 min-w-0">
                <button @click.stop="sidebarOpen = !sidebarOpen" 
                        class="lg:hidden p-2 rounded-lg text-sicaps-muted hover:text-sicaps-text hover:bg-sicaps-sand-light/60 transition-colors"
                        aria-label="Abrir menú lateral">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <div>
                    @hasSection('header_title')
                        @yield('header_title')
                    @else
                        @php
                            $hour = (int) date('H');
                            $greeting = ($hour >= 5 && $hour < 12) ? 'Buenos días' : (($hour >= 12 && $hour < 19) ? 'Buenas tardes' : 'Buenas noches');
                            
                            // Formato de fecha en español
                            $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                            $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
                            $fechaHoy = $dias[date('w')] . ', ' . date('j') . ' de ' . $meses[date('n') - 1] . ' de ' . date('Y');
                        @endphp
                        <h2 class="text-xl font-bold text-sicaps-text tracking-tight flex items-center gap-2">
                            <span>{{ $greeting }}, {{ $userName }}</span>
                            <span class="text-xl">👋</span>
                        </h2>
                        <p class="text-xs text-sicaps-muted mt-0.5 font-medium capitalize">{{ $fechaHoy }}</p>
                    @endif
                </div>
            </div>

            <!-- Controles superiores: Buscador, Notificaciones y Perfil -->
            <div class="flex items-center space-x-3 sm:space-x-4">
                
                <!-- Buscador global redondeado -->
                <div class="relative hidden md:block w-64 lg:w-80">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-sicaps-muted/70">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" 
                           placeholder="Buscar pacientes, citas, notas..." 
                           class="w-full pl-9 pr-4 py-2 bg-sicaps-sand-light/50 border border-sicaps-border/70 rounded-full text-xs text-sicaps-text placeholder-sicaps-muted/70 focus:bg-white focus:border-sicaps-primary focus:ring-1 focus:ring-sicaps-primary transition-all duration-200">
                </div>



                <!-- Avatar superior con iniciales -->
                <div class="relative" @click.stop>
                    <button @click="userMenuOpen = !userMenuOpen" 
                            class="flex items-center space-x-2 p-1 pl-2 pr-2.5 rounded-full hover:bg-sicaps-sand-light/60 transition-colors border border-transparent hover:border-sicaps-border/60">
                        <div class="w-8 h-8 rounded-full bg-sicaps-sand text-sicaps-primary font-bold text-xs flex items-center justify-center shadow-inner">
                            {{ $initials }}
                        </div>
                        <svg class="w-3.5 h-3.5 text-sicaps-muted hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <!-- Menú desplegable de usuario -->
                    <div x-show="userMenuOpen"
                         x-cloak
                         x-transition
                         class="absolute right-0 mt-2 w-48 bg-sicaps-surface rounded-xl shadow-dropdown border border-sicaps-border py-1 z-50">
                        <div class="px-4 py-2 border-b border-sicaps-border/50">
                            <p class="text-xs font-semibold text-sicaps-text">{{ $userName }}</p>
                            <p class="text-[10px] text-sicaps-muted">{{ $roleLabel }}</p>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-xs text-sicaps-muted hover:text-sicaps-danger hover:bg-sicaps-danger-soft/40 transition-colors flex items-center">
                                <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        <!-- CONTENIDO PRINCIPAL SCROLLABLE -->
        <main class="flex-1 overflow-y-auto px-4 py-6 sm:px-8 sm:py-8">
            <div class="max-w-7xl mx-auto space-y-6">
                
                <!-- Notificaciones Flash (Éxito y Error) -->
                @if(session('success'))
                    <div class="bg-sicaps-success-soft border border-sicaps-success/30 text-sicaps-success px-4 py-3 rounded-xl text-xs sm:text-sm font-medium flex items-center justify-between shadow-sm" role="alert">
                        <div class="flex items-center space-x-2.5">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" @click="$el.parentElement.remove()" class="text-sicaps-success/70 hover:text-sicaps-success">✕</button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-sicaps-danger-soft border border-sicaps-danger/30 text-sicaps-danger px-4 py-3 rounded-xl text-xs sm:text-sm font-medium flex items-center justify-between shadow-sm" role="alert">
                        <div class="flex items-center space-x-2.5">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button type="button" @click="$el.parentElement.remove()" class="text-sicaps-danger/70 hover:text-sicaps-danger">✕</button>
                    </div>
                @endif

                @yield('content')

            </div>
        </main>
    </div>

    <!-- MODAL DE ADVERTENCIA POR INACTIVIDAD (SEGURIDAD EN SALUD MENTAL) -->
    <div x-show="inactivityWarning" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-sicaps-text/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-sicaps-surface rounded-2xl max-w-md w-full p-6 shadow-dropdown border border-sicaps-border text-center">
            <div class="w-12 h-12 bg-sicaps-warning-soft text-sicaps-warning rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                🔒
            </div>
            <h3 class="text-lg font-bold text-sicaps-text">Aviso de Privacidad y Seguridad</h3>
            <p class="text-xs sm:text-sm text-sicaps-muted mt-2">
                Por confidencialidad de la información clínica, tu sesión se cerrará automáticamente en <strong>1 minuto</strong> por inactividad.
            </p>
            <div class="mt-6 flex space-x-3 justify-center">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 border border-sicaps-border text-xs font-semibold rounded-xl text-sicaps-muted hover:bg-sicaps-sand-light transition">
                        Cerrar sesión ahora
                    </button>
                </form>
                <button @click="resetInactivity()" 
                        class="px-5 py-2 bg-sicaps-primary text-white text-xs font-semibold rounded-xl hover:bg-sicaps-primary-hover shadow-sm transition">
                    Continuar conectado
                </button>
            </div>
        </div>
    </div>

    <!-- NOTIFICACIÓN EMERGENTE FLOTANTE (TOAST DISCRETO EN LA PARTE INFERIOR) -->
    <div x-show="toastOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         class="fixed bottom-6 right-6 z-50 max-w-sm w-full sm:w-auto"
         role="status"
         aria-live="polite">
        <div class="bg-white/95 backdrop-blur-md rounded-2xl p-4 shadow-dropdown border border-sicaps-border flex items-start gap-3 text-left">
            <div :class="toastType === 'danger' ? 'bg-sicaps-danger-soft text-sicaps-danger' : 'bg-sicaps-primary-soft text-sicaps-primary'" 
                 class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.253M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5"/>
                </svg>
            </div>
            <div class="flex-1 pr-2">
                <p class="text-xs font-bold text-sicaps-text" x-text="toastTitle || 'Notificación'"></p>
                <p class="text-[11px] text-sicaps-muted mt-0.5 leading-relaxed" x-text="toastMsg"></p>
            </div>
            <button @click="toastOpen = false" class="text-sicaps-muted hover:text-sicaps-text p-1 text-base leading-none font-bold ml-1 transition" title="Cerrar">
                &times;
            </button>
        </div>
    </div>

</body>
</html>
