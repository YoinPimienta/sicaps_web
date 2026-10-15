<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SICAPS Web')</title>
    <link rel="icon" href="data:,">
    <link rel="preconnect" href="https://cdn.tailwindcss.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 flex min-h-screen">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md border-r border-gray-200 flex flex-col">
        <div class="p-6 border-b border-gray-100">
            <h1 class="text-2xl font-bold text-blue-900">SICAPS</h1>
            <p class="text-sm text-gray-500 mt-1">Gestión Clínica</p>
        </div>
        
        <nav class="flex-1 mt-6 px-4 space-y-2">
            <a href="{{ route('dashboard') }}" class="block py-2 px-4 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }} rounded-md">Dashboard</a>
            
            <a href="{{ route('patients.index') }}" class="block py-2 px-4 {{ request()->routeIs('patients.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }} rounded-md">Pacientes</a>
            
            <a href="{{ route('appointments.index') }}" class="block py-2 px-4 {{ request()->routeIs('appointments.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }} rounded-md">Citas</a>
            
            <a href="{{ route('clinical.index') }}" class="block py-2 px-4 {{ request()->routeIs('clinical.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }} rounded-md">Historias Clínicas</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <!-- Topbar -->
        <header class="bg-white shadow-sm border-b border-gray-200 p-4 flex justify-between items-center z-10">
            <h2 class="text-xl font-semibold text-gray-800">@yield('header_title', 'Panel Principal')</h2>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600">Hola, <strong>{{ session('user_name', 'Usuario') }}</strong></span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">Cerrar Sesión</button>
                </form>
            </div>
        </header>

        <!-- Page Content -->
        <div class="flex-1 overflow-auto p-6">
            @if(session('success'))
                <div class="bg-green-50 text-green-700 p-4 rounded-md mb-6 border border-green-200">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 text-red-700 p-4 rounded-md mb-6 border border-red-200">
                    {{ session('error') }}
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>

</body>
</html>
