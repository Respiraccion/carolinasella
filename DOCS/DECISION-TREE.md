# 🌳 ÁRBOL DE DECISIONES - ¿Dónde cambio esto?

Guía rápida para saber dónde hacer cada cambio. Busca tu tarea y sigue las instrucciones.

---

## 🖼️ IMÁGENES

### "Quiero que las imágenes carguen más rápido"
→ **Ya está configurado automáticamente** (ver `functions.php` → image-optimization.php)

### "Quiero cambiar el tamaño de una imagen específica"
→ **WordPress** → Medios → Clic en la imagen → Editar → Escala/Recortar

### "Las imágenes se ven borrosas o pixeladas"
→ **Sube imágenes más grandes** (mínimo 1200px de ancho) - WordPress las optimiza automáticamente

### "Quiero que TODAS las imágenes sean más grandes/pequeñas en una sección"
→ **Pedir al Agente** - Esto requiere cambios en CSS

---

## 🎨 COLORES

### "Quiero cambiar el color de fondo del sitio"
→ **WordPress** → Apariencia → Editor → Estilos → Colores → Fondo

### "Quiero cambiar el color del texto"
→ **WordPress** → Apariencia → Editor → Estilos → Tipografía → Color

### "Quiero cambiar el color de un bloque específico"
→ **WordPress** → Editar la página → Clic en el bloque → Panel derecho → Color

### "Quiero añadir un color nuevo a la paleta"
→ **Pedir al Agente** - Esto requiere editar `theme.json`

---

## ✏️ TIPOGRAFÍA (FUENTES)

### "Quiero cambiar el tamaño del texto en una página"
→ **WordPress** → Editar página → Seleccionar texto → Panel derecho → Tipografía

### "Quiero cambiar la fuente de TODO el sitio"
→ **WordPress** → Apariencia → Editor → Estilos → Tipografía

### "Quiero añadir una fuente nueva"
→ **Pedir al Agente** - Esto requiere editar `theme.json` y posiblemente subir archivos

---

## 📱 PROBLEMAS EN MÓVIL

### "El texto se ve demasiado pequeño/grande en móvil"
→ **Pedir al Agente** - Los tamaños responsive están en `style.css`

### "Los márgenes no se ven bien en móvil"
→ **Pedir al Agente** - Las media queries están en `style.css`

### "Un elemento no cabe bien en pantalla pequeña"
→ **Pedir al Agente** - Requiere ajustes CSS específicos

---

## 📐 ESPACIADO Y MÁRGENES

### "Quiero más/menos espacio entre bloques"
→ **WordPress** → Editar página → Seleccionar bloque → Panel derecho → Dimensiones

### "Quiero cambiar el padding de un bloque"
→ **WordPress** → Editar página → Seleccionar bloque → Panel derecho → Dimensiones → Relleno

### "Quiero cambiar márgenes globales de todo el sitio"
→ **Pedir al Agente** - Esto va en `theme.json` o `style.css`

---

## 🏠 HEADER / MENÚ

### "Quiero añadir/quitar páginas del menú"
→ **WordPress** → Apariencia → Editor → Patrones → Header → Editar navegación

### "Quiero cambiar el logo/título del sitio"
→ **WordPress** → Apariencia → Editor → Patrones → Header → Clic en título

### "Quiero cambiar el estilo del menú (colores, tamaño)"
→ **Pedir al Agente** - El header está personalizado en `style.css`

---

## 🦶 FOOTER

### "Quiero editar el contenido del footer"
→ **WordPress** → Apariencia → Editor → Patrones → Footer

---

## 🛒 TIENDA (WooCommerce)

### "Quiero añadir/editar productos"
→ **WordPress** → Productos → Añadir nuevo

### "Quiero cambiar precios"
→ **WordPress** → Productos → Editar producto → Datos del producto

### "El checkout no funciona"
→ **Pedir al Agente** - Puede ser configuración de Stripe/Printful

---

## 📄 PÁGINAS

### "Quiero crear una página nueva"
→ **WordPress** → Páginas → Añadir nueva

### "Quiero editar el contenido de una página"
→ **WordPress** → Páginas → Editar con editor de bloques

### "Quiero cambiar el diseño/estructura de una página"
→ **WordPress** → Páginas → Editar → Añadir bloques (Group, Columns, Cover, etc.)

---

## ❓ ¿NO ESTÁ EN LA LISTA?

**Pregunta al Agente describiendo:**
1. Qué quieres lograr
2. Dónde lo quieres (qué página/sección)
3. Cómo se ve actualmente vs cómo lo quieres

---

## 📁 REFERENCIA: Archivos Importantes

| Archivo | Qué contiene | ¿Tocarlo? |
|---------|--------------|-----------|
| `theme.json` | Colores, fuentes, tamaños base | Solo agente |
| `style.css` | Estilos avanzados y responsive | Solo agente |
| `functions.php` | Funcionalidad PHP | Solo agente |
| WordPress UI | Todo lo demás | ✅ Human OK |
