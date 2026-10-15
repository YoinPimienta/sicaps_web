@extends('layouts.app')

@section('title', 'Dashboard - SICAPS Web')
@section('header_title', 'Panel Principal')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Widget 1 -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
        <h3 class="text-gray-500 text-sm font-medium">Citas Hoy</h3>
        <p class="text-3xl font-bold text-blue-700 mt-2">0</p>
    </div>
    <!-- Widget 2 -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
        <h3 class="text-gray-500 text-sm font-medium">Pacientes Activos</h3>
        <p class="text-3xl font-bold text-green-600 mt-2">0</p>
    </div>
    <!-- Widget 3 -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
        <h3 class="text-gray-500 text-sm font-medium">Rol Asignado</h3>
        <p class="text-lg font-bold text-gray-700 mt-2">{{ str_replace('ROLE_', '', session('user_role', '')) }}</p>
    </div>
</div>

<div class="mt-8 bg-white p-6 rounded-lg shadow-sm border border-gray-100">
    <h3 class="text-lg font-medium text-gray-800 mb-4">Próximas Atenciones</h3>
    <p class="text-gray-500 text-sm">Aún no hay módulos implementados para mostrar esta información. Utiliza el menú lateral para navegar por los módulos disponibles.</p>
</div>
@endsection
