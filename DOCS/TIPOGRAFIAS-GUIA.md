# 📝 GUÍA DE TIPOGRAFÍAS - CAROLINA SELLA

Esta guía te permite controlar todas las tipografías desde WordPress.

## 🎨 CONFIGURACIÓN ACTUAL

### **DESKTOP (Pantallas grandes > 1024px)**

#### Títulos Principales (H1)
- **Fuente**: Cormorant Garamond
- **Tamaño**: `clamp(2.5rem, 5vw, 4rem)` → Entre 40px y 64px
- **Color**: Grafito (#3A3A3A)
- **Peso**: 400 (Regular)
- **Espaciado**: -0.02em (más compacto)
- **Uso**: Título principal de página

#### Subtítulos (H2) - "Artist Healer", "My Story"
- **Fuente**: Cormorant Garamond
- **Tamaño**: `clamp(1.8rem, 3vw, 2.5rem)` → Entre 28.8px y 40px
- **Color**: Grafito (#3A3A3A)
- **Peso**: 300 (Light)
- **Uso**: Secciones principales

#### Subtítulos Secundarios (H3)
- **Fuente**: Cormorant Garamond
- **Tamaño**: `clamp(1.4rem, 2.5vw, 1.8rem)` → Entre 22.4px y 28.8px
- **Color**: Grafito (#3A3A3A)
- **Peso**: 400 (Regular)

#### Texto de Párrafo (P)
- **Fuente**: Lato
- **Tamaño**: 1.125rem (18px)
- **Color**: Grafito (#3A3A3A)
- **Peso**: 400 (Regular)
- **Interlineado**: 1.8 (espaciado generoso para legibilidad)

---

### **MÓVIL PEQUEÑO (< 480px)**

#### H1
- **Tamaño**: `clamp(1.75rem, 5vw, 2.25rem)` → Entre 28px y 36px

#### H2 - "Artist Healer", "My Story" ⭐ AGRANDADO
- **Tamaño**: `clamp(1.625rem, 4.5vw, 2.125rem)` → Entre 26px y 34px
- **Nota**: Se aumentó 2 puntos (0.125rem = 2px)

#### H3
- **Tamaño**: `clamp(1.25rem, 4vw, 1.75rem)` → Entre 20px y 28px

#### Párrafo
- **Tamaño**: 16px (--mobile-base-font)
- **Interlineado**: 1.6

---

### **MÓVIL MEDIANO (480px - 767px)**

#### Párrafo
- **Tamaño**: 17px (mobile-base-font + 1px)
- Los títulos heredan los tamaños de móvil pequeño

---

### **TABLET (768px - 1024px)**

#### Párrafo
- **Tamaño**: 17px (--tablet-base-font)
- Los títulos usan los tamaños de desktop

---

## 🔧 CÓMO PERSONALIZAR DESDE WORDPRESS

### **Opción 1: Editor de Bloques (Recomendado)**
1. Ve a **Apariencia → Editor**
2. Haz clic en el bloque de texto que quieres editar
3. En el panel derecho, ajusta:
   - **Tipografía** → Tamaño de fuente
   - **Color** → Color del texto
   - **Apariencia** → Peso de fuente

### **Opción 2: CSS Adicional (Para cambios globales)**
Ve a **Apariencia → Personalizar → CSS Adicional** y agrega:

```css
/* Cambiar tamaño de subtítulos H2 en desktop */
h2 {
    font-size: 3rem !important; /* 48px */
}

/* Cambiar tamaño de subtítulos H2 en móvil */
@media (max-width: 767px) {
    h2 {
        font-size: 2rem !important; /* 32px */
    }
}

/* Cambiar color de todos los títulos */
h1, h2, h3 {
    color: #5F6C73 !important; /* Azul Humo */
}

/* Cambiar fuente de párrafos */
p {
    font-family: 'EB Garamond', serif !important;
    font-size: 1.25rem !important; /* 20px */
}
```

### **Opción 3: Variables CSS (Más flexible)**
```css
:root {
    /* Cambiar tamaños base */
    --mobile-base-font: 18px;  /* Default: 16px */
    --tablet-base-font: 19px;  /* Default: 17px */
}
```

---

## 🎨 PALETA DE COLORES DISPONIBLE

Usa estos nombres en WordPress:

- **Grafito**: `#3A3A3A` (Negro suave - texto principal)
- **Azul Humo**: `#5F6C73` (Gris azulado)
- **Violeta Polvo**: `#A59BB3` (Lila suave)
- **Gris Niebla**: `#D8D6D2`
- **Gris Muy Claro**: `#F1F1EF`
- **Fondo Principal**: `#E8E8E4` (Beige claro)
- **Blanco**: `#FFFFFF`
- **Bordó Claro**: `#E5D5D5`

---

## 📱 MÁRGENES MÓVILES CONFIGURABLES

```css
:root {
    --mobile-page-margin: 20px;        /* Móviles pequeños */
    --mobile-medium-margin: 24px;      /* Móviles medianos */
    --tablet-page-margin: 32px;        /* Tablets */
}
```

---

## 💡 TIPS DE DISEÑO

### **Jerarquía Visual**
- **H1**: Solo uno por página, el más grande
- **H2**: Secciones principales (Artist Healer, My Story)
- **H3**: Subsecciones
- **P**: Texto de lectura

### **Legibilidad**
- Mantén el interlineado en 1.6-1.8 para párrafos
- No uses más de 3 fuentes diferentes
- El texto debe tener contraste suficiente con el fondo

### **Responsive**
- Los tamaños usan `clamp()` para escalar automáticamente
- No necesitas definir tamaños para cada breakpoint

---

## 📞 SOPORTE

Si necesitas cambios más complejos, edita directamente:
- **Archivo**: `/wp-content/themes/twentytwentyfive-child/style.css`
- **Sección**: Busca "RESPONSIVE DESIGN - MOBILE & TABLET"
