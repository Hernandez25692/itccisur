<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login – CCISUR TI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root{
            --primary-dark: #0c1c3c;
            --primary-medium: #1a2a4f;
            --gold-primary: #b79a37;
            --gold-light: #ffe9a0;
            --text-light: #ffffff;
            --text-muted: #9ca3af;
            --glass-bg: rgba(10,18,35,0.55);
            --glass-border: rgba(255,233,160,0.22);
        }

        *{box-sizing:border-box}
        body{
            min-height:100vh;
            margin:0;
            overflow:hidden;
            font-family:'Nunito', sans-serif;
            background:radial-gradient(circle at 30% 20%, var(--primary-medium) 0%, var(--primary-dark) 70%);
            position:relative;
            color:var(--text-light);
        }

        /* Fondo */
        .tech-grid{
            position:absolute; inset:0;
            background-image:
                linear-gradient(rgba(255,255,255,0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size:45px 45px;
            animation:gridMove 22s linear infinite;
            opacity:0.16;
            z-index:0;
        }
        @keyframes gridMove{0%{background-position:0 0}100%{background-position:200px 400px}}

        .circuit-lines::before, .circuit-lines::after{
            content:""; position:absolute; width:140%; height:2px;
            background:linear-gradient(90deg, transparent, rgba(183,154,55,0.7), transparent);
            animation:circuitMove 6s linear infinite; filter:blur(1px); z-index:1;
        }
        .circuit-lines::before{top:20%; left:-20%}
        .circuit-lines::after{top:70%; right:-20%; animation-delay:2.5s}
        @keyframes circuitMove{0%{transform:translateX(-100%)}100%{transform:translateX(100%)}}

        .orb{position:absolute;border-radius:50%;filter:blur(45px);opacity:0.25;animation:float 8s ease-in-out infinite alternate; z-index:0}
        .orb1{width:260px;height:260px;background:var(--gold-primary);top:-60px;right:-40px}
        .orb2{width:180px;height:180px;background:var(--gold-light);bottom:-50px;left:-40px}
        @keyframes float{0%{transform:translateY(0)}100%{transform:translateY(35px)}}

        .data-stream{position:absolute;inset:0;pointer-events:none;z-index:1;overflow:hidden}
        .data-particle{position:absolute;width:2px;background:linear-gradient(to bottom, transparent, var(--gold-light), transparent);opacity:0;animation:dataFlow 4s linear infinite}
        @keyframes dataFlow{0%{transform:translateY(-100px);opacity:0}10%{opacity:0.8}90%{opacity:0.8}100%{transform:translateY(100vh);opacity:0}}

        /* Card */
        .login-wrapper{display:flex;align-items:center;justify-content:center;min-height:100vh;position:relative;z-index:10;padding:1rem}
        .login-card{
            background:var(--glass-bg);backdrop-filter:blur(18px) saturate(170%);
            border-radius:1.5rem;padding:2.5rem;border:1px solid var(--glass-border);
            box-shadow:0 0 35px rgba(0,0,0,0.45), inset 0 0 25px rgba(255,233,160,0.03);
            width:100%;max-width:420px;position:relative;z-index:20;
        }
        .glow-edge{position:absolute;inset:0;border-radius:1.5rem;box-shadow:0 0 22px rgba(183,154,55,0.3),0 0 45px rgba(255,233,160,0.2);pointer-events:none;z-index:1}

        .logo{display:flex;flex-direction:column;align-items:center;margin-bottom:1rem;z-index:5}
        .logo img{width:140px;height:auto;filter:drop-shadow(0 6px 18px rgba(0,0,0,0.45))}

        h1{font-size:1.9rem;margin:0;font-weight:800;background:linear-gradient(to right,var(--text-light),var(--gold-light));-webkit-background-clip:text;color:transparent}
        .subtitle{color:var(--gold-primary);font-weight:600;margin-top:6px}

        form .field{margin-bottom:1rem}
        label{display:block;color:#d1d5db;font-weight:600;margin-bottom:6px;font-size:0.9rem}
        .input{
            width:100%;padding:0.8rem 1rem;border-radius:.75rem;background:rgba(12,28,60,0.6);
            border:1px solid rgba(183,154,55,0.25);color:var(--text-light);font-weight:600;
            transition:all .2s;
        }
        .input:focus{outline:none;border-color:var(--gold-primary);box-shadow:0 0 0 4px rgba(183,154,55,0.08);background:rgba(12,28,60,0.9)}

        .password-wrapper{position:relative}
        .password-toggle{
            position:absolute;right:0.6rem;top:50%;transform:translateY(-50%);background:transparent;border:none;color:var(--gold-light);cursor:pointer;font-size:1rem;padding:0.35rem;border-radius:6px
        }

        .btn{
            width:100%;padding:.9rem;border-radius:.75rem;border:none;font-weight:800;color:var(--primary-dark);background:linear-gradient(90deg,var(--gold-primary),var(--gold-light));cursor:pointer;
            transition:transform .15s, box-shadow .15s;box-shadow:0 10px 30px rgba(183,154,55,0.18)
        }
        .btn:active{transform:translateY(1px)}

        .footer-links{display:flex;justify-content:space-between;margin-top:1rem;gap:0.5rem;font-size:0.85rem;color:var(--text-muted)}
        .footer-links a{color:var(--text-muted);text-decoration:none}
        @media(max-width:640px){h1{font-size:1.6rem}.login-card{padding:1.8rem}}
    </style>
</head>

<body>
    <div class="tech-grid"></div>
    <div class="circuit-lines"></div>
    <div class="orb orb1"></div>
    <div class="orb orb2"></div>
    <div id="dataStream" class="data-stream"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <span class="glow-edge"></span>

            <div class="logo">
                <img src="{{ asset('storage/logos/logo2.png') }}" alt="CCISUR logo">
                <h1>CCISUR TI</h1>
                <div class="subtitle">Sistema de Tecnologías de Información</div>
                <div style="font-size:12px;color:#bfc7d6;margin-top:6px">Cámara de Comercio e Industrias del Sur</div>
            </div>

            <form id="loginForm" method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <div class="field">
                    <label for="email">Correo institucional</label>
                    <input id="email" name="email" type="email" required placeholder="usuario@ccisur.org" class="input">
                </div>

                <div class="field">
                    <label for="password">Contraseña</label>
                    <div class="password-wrapper">
                        <input id="password" name="password" type="password" required placeholder="••••••••" class="input">
                        <button type="button" id="togglePassword" class="password-toggle" aria-label="Mostrar contraseña">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button id="submitBtn" type="submit" class="btn">
                    <i class="fas fa-sign-in-alt" style="margin-right:8px"></i> Iniciar sesión
                </button>

                <div class="footer-links">
                    <a href="#"><i class="fas fa-question-circle" style="margin-right:6px"></i> Soporte</a>
                    <a href="#"><i class="fas fa-lock" style="margin-right:6px"></i> Recuperar</a>
                    <a href="#"><i class="fas fa-info-circle" style="margin-right:6px"></i> Políticas</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Partículas de datos
        function createDataParticles() {
            const stream = document.getElementById('dataStream');
            const particleCount = 18;
            for (let i = 0; i < particleCount; i++) {
                const p = document.createElement('div');
                p.className = 'data-particle';
                const left = Math.random() * 100;
                const delay = Math.random() * 4;
                const duration = 3 + Math.random() * 3;
                const height = 8 + Math.random() * 30;
                p.style.left = left + '%';
                p.style.animationDelay = delay + 's';
                p.style.animationDuration = duration + 's';
                p.style.height = height + 'px';
                stream.appendChild(p);
            }
        }

        // Nodos/cableado (decorativo)
        function createCircuitNodes() {
            const nodes = [
                {top: '12%', left: '8%'},
                {top: '28%', left: '85%'},
                {top: '62%', left: '14%'},
                {top: '78%', left: '88%'}
            ];
            nodes.forEach(n => {
                const node = document.createElement('div');
                node.style.position = 'absolute';
                node.style.width = '6px';
                node.style.height = '6px';
                node.style.top = n.top;
                node.style.left = n.left;
                node.style.background = 'var(--gold-primary)';
                node.style.borderRadius = '50%';
                node.style.boxShadow = '0 0 10px var(--gold-primary)';
                node.style.zIndex = '2';
                document.body.appendChild(node);
            });
            // conexiones simples
            const conn = document.createElement('div');
            conn.style.position = 'absolute';
            conn.style.top = '20%';
            conn.style.left = '10%';
            conn.style.width = '75%';
            conn.style.height = '1px';
            conn.style.background = 'linear-gradient(90deg, transparent, rgba(183,154,55,0.95), transparent)';
            conn.style.filter = 'blur(.5px)';
            conn.style.zIndex = '1';
            document.body.appendChild(conn);
        }

        // Toggle contraseña
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('togglePassword');
            const pwd = document.getElementById('password');
            const submitBtn = document.getElementById('submitBtn');
            const form = document.getElementById('loginForm');

            toggle.addEventListener('click', function () {
                const icon = this.querySelector('i');
                if (pwd.type === 'password') {
                    pwd.type = 'text';
                    icon.className = 'fas fa-eye-slash';
                } else {
                    pwd.type = 'password';
                    icon.className = 'fas fa-eye';
                }
            });

            // Animaciones de fondo
            createDataParticles();
            createCircuitNodes();

            // focus inicial
            setTimeout(()=>{ document.getElementById('email').focus() }, 500);

            // Mostrar spinner al enviar, pero permitir que el formulario se envíe normalmente
            form.addEventListener('submit', function () {
                submitBtn.disabled = true;
                const original = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right:8px"></i> Verificando...';
                // dejaremos que la petición real al servidor continúe
                // si quieres restaurar el botón en caso de error por JS, manejalo en la respuesta del servidor
                // setTimeout(()=>{ submitBtn.disabled = false; submitBtn.innerHTML = original }, 5000);
            });
        });
    </script>
</body>

</html>
