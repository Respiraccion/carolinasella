# 🛒 Shop Platform Integration Plan
## Printful (Prints) + Contrado (Tazas y Pañuelos)

---

## 📋 Resumen Ejecutivo

Este documento detalla el plan de integración para vender productos print-on-demand en el shop de Carolina Sella:

| Plataforma | Productos | Automatización | Integración |
|------------|-----------|----------------|-------------|
| **Printful** | Art Prints (papel, canvas, posters) | ✅ Alta (API REST completa) | Frontend propio + API |
| **Contrado** | Tazas, Pañuelos (scarves) | ⚠️ Media (Shopify app o manual) | Enlace externo o manual |

---

## 🎨 PRINTFUL - Art Prints

### Nivel de Automatización: ⭐⭐⭐⭐⭐ (Excelente)

### Capacidades de la API

Printful ofrece una **API REST completa** que permite:

1. **Catalog API**: Acceso al catálogo de productos blancos y variantes
2. **Products API**: Crear/modificar productos sincronizados con diseños
3. **Orders API**: Crear pedidos automáticamente, tracking de envíos
4. **Mockup Generator API**: Generar mockups de productos con los diseños
5. **Webhook API**: Notificaciones en tiempo real (envío, actualización de pedido, etc.)
6. **Shipping Rate API**: Calcular costos de envío en tiempo real
7. **File Library API**: Subir y gestionar archivos de diseño

### Arquitectura de Integración Recomendada

```
┌─────────────────────────────────────────────────────────────────┐
│                    FRONTEND (WordPress)                          │
│  ┌─────────────┐  ┌──────────────┐  ┌────────────────────────┐  │
│  │ Shop Page   │  │ Product Page │  │ Cart/Checkout (Stripe) │  │
│  └──────┬──────┘  └──────┬───────┘  └───────────┬────────────┘  │
└─────────┼────────────────┼──────────────────────┼───────────────┘
          │                │                      │
          ▼                ▼                      ▼
┌─────────────────────────────────────────────────────────────────┐
│                    BACKEND (PHP/REST)                            │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │              Custom WordPress Plugin/API                  │   │
│  │  • Gestión de productos (JSON estático o WP custom post) │   │
│  │  • Procesamiento de pagos (Stripe)                        │   │
│  │  • Orden a Printful vía API                               │   │
│  │  • Webhooks receiver (actualizaciones de estado)          │   │
│  └──────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
          │                                       ▲
          ▼                                       │
┌─────────────────────────────────────────────────────────────────┐
│                    PRINTFUL API                                  │
│  ┌────────────┐  ┌───────────┐  ┌─────────────┐  ┌───────────┐  │
│  │ Products   │  │ Orders    │  │ Mockups     │  │ Webhooks  │  │
│  └────────────┘  └───────────┘  └─────────────┘  └───────────┘  │
└─────────────────────────────────────────────────────────────────┘
```

### Flujo de Trabajo Automatizado

#### 1. Setup Inicial (Una vez)
```
[ ] Crear cuenta Printful (printful.com)
[ ] Generar API Token en Dashboard → Settings → API
[ ] Configurar webhook URL: https://carolinasella.com/api/printful-webhook
[ ] Seleccionar productos base para prints:
    - Enhanced Matte Paper Poster
    - Canvas Prints
    - Framed Posters
    - Art Prints en papel premium
```

#### 2. Agregar Nuevo Print (Por cada obra)
```
1. Subir archivo de alta resolución a Printful
2. Crear "Sync Product" con variantes (tamaños)
3. Generar mockups automáticamente vía API
4. Guardar producto en WordPress (custom post type o JSON)
5. Publicar en frontend
```

#### 3. Flujo de Compra (Automático)
```
Cliente               Frontend            Backend              Printful
   │                     │                   │                    │
   ├─── Selecciona ────>│                   │                    │
   │    producto         │                   │                    │
   │                     │                   │                    │
   ├─── Checkout ──────>│                   │                    │
   │    (Stripe)         ├─── Pago ────────>│                    │
   │                     │    aprobado       │                    │
   │                     │                   ├─── Create ────────>│
   │                     │                   │    Order           │
   │                     │                   │                    │
   │                     │                   │<─── Order ─────────┤
   │                     │                   │     confirmed      │
   │<─── Email ─────────┼───────────────────┤                    │
   │    confirmación     │                   │                    │
   │                     │                   │                    │
   │                     │                   │<─── Webhook ───────┤
   │                     │                   │     (shipped)      │
   │<─── Email ─────────┼───────────────────┤                    │
   │    tracking         │                   │                    │
```

### Implementación Técnica

#### Opción A: Custom WordPress Plugin (Recomendado)
```php
// Estructura del plugin
wp-content/plugins/carolina-shop/
├── carolina-shop.php              // Main plugin file
├── includes/
│   ├── class-printful-api.php     // Printful API wrapper
│   ├── class-stripe-checkout.php  // Stripe integration
│   ├── class-order-handler.php    // Order processing
│   └── class-webhook-handler.php  // Webhook receiver
├── admin/
│   └── product-manager.php        // Admin UI para productos
└── public/
    ├── shop-template.php          // Shop page template
    └── js/cart.js                 // Cart functionality
```

#### Opción B: Headless con Next.js/React
- Frontend separado con React
- API routes para comunicación con Printful
- Más moderno pero requiere hosting adicional

### API Endpoints Clave (Printful)

```bash
# Autenticación
Authorization: Bearer {API_TOKEN}
Base URL: https://api.printful.com

# Obtener catálogo de productos
GET /products

# Crear producto sincronizado
POST /store/products
{
  "sync_product": { "name": "Ocean Dreams Print" },
  "sync_variants": [
    {
      "retail_price": 35.00,
      "variant_id": 4011,  // 12x16 Poster
      "files": [{ "url": "https://..." }]
    }
  ]
}

# Crear orden
POST /orders
{
  "recipient": {
    "name": "John Doe",
    "address1": "...",
    "city": "...",
    "country_code": "US",
    "zip": "..."
  },
  "items": [
    { "sync_variant_id": 123, "quantity": 1 }
  ]
}

# Confirmar orden (cobra y envía a producción)
POST /orders/{id}/confirm
```

### Webhooks Importantes

```javascript
// Eventos a escuchar
{
  "package_shipped": "Paquete enviado → Email con tracking al cliente",
  "order_failed": "Error en orden → Notificar para resolver",
  "order_canceled": "Cancelación → Procesar reembolso",
  "order_updated": "Actualización de estado → Actualizar en DB"
}
```

### Precios y Márgenes

| Producto | Costo Printful | Precio Sugerido | Margen |
|----------|---------------|-----------------|--------|
| 12×16 Poster | ~$12 USD | $35-45 USD | ~65% |
| 18×24 Poster | ~$18 USD | $55-70 USD | ~65% |
| Canvas 16×20 | ~$35 USD | $95-120 USD | ~60% |
| Framed 12×16 | ~$45 USD | $110-140 USD | ~60% |

---

## ☕ CONTRADO - Tazas y Pañuelos

### Nivel de Automatización: ⭐⭐⭐ (Moderado)

### Situación Actual de Contrado

**Importante:** Contrado tiene integración nativa **principalmente con Shopify**. Para sitios custom:

1. **API disponible** pero menos documentada que Printful
2. **Integración manual** posible para pedidos
3. **Mejor opción**: Redirigir a tienda Contrado o usar iframes

### Opciones de Integración

#### Opción 1: Tienda Contrado Dedicada (⭐ Recomendado para empezar)
```
┌────────────────┐        ┌─────────────────────────────┐
│ carolinasella  │  Link  │ carolinasella.contrado.com  │
│   .com/shop    │ ─────> │   (Tienda White-label)      │
│                │        │                             │
│ "Tazas y       │        │ • Gestión completa          │
│  Pañuelos"     │        │ • Pagos integrados          │
│                │        │ • Envío automático          │
└────────────────┘        └─────────────────────────────┘
```

**Ventajas:**
- ✅ Cero desarrollo necesario
- ✅ Contrado maneja pagos, envíos, atención al cliente
- ✅ Branding personalizable
- ✅ Producción 1-3 días, envío mundial

**Desventajas:**
- ❌ Cliente sale del sitio principal
- ❌ Menos control sobre la experiencia

#### Opción 2: Integración Manual Semi-Automatizada
```
Cliente compra en       Backend recibe      Se crea orden
tu sitio (Stripe)  →    notificación   →    en Contrado
                                            (manualmente o
                                             via API básica)
```

**Flujo:**
1. Cliente compra en tu frontend
2. Recibes notificación de venta
3. Colocas el pedido en Contrado (manual o automatizado)
4. Contrado produce y envía
5. Actualizas al cliente con tracking

#### Opción 3: API Custom (Para futuro)
```javascript
// Contrado ofrece API pero requiere contactar directamente
// para obtener documentación completa

POST https://api.contrado.com/v1/orders (ejemplo conceptual)
{
  "product_type": "mug_11oz",
  "design_url": "https://...",
  "shipping": { ... },
  "quantity": 1
}
```

### Productos Sugeridos Contrado

| Producto | Descripción | Precio Estimado |
|----------|-------------|-----------------|
| Taza 11oz Cerámica | Diseño all-over | €15-25 |
| Taza Premium 15oz | Mayor área de diseño | €20-30 |
| Pañuelo Seda 90×90cm | Alta calidad, bordes cosidos | €85-120 |
| Pañuelo Modal 70×70cm | Más accesible | €45-65 |
| Fular largo | Diseño artístico | €60-90 |

### Implementación Recomendada (Fases)

#### Fase 1: MVP (Semana 1-2)
```
[ ] Crear cuenta Contrado Business
[ ] Diseñar 2-3 productos iniciales (tazas + pañuelos)
[ ] Configurar tienda white-label Contrado
[ ] Agregar enlaces desde carolinasella.com/shop
[ ] Configuración de branding (logo, colores)
```

#### Fase 2: Integración Visual (Semana 2-3)
```
[ ] Mostrar productos Contrado en el frontend propio
[ ] Usar iFrame o modal para checkout en Contrado
[ ] O crear página de "productos externos" con enlaces
```

#### Fase 3: Automatización (Futuro)
```
[ ] Contactar Contrado para acceso API completo
[ ] Implementar creación de órdenes automatizada
[ ] Integrar con sistema de notificaciones
```

---

## 🔧 Plan de Implementación Global

### Fase 1: Infraestructura Base (Semana 1)

```
[ ] Crear cuentas en ambas plataformas
    [ ] Printful: Cuenta + API Token
    [ ] Contrado: Cuenta Business

[ ] Configurar Stripe para pagos
    [ ] Cuenta Stripe
    [ ] Claves API (test + production)
    [ ] Webhook endpoint

[ ] Preparar diseños de alta resolución
    [ ] Prints: 300 DPI, CMYK
    [ ] Tazas: Templates Contrado
    [ ] Pañuelos: Templates Contrado (2000×2000px mínimo)
```

### Fase 2: Printful Integration (Semana 2-3)

```
[ ] Desarrollar plugin WordPress "carolina-shop"
    [ ] Wrapper API Printful
    [ ] Custom Post Type: Products
    [ ] Integración Stripe Checkout
    [ ] Webhook receiver

[ ] Crear productos en Printful
    [ ] Subir diseños
    [ ] Configurar variantes y precios
    [ ] Generar mockups

[ ] Diseñar frontend del shop
    [ ] Grid de productos
    [ ] Página de producto individual
    [ ] Cart sidebar
    [ ] Checkout flow
```

### Fase 3: Contrado Setup (Semana 3-4)

```
[ ] Configurar tienda Contrado
    [ ] Branding
    [ ] Productos iniciales
    [ ] Políticas de envío

[ ] Integrar en frontend
    [ ] Sección "Tazas y Pañuelos"
    [ ] Cards con enlace a Contrado
    [ ] O modal con preview + redirect
```

### Fase 4: Testing y Launch (Semana 4-5)

```
[ ] Testing end-to-end
    [ ] Compra de prueba Printful (usar modo sandbox)
    [ ] Verificar webhooks
    [ ] Probar todos los tamaños/variantes

[ ] Optimización
    [ ] SEO de páginas de producto
    [ ] Performance (lazy loading imágenes)
    [ ] Mobile responsive

[ ] Launch
    [ ] Activar modo producción Stripe
    [ ] Confirmar órdenes en Printful
    [ ] Anunciar en redes sociales
```

---

## 💰 Costos Estimados

### Setup Inicial
| Item | Costo |
|------|-------|
| Printful | Gratis (pago por pedido) |
| Contrado | Gratis (pago por pedido) |
| Stripe | 2.9% + $0.30 por transacción |
| Dominio/Hosting | Ya existente |

### Por Pedido (Ejemplo: Print 12×16)
| Concepto | Monto |
|----------|-------|
| Precio venta | $45.00 |
| Costo Printful | -$12.00 |
| Envío (incluido en precio) | -$5.00 |
| Stripe fee | -$1.60 |
| **Ganancia neta** | **$26.40** |

---

## 📧 Emails Transaccionales

Configurar con el servicio de email existente:

1. **Confirmación de compra**: Inmediato post-pago
2. **Orden en producción**: Cuando Printful confirma
3. **Envío realizado**: Con número de tracking
4. **Entrega confirmada**: Follow-up opcional

---

## 🚀 Quick Start Checklist

```
INMEDIATO (Hoy):
[ ] Crear cuenta Printful
[ ] Generar API Token
[ ] Crear cuenta Contrado Business

ESTA SEMANA:
[ ] Preparar 3-5 diseños de alta resolución
[ ] Subir primer producto a Printful
[ ] Configurar tienda básica Contrado

PRÓXIMA SEMANA:
[ ] Instalar/desarrollar plugin WordPress
[ ] Configurar Stripe
[ ] Diseñar página del shop
```

---

## 📚 Recursos

- [Printful API Docs](https://developers.printful.com/docs/)
- [Printful Postman Collection](https://developers.printful.com/docs/#section/About-the-Printful-API/Postman-Collection)
- [Contrado Business](https://www.contrado.com/sell-your-designs)
- [Stripe Checkout Docs](https://stripe.com/docs/payments/checkout)

---

## ⚠️ Decisiones Pendientes

1. **¿Usar WooCommerce o plugin custom?**
   - WooCommerce: Más features, más complejo
   - Custom: Más ligero, control total

2. **¿Mostrar productos Contrado en frontend o solo enlazar?**
   - Frontend: Mejor UX pero más desarrollo
   - Enlace: Rápido pero cliente sale del sitio

3. **¿Precios en USD, EUR o ambos?**
   - Considerar mercado objetivo principal
