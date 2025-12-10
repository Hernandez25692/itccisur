<x-app-layout>
    <style>
        :root {
            --primary-dark: #0C1C3C;
            --primary-medium: #1A2A4F;
            --gold-primary: #C5A049;
            --gold-light: #D4B56C;
        }

        .plan-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #f0f4f8 0%, #e6eef7 100%);
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .plan-card {
            width: 100%;
            max-width: 800px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(12, 28, 60, 0.1);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.9);
        }

        .plan-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-medium) 100%);
            padding: 2rem;
            position: relative;
        }

        .plan-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--gold-primary) 0%, var(--gold-light) 100%);
        }

        .plan-title {
            color: white;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .plan-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
        }

        .plan-year {
            display: inline-block;
            background: var(--gold-primary);
            color: white;
            padding: 0.25rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .plan-body {
            padding: 2.5rem;
        }

        .form-group {
            margin-bottom: 2rem;
        }

        .form-label {
            display: block;
            color: var(--primary-dark);
            font-weight: 600;
            margin-bottom: 0.75rem;
            font-size: 1.05rem;
        }

        .form-input, .form-textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            color: #1a202c;
            background: #f8fafc;
            transition: all 0.3s ease;
        }

        .form-input:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--gold-primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(197, 160, 73, 0.15);
        }

        .form-textarea {
            min-height: 150px;
            resize: vertical;
            line-height: 1.5;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #e2e8f0;
        }

        .btn-save {
            flex: 1;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-light) 100%);
            color: white;
            font-weight: 600;
            font-size: 1.05rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(197, 160, 73, 0.3);
        }

        .btn-cancel {
            flex: 1;
            padding: 1rem 2rem;
            background: white;
            color: #4a5568;
            font-weight: 600;
            font-size: 1.05rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .btn-cancel:hover {
            background: #f7fafc;
            border-color: var(--gold-light);
            color: var(--gold-primary);
        }

        @media (max-width: 768px) {
            .plan-container {
                padding: 1rem;
            }
            
            .plan-body {
                padding: 1.5rem;
            }
            
            .plan-title {
                font-size: 1.5rem;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn-save, .btn-cancel {
                width: 100%;
            }
        }
    </style>

    <div class="plan-container">
        <div class="plan-card">
            <div class="plan-header">
                <h1 class="plan-title">Editar Plan de Trabajo</h1>
                <p class="plan-subtitle">Modifique los detalles del plan anual</p>
                <div class="plan-year">Año: {{ $plan->anio }}</div>
            </div>

            <div class="plan-body">
                <form action="{{ route('plan-trabajo.update', $plan->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Año del Plan</label>
                        <input type="number" name="anio" 
                               class="form-input"
                               value="{{ $plan->anio }}" 
                               min="2000" max="2100"
                               required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Descripción General</label>
                        <textarea name="descripcion_general" 
                                  class="form-textarea"
                                  placeholder="Describa los objetivos y alcances del plan de trabajo...">{{ $plan->descripcion_general }}</textarea>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('plan-trabajo.index') }}" class="btn-cancel">
                            ✕ Cancelar
                        </a>
                        <button type="submit" class="btn-save">
                            ✓ Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>