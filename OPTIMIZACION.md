# 🚀 GUÍA DE OPTIMIZACIÓN - CUIDARTE SPA

## 📦 Peso Actual del Sitio

### Assets Base (Sin imágenes de productos)
- **style.css**: ~15KB
- **main.js**: ~8KB  
- **Todos los banners SVG**: ~15KB (7 archivos)
- **Placeholder SVG**: ~1KB
- **TOTAL**: ~39KB

### Carga por Página
- **Home**: 40-50KB (sin imágenes de productos)
- **Productos**: 35-45KB + imágenes
- **Detalle**: 30-40KB + galería
- **Blog**: 35-45KB + imagen destacada

## 🎯 Objetivo: Mantener el sitio bajo 2MB por página

## 📸 Optimización de Imágenes

### 1. Productos (Crítico)
**Dimensiones recomendadas:**
- Imagen principal: 800x800px
- Galería: 800x800px
- Thumbnail: 200x200px

**Formato y peso:**
- Usar WebP (mejor compresión)
- Fallback a JPG
- Peso máximo: 150KB por imagen
- Calidad: 80-85%

**Herramientas:**
```bash
# Squoosh (online): https://squoosh.app/
# TinyPNG (online): https://tinypng.com/
# ImageMagick (CLI):
magick convert producto.jpg -resize 800x800 -quality 85 producto-opt.jpg
```

### 2. Blog
**Dimensiones:** 1200x630px (formato Open Graph)
**Peso máximo:** 120KB
**Formato:** WebP o JPG optimizado

### 3. Servicios
**Dimensiones:** 800x600px
**Peso máximo:** 100KB

## 🔧 Optimizaciones Implementadas

✅ **SVG para banners** (en lugar de JPG pesados)
✅ **SVG para placeholder** (1KB vs 50KB+)
✅ **CSS minimalista** (sin frameworks pesados)
✅ **JavaScript vanilla** (sin jQuery)
✅ **Lazy loading** de imágenes
✅ **Fuentes Google optimizadas** (solo 2 familias)

## 📊 Comparación de Peso

### Antes (con JPG)
- Banner JPG: ~500KB cada uno
- 3 banners: ~1.5MB
- Placeholder JPG: ~50KB
- **Total banners**: ~1.55MB

### Ahora (con SVG)
- Banner SVG: ~2KB cada uno
- 7 banners: ~14KB
- Placeholder SVG: ~1KB
- **Total banners**: ~15KB
- **Ahorro**: 99% menos peso

## 🌐 Configuración del Servidor

### Apache (.htaccess ya incluido)
```apache
# Compresión GZIP
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/css text/javascript application/javascript
</IfModule>

# Cache de navegador
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/svg+xml "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

### Nginx
```nginx
# Compresión
gzip on;
gzip_types text/css application/javascript image/svg+xml;

# Cache
location ~* \.(svg|css|js)$ {
    expires 1y;
    add_header Cache-Control "public, immutable";
}
```

## 📱 Optimización Móvil

### Imágenes Responsive
```html
<!-- Ejemplo para implementar -->
<picture>
    <source srcset="producto-small.webp" media="(max-width: 768px)">
    <source srcset="producto.webp">
    <img src="producto.jpg" alt="Producto">
</picture>
```

### CSS Critical
Considerar extraer CSS crítico inline para primera carga.

## 🔍 Herramientas de Análisis

### PageSpeed Insights
```
https://pagespeed.web.dev/
```

### GTmetrix
```
https://gtmetrix.com/
```

### WebPageTest
```
https://www.webpagetest.org/
```

## 📈 Métricas Objetivo

- **LCP (Largest Contentful Paint)**: < 2.5s
- **FID (First Input Delay)**: < 100ms
- **CLS (Cumulative Layout Shift)**: < 0.1
- **Peso total página**: < 2MB
- **Tiempo de carga**: < 3s

## 🎨 Optimización de Fuentes

### Actual (Google Fonts)
```html
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
```

### Mejora Futura (Self-hosted)
1. Descargar fuentes de Google Fonts
2. Convertir a WOFF2
3. Hospedar localmente
4. Usar `font-display: swap`

## 🗜️ Minificación

### CSS
```bash
# Usando cssnano
npx cssnano style.css style.min.css
```

### JavaScript
```bash
# Usando terser
npx terser main.js -o main.min.js -c -m
```

## 📦 Checklist Pre-Producción

- [ ] Comprimir todas las imágenes de productos
- [ ] Habilitar GZIP en servidor
- [ ] Configurar cache de navegador
- [ ] Minificar CSS y JS
- [ ] Probar en PageSpeed Insights
- [ ] Verificar responsive en móviles
- [ ] Probar lazy loading
- [ ] Configurar CDN (opcional)

## 🎯 Resultado Esperado

Con todas las optimizaciones:
- **Home**: ~100KB (sin productos)
- **Productos con 10 items**: ~1.5MB (150KB x 10)
- **Detalle con galería**: ~600KB (4 imágenes)
- **Blog**: ~200KB

**Total promedio por página: < 2MB** ✅

## 💡 Tips Adicionales

1. **Lazy loading**: Ya implementado en main.js
2. **Preload crítico**: Considerar para fuentes
3. **Defer JavaScript**: Ya implementado
4. **Async CSS**: Considerar para fuentes
5. **Service Worker**: Para cache offline (futuro)

## 🔄 Mantenimiento

- Revisar peso de imágenes mensualmente
- Limpiar uploads no utilizados
- Monitorear métricas de velocidad
- Actualizar dependencias

---

**Última actualización**: <?= date('Y-m-d') ?>
