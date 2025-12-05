<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Gestión de Usuarios</h1>

        <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg mb-4 inline-block">
            + Nuevo Usuario
        </a>

        <table class="w-full border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Nombre</th>
                    <th class="p-2 border">Email</th>
                    <th class="p-2 border">Rol</th>
                    <th class="p-2 border">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $u)
                    <tr>
                        <td class="p-2 border">{{ $u->name }}</td>
                        <td class="p-2 border">{{ $u->email }}</td>
                        <td class="p-2 border">{{ $u->roles->pluck('name')->join(', ') }}</td>
                        <td class="p-2 border">
                            <a href="{{ route('admin.users.edit', $u) }}" class="text-blue-600">Editar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
