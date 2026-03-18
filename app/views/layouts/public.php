<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? SITE_NAME) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDesc ?? '') ?>">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Playfair+Display:wght@400;600;700;800&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="wc-navbar sticky-top">
    <div class="wc-navbar-inner container">

        <!-- LOGO -->
        <a class="wc-brand" href="<?= BASE_URL ?>/">
            <div class="wc-logo-wrap">
                <span class="wc-logo-icon">🌿</span>
                <div>
                    <span class="wc-logo-text">Cuidarte</span>
                    <span class="wc-logo-sub">Spa & Aromaterapia</span>
                </div>
            </div>
        </a>

        <!-- LINKS CENTRO -->
        <ul class="wc-nav-links" id="navMenu">
            <li><a class="wc-nav-link" href="<?= BASE_URL ?>/">Inicio</a></li>
            <li><a class="wc-nav-link" href="<?= BASE_URL ?>/nosotros">Propósito</a></li>
            <li><a class="wc-nav-link" href="<?= BASE_URL ?>/productos">Productos</a></li>
            <li><a class="wc-nav-link" href="<?= BASE_URL ?>/servicios">Servicios</a></li>
            <li><a class="wc-nav-link" href="<?= BASE_URL ?>/blog">Blog</a></li>
            <li><a class="wc-nav-link" href="<?= BASE_URL ?>/contacto">Contacto</a></li>
        </ul>

        <!-- ACCIONES DERECHA -->
        <div class="wc-nav-actions">
            <?php if (!empty($_SESSION['usuario']) && ($_SESSION['usuario']['rol'] ?? '') === 'cliente'): ?>
                <div class="dropdown">
                    <button class="btn-wc-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="padding:0.45rem 1.1rem;font-size:0.85rem">
                        👤 <?= htmlspecialchars(explode(' ', $_SESSION['usuario']['nombre'])[0]) ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/mi-cuenta/perfil">✏️ Mi perfil</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/mi-cuenta/pedidos">📦 Mis compras</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/mi-cuenta/reservas">📅 Mis reservas</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/mi-cuenta/logout">🚪 Cerrar sesión</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a class="wc-nav-account" href="<?= BASE_URL ?>/mi-cuenta/ingresar">Ingresar</a>
            <?php endif; ?>
            <a class="wc-cart-btn" href="<?= BASE_URL ?>/carrito">
                🛒 <span class="wc-cart-count" id="cartCount"><?= array_sum(array_column($_SESSION['carrito'] ?? [], 'cantidad')) ?></span>
            </a>
            <button class="wc-hamburger" id="hamburger" onclick="toggleMenu()" aria-label="Menú">
                <span></span><span></span><span></span>
            </button>
        </div>

    </div>
</nav>

<main>
<?= $content ?>
</main>

<!-- FOOTER -->
<footer class="wc-footer mt-3">
    <img src="<?= BASE_URL ?>/public/images/banner/footer.png" alt="Footer" class="wc-footer-bg">
    <div class="wc-footer-content container">
        <div class="row g-4 pb-4">
            <div class="col-md-4">
                <div class="wc-footer-brand">
                    <span style="font-size:1.8rem;font-weight:800;color:white">Cuidarte Spa 🦋</span>
                    <p class="mt-2" style="opacity:0.8">Bienestar físico, emocional y mental a través de la aromaterapia y el acompañamiento consciente.</p>
                </div>
            </div>
            <div class="col-md-4">
                <h5 class="wc-footer-title">Navegación</h5>
                <ul class="list-unstyled wc-footer-links">
                    <li><a href="<?= BASE_URL ?>/productos">🌿 Productos</a></li>
                    <li><a href="<?= BASE_URL ?>/servicios">💆 Servicios</a></li>
                    <li><a href="<?= BASE_URL ?>/reservar">📅 Reservar</a></li>
                    <li><a href="<?= BASE_URL ?>/blog">✍️ Blog</a></li>
                    <li><a href="<?= BASE_URL ?>/nosotros">🌸 Propósito</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5 class="wc-footer-title">Contacto</h5>
                <p style="opacity:0.8">📱 <?= config('telefono') ?: '+56 9 XXXX XXXX' ?></p>
                <p style="opacity:0.8">📧 <?= config('email_contacto') ?: 'contacto@cuidartespa.cl' ?></p>
                <div class="d-flex gap-2 mt-3">
                    <a href="#" class="wc-social-btn">Instagram</a>
                    <a href="#" class="wc-social-btn">Facebook</a>
                </div>
            </div>
        </div>
        <div class="wc-footer-bottom text-center py-3">
            <p class="mb-0" style="opacity:0.75">&copy; <?= date('Y') ?> Cuidarte Spa &mdash; Hecho con 💙 y aromaterapia</p>
        </div>
    </div>
</footer>

<div id="toast" class="wc-toast"></div>

<!-- DRAWER CARRITO -->
<div id="cartDrawerOverlay" onclick="closeCartDrawer()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:1040"></div>
<div id="cartDrawer" style="position:fixed;top:0;right:0;width:380px;max-width:95vw;height:100vh;background:white;z-index:1050;transform:translateX(100%);transition:transform 0.35s cubic-bezier(.4,0,.2,1);display:flex;flex-direction:column;box-shadow:-8px 0 40px rgba(0,0,0,0.15)">
    <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--wc-blue-soft);display:flex;justify-content:space-between;align-items:center;background:linear-gradient(135deg,#4A90A4,#2C7A6F)">
        <span style="font-weight:700;font-size:1.1rem;color:white">🛒 Tu Carrito</span>
        <button onclick="closeCartDrawer()" style="background:rgba(255,255,255,0.2);border:none;color:white;width:32px;height:32px;border-radius:50%;font-size:1.1rem;cursor:pointer;display:flex;align-items:center;justify-content:center">&times;</button>
    </div>
    <div id="cartDrawerItems" style="flex:1;overflow-y:auto;padding:1rem 1.25rem"></div>
    <div style="padding:1.25rem 1.5rem;border-top:1px solid var(--wc-blue-soft);background:var(--wc-blue-pale)">
        <div style="display:flex;justify-content:space-between;margin-bottom:1rem;font-weight:700;font-size:1.1rem">
            <span>Total</span><span id="cartDrawerTotal" style="color:var(--wc-pink)"></span>
        </div>
        <a href="<?= BASE_URL ?>/checkout" class="btn-wc-primary d-block text-center mb-2" style="padding:0.85rem">💳 Pagar</a>
        <button onclick="closeCartDrawer()" class="btn-wc-outline d-block w-100 text-center" style="padding:0.85rem">← Seguir Comprando</button>
    </div>
</div>

<!-- MODAL RESERVA GLOBAL -->
<div class="modal fade" id="modalReserva" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:20px;border:none;overflow:hidden">
            <div class="modal-header" style="background:linear-gradient(135deg,var(--wc-blue),var(--wc-pink));border:none;padding:1.25rem 1.5rem">
                <h5 class="modal-title" style="font-weight:700;color:white;font-size:1.2rem">📅 Reserva tu Sesión</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="modalFormBody">
                <div id="reservaError" class="wc-alert-error" style="display:none">Por favor completa todos los campos requeridos.</div>
                <form id="reservaForm">
                    <div class="mb-3">
                        <label class="wc-form-label">Servicio *</label>
                        <select name="servicio_id" id="servicioId" class="wc-form-control" required onchange="actualizarModalidad()">
                            <option value="">Selecciona un servicio</option>
                            <?php foreach ($serviciosModal ?? [] as $s): ?>
                            <option value="<?= $s['id'] ?>"
                                    data-precio="<?= $s['precio'] ?>"
                                    data-precio-presencial="<?= $s['precio_presencial'] ?? '' ?>"
                                    data-modalidad="<?= $s['modalidad'] ?>">
                                <?= htmlspecialchars($s['nombre']) ?> — $<?= number_format($s['precio'],0,',','.') ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3" id="modalidadContainer" style="display:none">
                        <label class="wc-form-label">Modalidad *</label>
                        <div style="display:flex;gap:0.75rem">
                            <label id="opcionOnline" style="flex:1;border:2px solid var(--wc-blue-soft);border-radius:12px;padding:0.75rem;cursor:pointer;text-align:center">
                                <input type="radio" name="modalidad" value="online" style="display:none" onchange="actualizarPrecio()">
                                💻 Online<br><span id="precioOnline" style="font-weight:700;color:var(--wc-blue)"></span>
                            </label>
                            <label id="opcionPresencial" style="flex:1;border:2px solid var(--wc-blue-soft);border-radius:12px;padding:0.75rem;cursor:pointer;text-align:center">
                                <input type="radio" name="modalidad" value="presencial" style="display:none" onchange="actualizarPrecio()">
                                🏠 Presencial<br><span id="precioPresencial" style="font-weight:700;color:var(--wc-purple)"></span>
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="wc-form-label">Fecha *</label>
                        <input type="date" name="fecha" id="fecha" class="wc-form-control" required min="<?= date('Y-m-d') ?>" onchange="cargarHorarios()">
                    </div>
                    <div id="horariosContainer" class="mb-3" style="display:none">
                        <label class="wc-form-label">Horario disponible *</label>
                        <div id="horariosDisponibles" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));gap:0.6rem"></div>
                        <input type="hidden" name="horario_id" id="horarioId">
                    </div>
                    <hr style="border-color:var(--wc-blue-soft)">
                    <div class="mb-3">
                        <label class="wc-form-label">Nombre completo *</label>
                        <input type="text" name="nombre" class="wc-form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="wc-form-label">Email *</label>
                        <input type="email" name="email" class="wc-form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="wc-form-label">Teléfono</label>
                        <input type="tel" name="telefono" class="wc-form-control" placeholder="+56 9 XXXX XXXX">
                    </div>
                    <div class="mb-3">
                        <label class="wc-form-label">Notas adicionales</label>
                        <textarea name="notas" class="wc-form-control" rows="3" placeholder="¿Algo que debamos saber?"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-0" id="modalFormFooter">
                <button type="button" class="btn-wc-outline" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn-wc-primary" onclick="enviarReserva()">Confirmar Reserva</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>const BASE_URL = '<?= BASE_URL ?>';</script>
<script src="<?= BASE_URL ?>/public/js/main.js"></script>
<script>
// Inyectar CSRF en formularios públicos
document.querySelectorAll('form[method="post"], form[method="POST"]').forEach(f => {
    if (!f.querySelector('[name="csrf_token"]')) {
        const i = document.createElement('input');
        i.type = 'hidden'; i.name = 'csrf_token';
        i.value = '<?= csrfToken() ?>';
        f.appendChild(i);
    }
});
</script>
<script>
function abrirReserva(servicioId) {
    const sel = document.getElementById('servicioId');
    if (servicioId && sel) {
        sel.value = servicioId;
        actualizarModalidad();
    }
    new bootstrap.Modal(document.getElementById('modalReserva')).show();
}

function actualizarModalidad() {
    const sel = document.getElementById('servicioId');
    const opt = sel.options[sel.selectedIndex];
    const modalidad   = opt.dataset.modalidad;
    const precio      = opt.dataset.precio;
    const precioP     = opt.dataset.precioPresencial;
    const container   = document.getElementById('modalidadContainer');
    const opOnline    = document.getElementById('opcionOnline');
    const opPresencial= document.getElementById('opcionPresencial');

    if (!sel.value) { container.style.display = 'none'; return; }

    document.getElementById('precioOnline').textContent     = '$' + parseInt(precio).toLocaleString('es-CL');
    document.getElementById('precioPresencial').textContent = precioP ? '$' + parseInt(precioP).toLocaleString('es-CL') : '';

    if (modalidad === 'online') {
        opOnline.style.display = 'flex'; opPresencial.style.display = 'none';
        opOnline.querySelector('input').checked = true;
    } else if (modalidad === 'presencial') {
        opOnline.style.display = 'none'; opPresencial.style.display = 'flex';
        opPresencial.querySelector('input').checked = true;
    } else {
        opOnline.style.display = 'flex'; opPresencial.style.display = 'flex';
        opOnline.querySelector('input').checked = true;
    }
    container.style.display = 'block';
    actualizarPrecio();
    cargarHorarios();
}

function actualizarPrecio() {
    const modalidad = document.querySelector('input[name="modalidad"]:checked')?.value;
    const opOnline  = document.getElementById('opcionOnline');
    const opPresencial = document.getElementById('opcionPresencial');
    opOnline.style.borderColor     = modalidad === 'online'     ? 'var(--wc-blue)' : 'var(--wc-blue-soft)';
    opPresencial.style.borderColor = modalidad === 'presencial' ? 'var(--wc-purple)' : 'var(--wc-blue-soft)';
}

function cargarHorarios() {
    const servicioId = document.getElementById('servicioId').value;
    const fecha = document.getElementById('fecha').value;
    if (!servicioId || !fecha) return;
    fetch(`${BASE_URL}/reservar/horarios?servicio_id=${servicioId}&fecha=${fecha}`)
        .then(r => r.json())
        .then(horarios => {
            const container = document.getElementById('horariosDisponibles');
            const wrapper   = document.getElementById('horariosContainer');
            container.innerHTML = horarios.length
                ? horarios.map(h => `<div class="wc-variant" style="text-align:center;cursor:pointer" onclick="seleccionarHorario(${h.id},this)">${h.hora_inicio.substring(0,5)}</div>`).join('')
                : '<p style="color:var(--wc-text-soft);grid-column:1/-1">No hay horarios disponibles</p>';
            wrapper.style.display = 'block';
        });
}

function seleccionarHorario(id, el) {
    document.querySelectorAll('#horariosDisponibles .wc-variant').forEach(e => e.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('horarioId').value = id;
}

function enviarReserva() {
    const form = document.getElementById('reservaForm');
    if (!form.checkValidity() || !document.getElementById('horarioId').value) {
        document.getElementById('reservaError').style.display = 'block';
        return;
    }
    document.getElementById('reservaError').style.display = 'none';
    form.method = 'POST';
    form.action = BASE_URL + '/reservar';
    const csrf = document.createElement('input');
    csrf.type = 'hidden'; csrf.name = 'csrf_token';
    csrf.value = '<?= csrfToken() ?>';
    form.appendChild(csrf);
    form.submit();
}

// Resetear modal al cerrarse
document.getElementById('modalReserva').addEventListener('hidden.bs.modal', () => {
    document.getElementById('reservaForm').reset();
    document.getElementById('horariosContainer').style.display = 'none';
    document.getElementById('reservaError').style.display = 'none';
    document.getElementById('modalFormBody').innerHTML = document.getElementById('modalFormBody').innerHTML;
});
</script>
</body>
</html>
