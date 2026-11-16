# CCISUR ITMS

Sistema interno de la Cámara (CCISUR) que manejamos desde TI. Aquí está el plan de trabajo y la bitácora de TI, los recordatorios de vencimientos, el calendario editorial de redes, las audiencias y antecedentes registrales del GOR, la cobranza de socios, las vacaciones de RRHH y la validación de certificados por QR que se conecta con ccisur.org.

Está hecho en Laravel 12 con MySQL. En local lo corro con XAMPP.

## Lo que hay que tener instalado

- Git
- XAMPP con PHP 8.2 o más nuevo (de ahí se usa PHP y MySQL)
- Composer: al instalarlo pide la ruta de PHP, se le pone `C:\xampp\php\php.exe`
- Node.js LTS
- VS Code

En `C:\xampp\php\php.ini` revisar que estas extensiones no estén comentadas con `;`: `gd`, `intl`, `zip`, `fileinfo`, `pdo_mysql`, `mbstring`. La de `gd` es la que más falta hace porque sin ella no salen los QR ni los PDF.

## Levantarlo en otra máquina

Primero abrir XAMPP y darle Start a MySQL. Apache no hace falta.

Mejor clonarlo fuera de OneDrive, porque OneDrive se pone a sincronizar `node_modules` y `vendor` y da problemas.

```powershell
cd C:\proyectos
git clone https://github.com/Hernandez25692/itccisur.git ccisur_itms
cd ccisur_itms
code .
```

Ya dentro de VS Code, en la terminal:

```powershell
composer install
npm install
copy .env.example .env
php artisan key:generate
```

En el `.env` que se acaba de crear, la parte de la base viene comentada. Dejarla así:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ccisur_itms
DB_USERNAME=root
DB_PASSWORD=

ADMIN_EMAIL=admin@gmail.com
ADMIN_PASSWORD=
```

En `ADMIN_PASSWORD` poner la contraseña que va a tener el usuario administrador. Si se deja vacía, el seeder inventa una y la muestra una sola vez en la terminal; hay que copiarla en ese momento.

`WORDPRESS_API_KEY` solo se ocupa para probar el envío de certificados a ccisur.org. Para lo demás se puede dejar vacía.

Crear la base y cargar todo:

```powershell
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE ccisur_itms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

Y entrar a http://127.0.0.1:8000 con el `ADMIN_EMAIL` y la contraseña.

El `--seed` crea los roles (`admin_ti`, `gerencia`, `usuario`, `calendario`, `GOR`, `GOR_Gerencia`, `cobranza`, `rrhh`), los cortes de cobranza (5, 12, 20 y 28), los rubros y el admin. No hay registro público: los demás usuarios se crean desde el sistema con el admin, en el menú Usuarios.

El `storage:link` es para que se vean los logos. Los logos sí vienen en el repo, en `storage/app/public/logos`.

## Para trabajar

Si voy a tocar estilos o JavaScript abro dos terminales:

```powershell
php artisan serve
```

```powershell
npm run dev
```

Si solo toco PHP o Blade basta con `php artisan serve`.

`composer run dev` no funciona en Windows. Trae `php artisan pail`, que no corre en Windows, y al caerse tumba lo demás.

Después de cada `git pull`:

```powershell
composer install
npm install
php artisan migrate
npm run build
```

## Vacaciones (RRHH)

Las vacaciones se acreditan solas por aniversario laboral todos los días a las 00:30. En local no hay nada programado, así que cuando ocupo probarlo lo corro a mano:

```powershell
php artisan rrhh:acreditar-vacaciones
```

Se puede correr las veces que sea, no duplica. Los días por año de servicio y la fecha del primer cierre están en `config/rrhh.php`.

## Pasar los datos reales

El repo solo trae el código; la base sale vacía. Si quiero los mismos datos que en la máquina de la oficina, allá saco el respaldo:

```powershell
C:\xampp\mysql\bin\mysqldump.exe -u root ccisur_itms > ccisur_itms.sql
```

Y en la otra máquina, en vez del `migrate --seed`:

```powershell
C:\xampp\mysql\bin\mysql.exe -u root ccisur_itms -e "source ccisur_itms.sql"
```

Las evidencias, los adjuntos del calendario y los comprobantes del GOR y de RRHH no están en Git, así que hay que copiar a mano toda la carpeta `storage/app`. Lo nuevo se guarda en `storage/app/private`, pero todavía quedan archivos viejos en `storage/app/public`. Para pasarlos a privado está `php artisan archivos:privatizar`.

El `.sql` y esa carpeta tienen datos de socios y empleados, así que no van al repo ni se mandan por WhatsApp.

## Cuando algo falla

- `could not find driver`: falta activar `pdo_mysql` en el `php.ini`. Después de cambiarlo hay que cerrar y abrir la terminal.
- `SQLSTATE[HY000] [2002]`: MySQL no está encendido en XAMPP.
- `Vite manifest not found`: falta `npm run build`.
- No salen los logos: falta `php artisan storage:link`.
- Cambié algo en el `.env` y no agarra: `php artisan optimize:clear`.

## Pruebas

```powershell
php artisan test
```

Corren con SQLite en memoria, no tocan la base de MySQL. Para eso tienen que estar activas `pdo_sqlite` y `sqlite3` en el `php.ini`.
