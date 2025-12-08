<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header Principal -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold text-[#0C1C3C] mb-2">Gestión de Usuarios</h1>
                    <p class="text-gray-600">Administra los usuarios y permisos del sistema CCISUR - TI</p>
                </div>
                
                <a href="{{ route('admin.users.create') }}"
                    class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#C5A049] to-[#D8B96E] text-[#0C1C3C] font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-[1.02] group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nuevo Usuario
                </a>
            </div>
        </div>

        <!-- Panel de Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-[#0C1C3C] to-[#1A2A4F] text-white p-6 rounded-2xl shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-300">Total Usuarios</p>
                        <p class="text-3xl font-bold mt-2">{{ $users->count() }}</p>
                    </div>
                    <div class="p-3 bg-white/10 rounded-xl">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 0H21"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 p-6 rounded-2xl shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Administradores TI</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2">
                            {{ $users->filter(fn($u) => $u->hasRole('admin_ti'))->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-xl">
                        <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            

        </div>

        <!-- Tabla de Usuarios -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-[#0C1C3C] to-[#1A2A4F] text-white">
                            <th class="p-4 text-left font-semibold text-sm">Usuario</th>
                            <th class="p-4 text-left font-semibold text-sm">Información de Contacto</th>
                            <th class="p-4 text-left font-semibold text-sm">Rol y Estado</th>
                            <th class="p-4 text-left font-semibold text-sm">Registro</th>
                            <th class="p-4 text-left font-semibold text-sm">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($users as $user)
                            @php
                                $roles = $user->roles->pluck('name')->toArray();
                                $isAdmin = in_array('admin_ti', $roles);
                                $isActive = $user->is_active ?? true;
                            @endphp
                            
                            <tr class="hover:bg-gray-50/80 transition-colors duration-200">
                                <!-- Información del Usuario -->
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#C5A049] to-[#D8B96E] flex items-center justify-center text-white font-bold text-lg shadow-md">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">{{ $user->name }}</h3>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-sm {{ $isActive ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $isActive ? '● Activo' : '● Inactivo' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Contacto -->
                                <td class="p-4">
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            <a href="mailto:{{ $user->email }}" class="text-sm text-gray-600 hover:text-[#C5A049] transition-colors">
                                                {{ $user->email }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Rol y Estado -->
                                <td class="p-4">
                                    <div class="space-y-2">
                                        <!-- Roles -->
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($roles as $role)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                    @if($role == 'admin_ti') bg-red-100 text-red-800
                                                    @elseif($role == 'soporte') bg-blue-100 text-blue-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $role)) }}
                                                </span>
                                            @endforeach
                                            @if(empty($roles))
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Sin rol asignado
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Registro -->
                                <td class="p-4">
                                    <div class="text-sm text-gray-600">
                                        <div class="font-medium">{{ $user->created_at->format('d/m/Y') }}</div>
                                        <div>{{ $user->created_at->diffForHumans() }}</div>
                                    </div>
                                </td>
                                
                                <!-- Acciones -->
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <!-- Editar -->
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           class="inline-flex items-center gap-1 px-4 py-2 bg-gradient-to-r from-[#0C1C3C] to-[#1A2A4F] text-white text-sm font-medium rounded-xl hover:shadow-md transition-shadow duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Editar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <!-- Estado vacío -->
                @if($users->isEmpty())
                    <div class="py-16 text-center">
                        <div class="w-20 h-20 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 0H21"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay usuarios registrados</h3>
                        <p class="text-gray-600 mb-6">Comienza agregando el primer usuario al sistema.</p>
                        <a href="{{ route('admin.users.create') }}"
                            class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#C5A049] to-[#D8B96E] text-[#0C1C3C] font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Agregar primer usuario</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>