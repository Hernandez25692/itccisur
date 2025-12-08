<x-app-layout>
    <style>
        :root {
            --primary-dark: #0C1C3C;
            --primary-medium: #1A2A4F;
            --primary-light: #2A3A6F;
            --gold-primary: #C5A049;
            --gold-light: #D4B56C;
            --gold-dark: #B59038;
            --text-dark: #1F2937;
            --text-medium: #4B5563;
            --text-light: #6B7280;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --info: #3B82F6;
            --glass-bg: rgba(255, 255, 255, 0.95);
            --shadow-subtle: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --shadow-medium: 0 10px 15px -3px rgba(0, 0, 0, 0.07), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --border-radius: 12px;
        }

        .user-form-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-form-card {
            width: 100%;
            max-width: 500px;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-medium);
            border: 1px solid rgba(255, 255, 255, 0.9);
            overflow: hidden;
        }

        .form-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-medium) 100%);
            padding: 1.75rem 2rem;
            position: relative;
        }

        .form-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--gold-primary) 0%, var(--gold-light) 100%);
        }

        .form-title {
            color: white;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .form-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
            font-weight: 400;
        }

        .form-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.75rem;
        }

        .form-label {
            display: block;
            color: var(--text-dark);
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            letter-spacing: 0.025em;
        }

        .form-label-required::after {
            content: ' *';
            color: var(--danger);
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            color: var(--text-dark);
            background: white;
            transition: all 0.2s ease;
            box-shadow: var(--shadow-subtle);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--gold-primary);
            box-shadow: 0 0 0 3px rgba(197, 160, 73, 0.1);
            transform: translateY(-1px);
        }

        .form-input:hover:not(:focus) {
            border-color: #9ca3af;
        }

        .form-select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            color: var(--text-dark);
            background: white;
            transition: all 0.2s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%234B5563'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.25rem;
            box-shadow: var(--shadow-subtle);
        }

        .form-select:focus {
            outline: none;
            border-color: var(--gold-primary);
            box-shadow: 0 0 0 3px rgba(197, 160, 73, 0.1);
            transform: translateY(-1px);
        }

        .form-select:hover:not(:focus) {
            border-color: #9ca3af;
        }

        .form-helper {
            display: block;
            margin-top: 0.375rem;
            font-size: 0.875rem;
            color: var(--text-light);
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .btn-submit {
            flex: 1;
            padding: 0.875rem 1.5rem;
            background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-light) 100%);
            color: white;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            letter-spacing: 0.025em;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(197, 160, 73, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-cancel {
            padding: 0.875rem 1.5rem;
            background: white;
            color: var(--text-medium);
            font-weight: 600;
            font-size: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-cancel:hover {
            background: #f9fafb;
            border-color: var(--gold-light);
            color: var(--gold-primary);
        }

        /* Iconos para inputs */
        .input-with-icon {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            pointer-events: none;
        }

        .input-with-icon .form-input,
        .input-with-icon .form-select {
            padding-left: 2.75rem;
        }

        /* Password strength indicator */
        .password-strength {
            margin-top: 0.5rem;
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            overflow: hidden;
            position: relative;
        }

        .password-strength-bar {
            height: 100%;
            width: 0%;
            background: var(--danger);
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .password-strength-text {
            font-size: 0.75rem;
            margin-top: 0.25rem;
            color: var(--text-light);
        }

        /* Responsive */
        @media (max-width: 640px) {
            .user-form-container {
                padding: 1rem;
            }
            
            .form-body {
                padding: 1.5rem;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn-submit,
            .btn-cancel {
                width: 100%;
            }
        }

        /* Animaciones */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            animation: fadeIn 0.3s ease forwards;
            animation-delay: calc(var(--i, 0) * 0.1s);
        }
    </style>

    <div class="user-form-container">
        <div class="user-form-card">
            <div class="form-header">
                <h1 class="form-title">Nuevo Usuario</h1>
                <p class="form-subtitle">Crear cuenta en el sistema CCISUR TI</p>
            </div>

            <div class="form-body">
                <form method="POST" action="{{ route('admin.users.store') }}" id="userForm">
                    @csrf

                    <div class="form-group" style="--i: 0;">
                        <label class="form-label form-label-required">Nombre Completo</label>
                        <div class="input-with-icon">
                            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <input 
                                type="text" 
                                name="name" 
                                class="form-input" 
                                required 
                                placeholder="Ej: Juan Pérez"
                                value="{{ old('name') }}"
                            >
                        </div>
                        @error('name')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" style="--i: 1;">
                        <label class="form-label form-label-required">Correo Institucional</label>
                        <div class="input-with-icon">
                            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <input 
                                type="email" 
                                name="email" 
                                class="form-input" 
                                required 
                                placeholder="usuario@ccisur.org"
                                value="{{ old('email') }}"
                            >
                        </div>
                        <span class="form-helper">Utilice el dominio institucional @ccisur.org</span>
                        @error('email')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" style="--i: 2;">
                        <label class="form-label form-label-required">Contraseña</label>
                        <div class="input-with-icon">
                            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <input 
                                type="password" 
                                name="password" 
                                id="password"
                                class="form-input" 
                                required 
                                placeholder="Mínimo 8 caracteres"
                                minlength="8"
                            >
                        </div>
                        <div class="password-strength">
                            <div class="password-strength-bar" id="passwordStrengthBar"></div>
                        </div>
                        <div class="password-strength-text" id="passwordStrengthText">
                            La contraseña debe tener al menos 8 caracteres
                        </div>
                        @error('password')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" style="--i: 3;">
                        <label class="form-label form-label-required">Confirmar Contraseña</label>
                        <div class="input-with-icon">
                            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                id="confirmPassword"
                                class="form-input" 
                                required 
                                placeholder="Repita la contraseña"
                            >
                        </div>
                        <div id="passwordMatch" class="form-helper"></div>
                    </div>

                    <div class="form-group" style="--i: 4;">
                        <label class="form-label form-label-required">Rol de Usuario</label>
                        <div class="input-with-icon">
                            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <select name="role" class="form-select" required>
                                <option value="" disabled selected>Seleccione un rol</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <span class="form-helper">Asigne los permisos adecuados según las funciones</span>
                        @error('role')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('admin.users.index') }}" class="btn-cancel">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Cancelar
                        </a>
                        <button type="submit" class="btn-submit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Crear Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Validación de fortaleza de contraseña
        const passwordInput = document.getElementById('password');
        const passwordStrengthBar = document.getElementById('passwordStrengthBar');
        const passwordStrengthText = document.getElementById('passwordStrengthText');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const passwordMatch = document.getElementById('passwordMatch');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let text = '';

            // Longitud
            if (password.length >= 8) strength += 25;
            
            // Minúsculas y mayúsculas
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25;
            
            // Números
            if (/\d/.test(password)) strength += 25;
            
            // Caracteres especiales
            if (/[^A-Za-z0-9]/.test(password)) strength += 25;

            // Actualizar barra
            passwordStrengthBar.style.width = strength + '%';

            // Actualizar texto y color
            if (strength < 50) {
                passwordStrengthBar.style.background = '#EF4444';
                text = 'Contraseña débil';
            } else if (strength < 75) {
                passwordStrengthBar.style.background = '#F59E0B';
                text = 'Contraseña media';
            } else {
                passwordStrengthBar.style.background = '#10B981';
                text = 'Contraseña fuerte';
            }

            passwordStrengthText.textContent = text;
            passwordStrengthText.style.color = passwordStrengthBar.style.background;
        });

        // Validación de coincidencia de contraseñas
        function checkPasswordMatch() {
            if (passwordInput.value && confirmPasswordInput.value) {
                if (passwordInput.value === confirmPasswordInput.value) {
                    passwordMatch.textContent = '✓ Las contraseñas coinciden';
                    passwordMatch.style.color = '#10B981';
                    confirmPasswordInput.style.borderColor = '#10B981';
                } else {
                    passwordMatch.textContent = '✗ Las contraseñas no coinciden';
                    passwordMatch.style.color = '#EF4444';
                    confirmPasswordInput.style.borderColor = '#EF4444';
                }
            } else {
                passwordMatch.textContent = '';
                confirmPasswordInput.style.borderColor = '#e5e7eb';
            }
        }

        passwordInput.addEventListener('input', checkPasswordMatch);
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);

        // Validación de email institucional
        const emailInput = document.querySelector('input[name="email"]');
        emailInput.addEventListener('blur', function() {
            const email = this.value;
            if (email && !email.endsWith('@ccisur.org')) {
                const helper = this.parentElement.nextElementSibling;
                if (helper && helper.classList.contains('form-helper')) {
                    helper.style.color = '#F59E0B';
                    helper.textContent = 'Se recomienda utilizar el dominio institucional @ccisur.org';
                }
            }
        });

        // Validación del formulario
        document.getElementById('userForm').addEventListener('submit', function(e) {
            let valid = true;
            
            // Validar contraseñas
            if (passwordInput.value !== confirmPasswordInput.value) {
                e.preventDefault();
                passwordMatch.textContent = '✗ Las contraseñas deben coincidir';
                passwordMatch.style.color = '#EF4444';
                confirmPasswordInput.focus();
                valid = false;
            }
            
            // Validar longitud de contraseña
            if (passwordInput.value.length < 8) {
                e.preventDefault();
                passwordStrengthText.textContent = '✗ La contraseña debe tener al menos 8 caracteres';
                passwordStrengthText.style.color = '#EF4444';
                passwordInput.focus();
                valid = false;
            }

            if (!valid) {
                // Agregar animación de error
                const inputs = document.querySelectorAll('.form-input, .form-select');
                inputs.forEach(input => {
                    if (!input.value || (input.name === 'password' && input.value.length < 8)) {
                        input.style.animation = 'shake 0.5s ease';
                        setTimeout(() => {
                            input.style.animation = '';
                        }, 500);
                    }
                });
            }
        });

        // Animación de shake para errores
        const style = document.createElement('style');
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                20%, 40%, 60%, 80% { transform: translateX(5px); }
            }
        `;
        document.head.appendChild(style);
    </script>
</x-app-layout>