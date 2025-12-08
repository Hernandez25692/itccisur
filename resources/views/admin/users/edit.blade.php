<x-app-layout>
    <style>
        :root {
            --primary-dark: #0C1C3C;
            --primary-medium: #1A2A4F;
            --gold-primary: #C5A049;
            --gold-light: #D4B56C;
            --success: #10B981;
            --danger: #EF4444;
            --glass-bg: rgba(255, 255, 255, 0.95);
        }

        .form-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-card {
            width: 100%;
            max-width: 500px;
            background: var(--glass-bg);
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.9);
            overflow: hidden;
        }

        .form-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-medium) 100%);
            padding: 1.5rem 2rem;
            border-bottom: 3px solid var(--gold-primary);
        }

        .form-title {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .form-subtitle {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.9rem;
        }

        .form-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            color: #374151;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-input, .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            color: #1f2937;
            background: white;
            transition: all 0.2s;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--gold-primary);
            box-shadow: 0 0 0 3px rgba(197, 160, 73, 0.15);
        }

        .toggle-container {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1rem;
            margin: 1.5rem 0;
        }

        .toggle-label {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            font-weight: 600;
            color: #4b5563;
        }

        .toggle-checkbox {
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid #9ca3af;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .toggle-checkbox:checked {
            background-color: var(--gold-primary);
            border-color: var(--gold-primary);
        }

        .password-container {
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .btn-update {
            flex: 1;
            padding: 0.875rem 1.5rem;
            background: linear-gradient(135deg, var(--primary-medium) 0%, var(--primary-dark) 100%);
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(12, 28, 60, 0.2);
        }

        .btn-cancel {
            padding: 0.875rem 1.5rem;
            background: white;
            color: #6b7280;
            font-weight: 600;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-cancel:hover {
            background: #f9fafb;
            border-color: var(--gold-light);
            color: var(--gold-primary);
        }

        @media (max-width: 640px) {
            .form-container {
                padding: 1rem;
            }
            .form-body {
                padding: 1.5rem;
            }
            .form-actions {
                flex-direction: column;
            }
        }
    </style>

    <div class="form-container">
        <div class="form-card">
            <div class="form-header">
                <h1 class="form-title">Editar Usuario</h1>
                <p class="form-subtitle">Actualizar información del usuario del sistema</p>
            </div>

            <div class="form-body">
                <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                               class="form-input" required placeholder="Nombre completo">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Correo Institucional</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                               class="form-input" required placeholder="usuario@ccisur.org">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Rol de Usuario</label>
                        <select name="role" class="form-select">
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" 
                                        {{ $user->roles->first()->name == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="toggle-container">
                        <label class="toggle-label">
                            <input type="checkbox" id="togglePassword" class="toggle-checkbox">
                            ¿Cambiar contraseña?
                        </label>
                    </div>

                    <div id="passwordContainer" class="password-container" style="height: 0; opacity: 0;">
                        <div class="form-group">
                            <label class="form-label">Nueva Contraseña</label>
                            <input type="password" name="password" class="form-input" 
                                   placeholder="Mínimo 8 caracteres">
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('admin.users.index') }}" class="btn-cancel">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-update">
                            Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const toggle = document.getElementById('togglePassword');
        const container = document.getElementById('passwordContainer');

        toggle.addEventListener('change', () => {
            if (toggle.checked) {
                container.style.height = container.scrollHeight + 'px';
                container.style.opacity = '1';
                container.style.marginBottom = '1.5rem';
            } else {
                container.style.height = '0';
                container.style.opacity = '0';
                container.style.marginBottom = '0';
            }
        });
    </script>
</x-app-layout>