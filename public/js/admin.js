// SIDEBAR TOGGLE
const sidebarToggle = document.getElementById('sidebarToggle');
const sidebar = document.getElementById('sidebar');
if (sidebarToggle) {
    sidebarToggle.addEventListener('click', () => sidebar.classList.toggle('open'));
}

// CONFIRM DELETE
document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', e => {
        if (!confirm(el.dataset.confirm || '¿Estás seguro?')) e.preventDefault();
    });
});

// IMAGE PREVIEW
document.querySelectorAll('input[type="file"][data-preview]').forEach(input => {
    input.addEventListener('change', () => {
        const preview = document.getElementById(input.dataset.preview);
        if (!preview || !input.files[0]) return;
        preview.src = URL.createObjectURL(input.files[0]);
        preview.style.display = 'block';
    });
});

// VARIANTES - agregar fila
const addVarianteBtn = document.getElementById('addVariante');
if (addVarianteBtn) {
    addVarianteBtn.addEventListener('click', () => {
        const container = document.getElementById('variantesContainer');
        const row = document.createElement('div');
        row.className = 'variante-row';
        row.innerHTML = `
            <div class="form-group" style="margin:0">
                <input type="text" name="variante_nombre[]" class="form-control" placeholder="Ej: Roll On 10ml" required>
            </div>
            <div class="form-group" style="margin:0">
                <input type="number" name="variante_precio[]" class="form-control" placeholder="Precio" required>
            </div>
            <div class="form-group" style="margin:0">
                <input type="number" name="variante_stock[]" class="form-control" placeholder="Stock" value="0">
            </div>
            <button type="button" class="btn btn-danger btn-sm remove-variante">✕</button>
        `;
        container.appendChild(row);
        row.querySelector('.remove-variante').addEventListener('click', () => row.remove());
    });

    // Remover filas existentes
    document.querySelectorAll('.remove-variante').forEach(btn => {
        btn.addEventListener('click', () => btn.closest('.variante-row').remove());
    });
}

// AUTO-DISMISS ALERTS
document.querySelectorAll('.alert').forEach(alert => {
    setTimeout(() => {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    }, 4000);
});

// QR GENERATOR
const generateQR = (url, canvasId) => {
    const canvas = document.getElementById(canvasId);
    if (!canvas || typeof QRCode === 'undefined') return;
    QRCode.toCanvas(canvas, url, { width: 150, margin: 1 });
};

const initQR = () => {
    document.querySelectorAll('[data-qr-url]').forEach(el => {
        const url = el.dataset.qrUrl;
        const canvasId = el.dataset.qrCanvas;
        if (url && canvasId) generateQR(url, canvasId);
    });
};

if (document.querySelector('[data-qr-url]')) {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js';
    script.onload = initQR;
    document.head.appendChild(script);
}
