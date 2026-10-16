# CLAUDE.md — Cuidarte Spa

Método de trabajo global: MÉTODO F5 (`~/.claude/CLAUDE.md`). Aquí solo lo específico del proyecto.

## Qué es
Ecommerce de aromaterapia + reservas de servicios terapéuticos (Cuidarte La Serena, Chile).
Producción: https://cuidartelaserena.cl (SiteGround). Repo: github.com/xRazy1217/cuidarte-spa.

## Stack (verificado en archivos)
- PHP puro, MVC propio. Sin framework ni tests. `composer.json` declara `phpmailer/phpmailer ^6.8`
  pero **no hay `vendor/`**: `app/models/Mailer.php` no lo usa (revisar antes de asumirlo).
- MySQL por PDO (`getDB()` en `config/database.php`). Esquema: `database/cuidarte_spa.sql` + migraciones
  sueltas en `database/*.sql` (sin orden numerado; aplicar según fecha/nombre).
- Pagos: Flow (`app/models/Flow.php`, `PagoController::confirmar|retorno`). Email: `Mailer.php` + `EmailTemplates.php`.
- Cron: `cron_recordatorios.php` (recordatorios de reservas, cada 30 min; zona America/Santiago).

## Rutas clave
- `index.php`: router manual. `$map` mapea `/controlador/acción/param`; `producto` y `articulo` usan slug.
- Admin: `/admin/<ctrl>` → `app/controllers/admin/<Ctrl>AdminController.php` o `<Ctrl>Controller.php`;
  exige `$_SESSION['usuario']['rol']==='admin'`. Login admin por URL secreta (`ADMIN_SECRET_URL` en `index.php`).
- Cliente: `/mi-cuenta/*` (`ClienteController`). Vistas en `app/views/{public,admin,cliente,layouts}`.
- Subidas: `public/uploads/{products,blog,...}`.

## Gotchas
- `Controller::render()` es **protected**: el router solo puede invocar métodos públicos (ej. `HomeController::notFound()`).
- `config/database.php` y `config/smtp.php` tienen **credenciales y NO se versionan** (`.gitignore`).
  `database.example.php` está incompleto (sin `getDB()` ni config de sesión): copiar de producción, nunca commitear.
- SiteGround (nginx) sirve estáticos existentes **sin pasar por `.htaccess`**: nunca dejar zips, dumps, logs
  ni backups dentro de `public_html`. Backups en `~/backups/` (fuera de `www/`).
- `config/database.php` en producción tiene `display_errors=1` incluso con `ENVIRONMENT==='production'`
  (el comentario dice lo contrario). Pendiente de corregir.
- Producción usa git: rama `main` = `origin/main`. Desplegar con `git pull --ff-only`, nunca con zips/FTP.
  Antes de cambiar de rama en el servidor, respaldar `config/*.php` (no están versionados y no deben borrarse).
- Windows: `core.autocrlf=true`; al comparar hashes con el servidor, normalizar LF.

## Acceso
- SSH: `ssh cuidartelaserena` (puerto 18765, clave en `~/.ssh`). Raíz web: `~/www/cuidartelaserena.cl/public_html`.

## Pendientes conocidos
- Rotar credenciales BD / Flow / SMTP (estuvieron en un zip público desde 29-abr-2026; zip ya retirado).
- Corregir `display_errors` en producción; completar `database.example.php`.
- CSRF, validación de subidas y rate limit del login admin sin revisar.
- Copias sobrantes: `cuidarte-spa/` en el repo y `~/www/cuidartelaserena.cl/cuidarte-spa/` en el servidor.
