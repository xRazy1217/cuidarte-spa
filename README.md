# 🌿 Cuidarte Spa - Ecommerce de Aromaterapia

Sistema completo de ecommerce para venta de productos de aromaterapia y reserva de servicios terapéuticos.

## ✨ Características

- **Sitio Web Público:**
  - Página de inicio con carrusel de banners
  - Catálogo de productos con variantes
  - Sistema de reservas de servicios
  - Blog de contenido
  - Carrito de compras
  - Proceso de checkout
  - Formulario de contacto

- **Panel Administrativo:**
  - Dashboard con métricas
  - Gestión de productos y variantes
  - Gestión de categorías
  - Gestión de servicios
  - Gestión de reservas
  - Gestión de pedidos
  - Sistema de cupones
  - Blog/Historias
  - Centro QR

## 🚀 Instalación

### Requisitos
- PHP 8.0+
- MySQL 5.7+
- Apache/Nginx con mod_rewrite

### Pasos

1. **Clonar/Copiar el proyecto**
```bash
cd c:\xampp\htdocs\
```

2. **Importar base de datos**
```bash
mysql -u root -p < database.sql
```

3. **Configurar base de datos**
Editar `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'cuidarte_spa');
define('DB_USER', 'root');
define('DB_PASS', '');
define('BASE_URL', 'http://localhost/cuidarte-spa');
define('SITE_NAME', 'Cuidarte Spa');
```

4. **Permisos de carpetas**
```bash
chmod 755 public/uploads/
chmod 755 public/uploads/products/
chmod 755 public/uploads/blog/
chmod 755 public/uploads/services/
```

5. **Acceder al sitio**
- Sitio público: `http://localhost/cuidarte-spa/`
- Panel admin: `http://localhost/cuidarte-spa/admin`
- Usuario admin: `admin@cuidartespa.cl`
- Contraseña: `password` (cambiar después)

## 🎨 Diseño

### Colores Acuarela
- **Azul Principal:** #4A90A4
- **Azul Claro:** #7FB3D5
- **Azul Suave:** #B8D4E3
- **Azul Pálido:** #E8F4F8
- **Azul Oscuro:** #2C5F6F

### Tipografías
- **Títulos:** Playfair Display (serif)
- **Texto:** Lato (sans-serif)

## ⚡ Optimización

### Imágenes
- **Banners:** SVG (< 2KB cada uno) con efecto blur para simular difuminado
- **Placeholders:** SVG (< 1KB) en lugar de JPG/PNG
- **Productos:** Comprimir a máximo 200KB, formato WebP recomendado
- **Blog:** Comprimir a máximo 150KB

### Recomendaciones
1. Usar herramientas como TinyPNG o Squoosh para comprimir imágenes
2. Dimensiones recomendadas:
   - Productos: 800x800px
   - Banners: 1920x800px (usar SVG proporcionados)
   - Blog: 1200x630px
3. Habilitar compresión GZIP en servidor
4. Usar lazy loading (ya implementado en JS)

## 📁 Estructura

```
Cuidarte Spa V1/
├── app/
│   ├── controllers/     # Controladores MVC
│   ├── models/          # Modelos de datos
│   └── views/           # Vistas (admin y public)
├── config/              # Configuración
├── public/
│   ├── css/            # Estilos
│   ├── js/             # JavaScript
│   ├── images/         # Imágenes estáticas (SVG)
│   └── uploads/        # Imágenes subidas
├── database.sql        # Base de datos
└── index.php          # Router principal
```

## 🛒 Productos

### Mezclas Disponibles
1. Calma y Armonía
2. Energía y Vitalidad
3. Inmunidad Natural
4. Respiración Plena
5. Serenidad Nocturna
6. Gran Diosa
7. Estrella de Paz

### Variantes
- Roll On 10ml - $10.000
- Bruma 50ml - $12.000
- Inhalador Personal - $5.000
- Pack Roll On + Bruma - $20.000

## 💆 Servicios

1. Guía en Crianza
2. Terapia RAI
3. Consulta Sesión Aromaterapia
4. Círculos de Autocuidado y Crianza
5. Talleres de Autocuidado y Aromaterapia

## 🔒 Seguridad

- Sanitización de inputs
- Prepared statements (prevención SQL injection)
- Validación de sesiones
- CSRF protection recomendado para producción

## 📊 Peso Total del Sitio

### Assets Estáticos
- CSS: ~15KB (minificado)
- JavaScript: ~8KB (minificado)
- Imágenes SVG: ~15KB total (todos los banners + placeholder)
- **Total base: ~38KB**

### Por Página (sin imágenes de productos)
- Home: ~40KB
- Productos: ~35KB
- Detalle: ~30KB
- **Carga inicial: < 100KB**

## 🌐 SEO

- URLs amigables
- Meta tags configurables
- Sitemap recomendado
- Schema markup para productos (a implementar)

## 📱 Responsive

- Mobile-first design
- Breakpoint: 768px
- Menú hamburguesa en móvil
- Grid adaptativo

## 🔄 Próximas Mejoras

- [ ] Integración con pasarela de pago (Webpay, Flow)
- [ ] Sistema de notificaciones por email
- [ ] Integración con Google Calendar para reservas
- [ ] Panel de cliente
- [ ] Sistema de reviews
- [ ] Newsletter
- [ ] Chat en vivo

## 📞 Soporte

Para consultas sobre el sistema, contactar al desarrollador.

---

**Desarrollado con ❤️ para Cuidarte Spa**
