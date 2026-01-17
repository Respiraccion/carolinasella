# Carolina Sella - Artist Profile & Ecommerce

## 👤 QUIÉN HACE QUÉ

### ✅ TAREAS HUMANAS (WordPress UI)
La programadora puede hacer esto sola desde WordPress:
- Editar contenido de páginas
- Cambiar colores/fuentes de bloques individuales
- Subir imágenes
- Añadir/editar productos en WooCommerce
- Modificar menú de navegación
- Cambiar espaciado entre bloques

**📖 Guía completa**: `DOCS/03-WORDPRESS-GUIDE.md`
**🌳 ¿Dónde lo cambio?**: `DOCS/DECISION-TREE.md`

### 🤖 TAREAS DEL AGENTE (Requieren código)
Pedir al agente cuando necesites:
- Cambios en **móvil/responsive** (media queries)
- Estilos del **header/footer** (personalizado en CSS)
- Añadir **fuentes nuevas** (theme.json)
- Cambios **globales** que afecten todo el sitio
- Arreglar errores de WooCommerce/Printful
- Cualquier cosa que no puedas hacer desde WordPress

---

## 🛠️ Technology & Specs
- **Platform**: WordPress
- **Theme**: Twenty Twenty-Five Child (`twentytwentyfive-child`)
- **Domain**: [carolinasella.com](https://carolinasella.com)
- **Server IP**: 157.180.70.21

### Site Structure (Pages)
- **Home** (Front Page)
- **Shop** (Main Store) - Cart, Checkout, My Account
- **Prints** (Printful Integrated)
- **Art gallery** - Project Alpha, Project Beta
- **Tattoos** - Ink & ritual, Artistic Tattoos
- **Oracle Cards** - Bach Flowers Oracle
- **Blog**
- **Contacto**

### E-Commerce
- **Platform**: WooCommerce + Printful
- **Payments**: Stripe (Pending human config)

---

## 📚 Documentación

| Archivo | Propósito |
|---------|-----------|
| `DOCS/DECISION-TREE.md` | 🌳 ¿Dónde cambio X? |
| `DOCS/03-WORDPRESS-GUIDE.md` | 📖 Guía paso a paso |
| `DOCS/TIPOGRAFIAS-GUIA.md` | ✏️ Referencia tipografías |
| `DOCS/00-Domain-memory.md` | 🧠 Memoria del proyecto |
| `DOCS/02-HUMAN.md` | ✅ Tareas pendientes humanas |
| `DOCS/01-TODOS.xml` | 📋 Lista de tareas activas |

---

## 🎨 Jerarquía de Estilos (IMPORTANTE)

```
1. WordPress Site Editor  ← PRIMERO (humano puede editar)
2. theme.json             ← SEGUNDO (colores, fuentes base)
3. style.css              ← ÚLTIMO (solo overrides complejos)
```

**Regla**: Si se puede hacer en WordPress UI, NO lo hagas en código.

---

## 🛑 Protocolo Anti-Alucinación (Para Agentes)

1. **Nunca asumir** - Siempre verificar archivos con `view_file`
2. **Rutas absolutas** - Solo usar paths completos
3. **Verificar cambios** - Confirmar después de cada modificación
4. **Preguntar si hay dudas** - Mejor preguntar que romper algo

### Antes de empezar:
- Leer `AGENTS.md` (este archivo)
- Revisar `DOCS/00-Domain-memory.md`

### Después de terminar:
- Actualizar `DOCS/00-Domain-memory.md` con aprendizajes
- Marcar tareas completadas en `DOCS/01-TODOS.xml`

---

## ⚠️ NO GIT YET
No correr comandos git. Los cambios son locales al servidor.