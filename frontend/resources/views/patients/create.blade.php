@extends('layouts.app')

@section('title', 'Nuevo Paciente - SICAPS Web')
@section('header_title', 'Registrar Nuevo Paciente')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 max-w-3xl">
    <form action="{{ route('patients.store') }}" method="POST" class="space-y-4">
        @csrf
        
        <h3 class="text-lg font-medium text-gray-800 border-b pb-2">Datos Personales</h3>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Apellido</label>
                <input type="text" name="lastName" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tipo Documento</label>
                <select name="documentType" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="CC">Cédula de Ciudadanía</option>
                    <option value="TI">Tarjeta de Identidad</option>
                    <option value="CE">Cédula de Extranjería</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Número de Documento</label>
                <input type="text" name="documentNumber" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                <input type="email" name="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                <input type="text" name="phone" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
        </div>
        
        <h3 class="text-lg font-medium text-gray-800 border-b pb-2 mt-6">Contacto de Emergencia</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nombre Contacto</label>
                <input type="text" name="emergencyContactName" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Teléfono Contacto</label>
                <input type="text" name="emergencyContactPhone" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
        </div>

        <div class="pt-4 flex justify-end space-x-2">
            <a href="{{ route('patients.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">Cancelar</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Guardar Paciente</button>
        </div>
    </form>
</div>
@endsection
