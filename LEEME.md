# CCISUR ITMS — Guía de instalación local

Sistema interno de CCISUR: plan de trabajo y actividades de TI, bitácora, recordatorios, calendario editorial, control de audiencias, GOR (antecedentes registrales), cobranza de socios, RRHH (empleados y vacaciones) y validación de certificados por QR.

**Stack:** Laravel 12 · PHP 8.2 · MySQL · Breeze (login) · Spatie Permission (roles) · Vite + Tailwind + Alpine · DomPDF · QR (endroid / simple-qrcode).

---

## 1. Requisitos en la máquina nueva (Windows)

| Programa | Versión | Descarga |
|---|---|---|
| Git | cualquiera reciente | https://git-scm.com/download/win |
| XAMPP (PHP + MySQL) | PHP **8.2 o superior** | https://www.apachefriends.org |
| Composer | 2.x | https://getcomposer.org/Composer-Setup.exe |
| Node.js | LTS (20 o superior) | https://nodejs.org |
| Visual Studio Code | — | ya instalado |

Al instalar Composer, cuando pida la ruta de PHP, selecciona `C:\xampp\php\php.exe`.

### Extensiones de PHP

Abre `C:\xampp\php\php.ini` y verifica que estas líneas **no** empiecen con `;`:

```ini
extension=fileinfo
extension=gd
extension=intl
extension=mbstring
extension=pdo_mysql
extension=zip
```

Comprueba en una terminal nueva:

```powershell
php -v
composer -V
node -v
npm -v
git --version
php -m
```

`php -m` debe listar `gd`, `intl`, `pdo_mysql`, `zip`, `fileinfo` y `openssl`.

---

## 2. Instalación paso a paso

Abre **XAMPP Control Panel** e inicia **MySQL** (Apache no es necesario).

Abre VS Code, después una terminal (**Terminal → New Terminal**, PowerShell) y ejecuta:

```powershell
# 1. Clonar (mejor en una carpeta fuera de OneDrive, p. ej. C:\proyectos)
mkdir C:\proyectos -Force
cd C:\proyectos
git clone https://github.com/Hernandez25692/itccisur.git ccisur_itms
cd ccisur_itms
code .

# 2. Dependencias de PHP y de JavaScript
composer install
npm install

# 3. Archivo de entorno y clave de la aplicación
copy .env.example .env
php artisan key:generate
```

### 2.1 Configurar `.env`

Abre `.env` en VS Code y reemplaza el bloque de base de datos (en el ejemplo viene comentado con `#`) por:

```env
APP_NAME="CCISUR ITMS"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ccisur_itms
DB_USERNAME=root
DB_PASSWORD=

ADMIN_EMAIL=admin@gmail.com
ADMIN_PASSWORD=pon_aqui_una_contraseña_segura
```

- `DB_PASSWORD` se deja vacío si el usuario `root` de XAMPP no tiene contraseña.
- `ADMIN_EMAIL` / `ADMIN_PASSWORD` son las credenciales del primer usuario administrador (`admin_ti`). Si dejas `ADMIN_PASSWORD` vacío, el seeder genera una contraseña aleatoria y la muestra **una sola vez** en la terminal: cópiala.
- `WORDPRESS_API_KEY` solo hace falta si vas a probar el envío de certificados QR al sitio ccisur.org.

### 2.2 Crear la base de datos

Opción A, desde la terminal:

```powershell
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE ccisur_itms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Opción B: entra a http://localhost/phpmyadmin (requiere Apache encendido) y crea la base `ccisur_itms` con cotejamiento `utf8mb4_unicode_ci`.

### 2.3 Tablas, datos iniciales, enlace de archivos y compilación

```powershell
php artisan migrate --seed
php artisan storage:link
npm run build
```

`migrate --seed` crea todas las tablas y carga:
- **Roles:** `admin_ti`, `gerencia`, `usuario`, `calendario`, `GOR`, `GOR_Gerencia`, `cobranza`, `rrhh`.
- **Catálogos de cobranza:** cortes (5, 12, 20, 28), tipos de empresa y rubros.
- **Usuario administrador** con los datos de `ADMIN_EMAIL` / `ADMIN_PASSWORD`.

### 2.4 Logos

Los logos vienen en el repositorio, en `storage\app\public\logos\` (`Logo.png`, `logo1.png`, `logo2.png`, `logop.png`). Se ven en el navegador gracias a `php artisan storage:link` (paso 2.3). Si falta ese enlace, el login, el menú y los PDF salen sin logo.

El resto de `storage\app\public\` (evidencias, adjuntos, comprobantes) sigue fuera de Git.

---

## 3. Levantar el sistema

```powershell
php artisan serve
```

Abre http://127.0.0.1:8000 e inicia sesión con el `ADMIN_EMAIL` y la contraseña del administrador.

### Modo desarrollo (cambios de CSS/JS al instante)

Usa **dos terminales** en VS Code:

```powershell
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

> No uses `composer run dev` en Windows: incluye `php artisan pail`, que necesita la extensión `pcntl` (no existe en Windows), y al fallar detiene los demás procesos.

Si solo cambias código PHP o Blade, basta con `php artisan serve` (más un `npm run build` cuando cambien estilos).

---

## 4. Tareas programadas (RRHH)

El sistema acredita vacaciones por aniversario laboral todos los días a las 00:30. En local puedes:

```powershell
# Ejecutarlo a mano cuando lo necesites
php artisan rrhh:acreditar-vacaciones

# O dejar el programador corriendo en otra terminal
php artisan schedule:work
```

---

## 5. Traer los datos reales (opcional)

Con el clonado solo obtienes el código y una base vacía. Para tener los mismos datos que la máquina original:

**En la máquina original:**

```powershell
C:\xampp\mysql\bin\mysqldump.exe -u root ccisur_itms > ccisur_itms.sql
```

Copia también la carpeta `storage\app\` completa (evidencias, adjuntos y comprobantes; los logos ya vienen con el repositorio).

**En la máquina nueva** (en lugar de `migrate --seed`):

```powershell
C:\xampp\mysql\bin\mysql.exe -u root ccisur_itms -e "source ccisur_itms.sql"
```

Pega la carpeta `storage\app\` en el proyecto y ejecuta `php artisan storage:link`.

> El `.sql` y la carpeta `storage` contienen datos personales de socios y empleados: no los subas a Git ni los compartas por canales públicos.

---

## 6. Actualizar después de un `git pull`

```powershell
git pull
composer install
npm install
php artisan migrate
npm run build
php artisan optimize:clear
```

---

## 7. Problemas comunes

| Síntoma | Solución |
|---|---|
| `could not find driver` | Activa `extension=pdo_mysql` en `php.ini` y reinicia la terminal. |
| `SQLSTATE[HY000] [2002]` (conexión rechazada) | MySQL no está iniciado en XAMPP, o el puerto en `.env` no es 3306. |
| `Unknown database 'ccisur_itms'` | Falta crear la base (paso 2.2). |
| `Vite manifest not found` | Ejecuta `npm run build` (o deja `npm run dev` corriendo). |
| Imágenes o logos rotos | Ejecuta `php artisan storage:link` (paso 2.3). |
| `No application encryption key` | Ejecuta `php artisan key:generate`. |
| Composer pide `ext-gd` o `ext-zip` | Activa la extensión en `php.ini`. |
| Cambié el `.env` y no se nota | `php artisan optimize:clear` |
| Errores raros de archivos en OneDrive | Mueve el proyecto fuera de OneDrive (p. ej. `C:\proyectos`). |

---

## 8. Pruebas

```powershell
php artisan test
```

Usan SQLite en memoria (ver `phpunit.xml`), así que no tocan la base MySQL. Requieren `extension=pdo_sqlite` y `extension=sqlite3` activas en `php.ini`.

---

## Extensiones recomendadas de VS Code

- PHP Intelephense (`bmewburn.vscode-intelephense-client`)
- Laravel Blade Snippets (`onecentlab.laravel-blade`)
- Tailwind CSS IntelliSense (`bradlc.vscode-tailwindcss`)
- DotENV (`mikestead.dotenv`)
