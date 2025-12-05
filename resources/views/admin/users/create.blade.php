<x-app-layout>
    <div class="max-w-lg mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Nuevo Usuario</h1>

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <label>Nombre</label>
            <input type="text" name="name" class="w-full border p-2 mb-3" required>

            <label>Email</label>
            <input type="email" name="email" class="w-full border p-2 mb-3" required>

            <label>Contraseña</label>
            <input type="password" name="password" class="w-full border p-2 mb-3" required>

            <label>Rol</label>
            <select name="role" class="w-full border p-2 mb-4">
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                @endforeach
            </select>

            <button class="px-4 py-2 bg-green-600 text-white rounded">Guardar</button>
        </form>
    </div>
</x-app-layout>
