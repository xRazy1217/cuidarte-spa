// HERO SLIDER
const initHeroSlider = () => {
    const slides = document.querySelectorAll('.wc-hero-slide');
    const dots   = document.querySelectorAll('.wc-dot');
    if (!slides.length) return;

    let current = 0;

    const showSlide = (n) => {
        slides.forEach(s => s.classList.remove('active'));
        dots.forEach(d => d.classList.remove('active'));
        slides[n].classList.add('active');
        if (dots[n]) dots[n].classList.add('active');
    };

    showSlide(0);
    const timer = setInterval(() => {
        current = (current + 1) % slides.length;
        showSlide(current);
    }, 5000);

    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
            clearInterval(timer);
            current = i;
            showSlide(i);
        });
    });
};

// TOAST
const showToast = (msg, duration = 3000) => {
    const t = document.getElementById('toast');
    if (!t) return;
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), duration);
};

// GALERÍA DE PRODUCTO
const initProductGallery = () => {
    const thumbs  = document.querySelectorAll('.wc-thumb');
    const mainImg = document.querySelector('#mainProductImg');
    if (!thumbs.length || !mainImg) return;

    thumbs.forEach(thumb => {
        thumb.addEventListener('click', () => {
            thumbs.forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
            mainImg.src = thumb.querySelector('img').src;
        });
    });
};

// VARIANTES
const initVariants = () => {
    const variants     = document.querySelectorAll('.wc-variant');
    const priceDisplay = document.querySelector('.wc-card-price, .wc-product-price');
    const variantInput = document.getElementById('variantId');
    if (!variants.length) return;

    variants.forEach(v => {
        v.addEventListener('click', () => {
            variants.forEach(x => x.classList.remove('active'));
            v.classList.add('active');
            if (priceDisplay && v.dataset.price)
                priceDisplay.textContent = '$' + parseInt(v.dataset.price).toLocaleString('es-CL');
            if (variantInput && v.dataset.id)
                variantInput.value = v.dataset.id;
        });
    });
};

// AGREGAR AL CARRITO
const addToCart = (variantId, cantidad = 1) => {
    if (!variantId) { showToast('Selecciona una variante primero'); return; }

    fetch(BASE_URL + '/carrito/agregar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `variante_id=${variantId}&cantidad=${cantidad}`
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const c = document.getElementById('cartCount');
            if (c) c.textContent = data.count;
            openCartDrawer();
        } else {
            showToast(data.message || 'Error al agregar');
        }
    })
    .catch(() => showToast('Error de conexión'));
};

// DRAWER CARRITO
const openCartDrawer = () => {
    fetch(BASE_URL + '/carrito/datos')
        .then(r => r.json())
        .then(data => {
            const items = data.items || [];
            const container = document.getElementById('cartDrawerItems');
            if (!container) return;

            if (!items.length) {
                container.innerHTML = '<div style="text-align:center;padding:3rem 1rem;color:var(--wc-text-soft)"><div style="font-size:3rem;margin-bottom:1rem">🛒</div><p>Tu carrito está vacío</p></div>';
                document.getElementById('cartDrawerTotal').textContent = '';
            } else {
                let total = 0;
                container.innerHTML = items.map(item => {
                    const subtotal = item.precio * item.cantidad;
                    total += subtotal;
                    const img = item.imagen
                        ? `${BASE_URL}/public/uploads/products/${item.imagen}`
                        : `${BASE_URL}/public/images/placeholder.svg`;
                    return `<div style="display:grid;grid-template-columns:65px 1fr;gap:0.75rem;align-items:center;padding:0.85rem 0;border-bottom:1px solid var(--wc-blue-soft)">
                        <img src="${img}" alt="" style="width:65px;height:65px;object-fit:cover;border-radius:10px;background:var(--wc-blue-pale)">
                        <div>
                            <div style="font-weight:700;font-size:0.9rem;color:var(--wc-blue-dark);line-height:1.3">${item.producto_nombre}</div>
                            <div style="font-size:0.8rem;color:var(--wc-text-soft);margin:0.15rem 0">${item.variante_nombre}</div>
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:0.35rem">
                                <span style="font-size:0.85rem;color:var(--wc-text-soft)">x${item.cantidad}</span>
                                <span style="font-weight:700;color:var(--wc-pink)">$${subtotal.toLocaleString('es-CL')}</span>
                            </div>
                        </div>
                    </div>`;
                }).join('');
                document.getElementById('cartDrawerTotal').textContent = '$' + total.toLocaleString('es-CL');
            }

            document.getElementById('cartDrawer').style.transform = 'translateX(0)';
            document.getElementById('cartDrawerOverlay').style.display = 'block';
        })
        .catch(() => showToast('Error al cargar carrito'));
};

const closeCartDrawer = () => {
    document.getElementById('cartDrawer').style.transform = 'translateX(100%)';
    document.getElementById('cartDrawerOverlay').style.display = 'none';
};

// Abrir drawer al hacer click en el ícono del carrito (si no estamos en /carrito)
document.addEventListener('DOMContentLoaded', () => {
    const cartBtn = document.querySelector('.wc-cart-btn');
    if (cartBtn && !window.location.pathname.includes('/carrito')) {
        cartBtn.addEventListener('click', e => {
            e.preventDefault();
            openCartDrawer();
        });
    }
});

// ELIMINAR DEL CARRITO
const removeFromCart = (variantId) => {
    if (!confirm('¿Eliminar este producto?')) return;
    fetch(BASE_URL + '/carrito/eliminar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `variante_id=${variantId}`
    })
    .then(r => r.json())
    .then(data => { if (data.success) location.reload(); });
};

// ACTUALIZAR CANTIDAD
const updateCartQuantity = (variantId, cantidad) => {
    fetch(BASE_URL + '/carrito/actualizar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `variante_id=${variantId}&cantidad=${cantidad}`
    })
    .then(r => r.json())
    .then(data => { if (data.success) location.reload(); });
};

// CUPÓN
const applyCoupon = () => {
    const code = document.getElementById('couponCode')?.value;
    if (!code) return;
    fetch(BASE_URL + '/carrito/aplicar-cupon', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `codigo=${code}`
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) location.reload();
        else showToast(data.message || 'Cupón inválido');
    });
};

// LAZY LOAD
const lazyLoad = () => {
    const imgs = document.querySelectorAll('img[data-src]');
    if (!imgs.length) return;
    const obs = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.src = e.target.dataset.src;
                e.target.removeAttribute('data-src');
                obs.unobserve(e.target);
            }
        });
    });
    imgs.forEach(img => obs.observe(img));
};

// INIT
document.addEventListener('DOMContentLoaded', () => {
    initHeroSlider();
    initProductGallery();
    initVariants();
    lazyLoad();
});

// SMOOTH SCROLL
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        const href = a.getAttribute('href');
        if (href === '#') return;
        e.preventDefault();
        document.querySelector(href)?.scrollIntoView({ behavior: 'smooth' });
    });
});

function toggleMenu() {
    const menu = document.getElementById('navMenu');
    const btn  = document.getElementById('hamburger');
    menu.classList.toggle('open');
    btn.classList.toggle('open');
}

function toggleUserMenu() {
    document.getElementById('userDropdown').classList.toggle('open');
}
document.addEventListener('click', function(e) {
    const menu = document.getElementById('userDropdown');
    if (menu && !e.target.closest('.wc-user-menu')) menu.classList.remove('open');
});
