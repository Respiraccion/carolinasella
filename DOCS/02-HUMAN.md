# 02 - HUMAN (Tasks & Feedback)

This file tracks tasks that require human action (sensitive data, content decisions, UI usage).

---

## 🚀 NEW PRIORITY TASKS (From Feedback)

### 0. Legal Pages Content (WordPress Editor) ⚖️ **NEW**
- **Task**: Add content to the Terms and Conditions and Privacy Policy pages.
- **Why**: EU law compliance (GDPR, ePrivacy Directive, Consumer Rights).
- **Status**: 
  - ✅ Pages created: `/terms-and-conditions/` and `/privacy-policy/`
  - ✅ Footer links added
### 0. Legal Pages Content (WordPress Editor) ⚖️ **DONE**
- **Task**: Terms and Conditions and Privacy Policy pages created and populated.
- **Why**: EU law compliance.
- **Status**: 
  - ✅ Pages created: `/terms-and-conditions/` and `/privacy-policy/`
  - ✅ Footer links added
  - ✅ Content populated (Markdown -> HTML Blocks)
- **Action Required**: None. You can review the pages on the frontend.

- **Note**: Both documents are comprehensive and EU-compliant. You may want to review:
  - Contact email (currently set to `hello@carolinasella.com`)
  - Business location (currently "Copenhagen, Denmark")
  - Any specific services or products mentioned


### 1. Home Image Adjustment (WordPress Editor)
- **Task**: The second image on Home is currently a circle. Change it to a **Rectangle**.
- **How**: 
  - Click the image block.
  - In Settings (right panel), look for **Styles**.
  - Uncheck "Rounded" or select "Default".

### 2. Shop Categories & Filters
- **Task**: Create/Assign detailed categories for products.
- **Why**: "I want filters: All, Prints, Decorative Objects, Giclee, Originals".
- **How**: 
  - Go to **Products > Categories**.
  - Create these categories.
  - Assign your products to them.

### 3. Menu Reorganization (Site Editor)
- **Task**: Fix the menu order.
- **Requirement**: "Shop" should be **before** "Blog" and **after** "Oracle Cards".
- **Requirement**: "Ink & Ritual" -> Remove sub-items dropdown. Only show the list of specialized pages ("Artistic Tattoos", "Tattoo Ritual") ON the page itself, or simplify the menu link.

### 4. Artistic Tattoos Content
- **Task**: Rename title to **"Artistic Tattoos by Nebula"**.
- **Content**: Link to Instagram `nebula.ttt` with text "Ver más".
- **Gallery**: Upload the photos for the gallery (Agent will handle the B&W effect).

### 5. Art Gallery Content
- **Task**: Upload paintings.
- **Text**: Add note to each: "Original not for sale yet. Giclee copies available. Join newsletter for updates."
- **Newsletter**: Add the Newsletter subscription form block.

### 6. Background Colors
- **Task**: Review site background colors in the Editor. Adjust if they feel off.

---

## ✉️ CONTACT FORM GUIDE

The contact form is successfully installed on `/contacto`.

- **Admin Menu**: Go to **Contact Forms** to view submissions.
- **Shortcode**: `[carolina_contact_form]` (Used on the page).
- **Settings**: Go to **Settings > Contact Form**.

---

## 📧 GUÍA COMPLETA DE EMAIL - PASO A PASO

Esta guía te permite:
- ✅ RECIBIR emails en `hello@carolinasella.com` (llegan a tu Gmail)
- ✅ RESPONDER desde tu iPhone/Gmail como `hello@carolinasella.com`
- ✅ WordPress ENVÍA emails (formulario de contacto, WooCommerce) como `hello@carolinasella.com`

---

# PARTE 1: CLOUDFLARE EMAIL ROUTING (Recibir emails)

## Paso 1.1: Acceder a Cloudflare
1. Abrí: **https://dash.cloudflare.com**
2. Iniciá sesión
3. Seleccioná el dominio **carolinasella.com**

## Paso 1.2: Ir a Email Routing
1. En el menú de la izquierda, buscá **"Email"**
2. Clickeá **"Email Routing"**

## Paso 1.3: Habilitar Email Routing (si no está habilitado)
1. Si ves un botón **"Get Started"** o **"Enable Email Routing"**, clickealo
2. Cloudflare te va a pedir agregar DNS records automáticamente
3. Clickeá **"Add records and enable"** o **"Agregar registros y habilitar"**
4. Esperá unos segundos hasta que se active

## Paso 1.4: Agregar tu Gmail como destino
1. Andá a la pestaña **"Destination addresses"** (Direcciones de destino)
2. Clickeá **"Add destination address"**
3. Escribí tu Gmail: `carolinasellasantecchia@gmail.com`
4. Clickeá **"Add"**
5. **IMPORTANTE**: Abrí tu Gmail, buscá el email de verificación de Cloudflare
6. Clickeá el link de verificación en ese email
7. Volvé a Cloudflare y verificá que el email aparezca como ✅ Verified

## Paso 1.5: Crear la ruta hello@carolinasella.com
1. Andá a la pestaña **"Email addresses"** o **"Routes"**
2. Clickeá **"Create address"** o **"Add address"**
3. En **"Custom address"**: escribí `hello` (sin el @)
4. En **"Action"**: seleccioná **"Send to an email"**
5. En **"Destination"**: seleccioná tu Gmail verificado
6. Clickeá **"Save"**

## Paso 1.6: Verificar que funciona
1. Desde otro email (no tu Gmail), enviá un email de prueba a `hello@carolinasella.com`
2. Revisá tu Gmail - debería llegar en 1-2 minutos
3. Si llega, ✅ Cloudflare Email Routing está funcionando

---

# PARTE 2: DNS RECORDS EN CLOUDFLARE (Autenticación de emails)

Para que los emails que ENVÍES no caigan en spam, necesitás estos DNS records.

## Paso 2.1: Ir a DNS
1. En Cloudflare, con tu dominio seleccionado
2. Menú izquierdo → **"DNS"** → **"Records"**

## Paso 2.2: Verificar records MX (deberían existir)
Deberías ver estos records MX (los agregó Cloudflare automáticamente):
| Type | Name | Content | Priority |
|------|------|---------|----------|
| MX | @ | `route1.mx.cloudflare.net` | 69 (o similar) |
| MX | @ | `route2.mx.cloudflare.net` | 12 (o similar) |
| MX | @ | `route3.mx.cloudflare.net` | 81 (o similar) |

Si no los ves, hay un problema con Email Routing. Volvé a Parte 1.

## Paso 2.3: Agregar SPF record
1. Clickeá **"Add record"**
2. Type: **TXT**
3. Name: **@**
4. Content: **`v=spf1 include:_spf.google.com include:_spf.mx.cloudflare.net ~all`**
5. TTL: Auto
6. Clickeá **"Save"**

**NOTA**: Si ya existe un record TXT con "v=spf1", NO crees uno nuevo. Editá el existente y combiná los includes.

## Paso 2.4: Agregar DMARC record
1. Clickeá **"Add record"**
2. Type: **TXT**
3. Name: **_dmarc**
4. Content: **`v=DMARC1; p=none; rua=mailto:hello@carolinasella.com`**
5. TTL: Auto
6. Clickeá **"Save"**

---

# PARTE 3: GMAIL - HABILITAR 2-STEP VERIFICATION

## Paso 3.1: Ir a seguridad de Google
1. Abrí: **https://myaccount.google.com/security**
2. Iniciá sesión con `carolinasellasantecchia@gmail.com`

## Paso 3.2: Habilitar verificación en 2 pasos
1. Buscá la sección **"Cómo accedes a Google"** o **"How you sign in to Google"**
2. Clickeá **"Verificación en 2 pasos"** o **"2-Step Verification"**
3. Clickeá **"Empezar"** o **"Get started"**
4. Seguí los pasos:
   - Te va a pedir verificar tu identidad con tu contraseña
   - Elegí un método: SMS a tu teléfono o Google Authenticator
   - Completá la verificación
5. Una vez habilitado, vas a ver opciones adicionales en esa página

---

# PARTE 4: GMAIL - CREAR APP PASSWORD

## Paso 4.1: Acceder a App Passwords
1. **DESPUÉS** de habilitar 2-Step Verification
2. Andá a: **https://myaccount.google.com/apppasswords**
3. O: En la página de 2-Step Verification, scrolleá hasta el final y buscá **"Contraseñas de aplicaciones"** o **"App passwords"**

**SI NO APARECE LA OPCIÓN:**
- Probá buscar en: https://myaccount.google.com (usá la barra de búsqueda y escribí "app passwords")
- Asegurate de que 2-Step Verification esté REALMENTE habilitado
- Esperá unos minutos después de habilitar 2-Step Verification

## Paso 4.2: Generar la contraseña
1. En **"Select app"**: elegí **"Other (Custom name)"** o **"Otra aplicación"**
2. Escribí un nombre: `FluentSMTP WordPress`
3. Clickeá **"Generate"** o **"Generar"**
4. Google te va a mostrar una **contraseña de 16 caracteres** (con espacios, tipo: `xxxx xxxx xxxx xxxx`)
5. **COPIÁ ESTA CONTRASEÑA AHORA** - solo se muestra una vez
6. Guardala en algún lugar seguro temporalmente

---

# PARTE 5: GMAIL - CONFIGURAR "SEND MAIL AS"

Esto permite que cuando respondas emails desde Gmail/iPhone, aparezca como `hello@carolinasella.com`.

## Paso 5.1: Ir a configuración de Gmail
1. Abrí Gmail en el navegador (no la app)
2. Clickeá el ícono de **⚙️ Configuración** (arriba a la derecha)
3. Clickeá **"Ver toda la configuración"** o **"See all settings"**

## Paso 5.2: Agregar dirección de envío
1. Andá a la pestaña **"Cuentas e importación"** o **"Accounts and Import"**
2. Buscá la sección **"Enviar como"** o **"Send mail as"**
3. Clickeá **"Añadir otra dirección de correo electrónico"** o **"Add another email address"**

## Paso 5.3: Completar el formulario (Ventana emergente)
**Primera pantalla:**
1. Nombre: `Carolina Sella`
2. Dirección de correo: `hello@carolinasella.com`
3. ☑️ Marcá **"Tratar como un alias"** / **"Treat as an alias"**
4. Clickeá **"Siguiente paso"** / **"Next Step"**

**Segunda pantalla (Servidor SMTP):**
1. Servidor SMTP: `smtp.gmail.com`
2. Puerto: `587`
3. Nombre de usuario: `carolinasellasantecchia@gmail.com` (tu Gmail completo)
4. Contraseña: **LA APP PASSWORD DE 16 CARACTERES** (de Parte 4)
5. Seleccioná: ☑️ **"Conexión segura con TLS"** / **"Secured connection using TLS"**
6. Clickeá **"Añadir cuenta"** / **"Add Account"**

## Paso 5.4: Verificar el email
1. Gmail te va a enviar un código de confirmación a... **¡tu propio Gmail!** (porque Cloudflare reenvía `hello@carolinasella.com` a tu Gmail)
2. Buscá ese email con el código
3. Copiá el código y pegalo en la ventana, o clickeá el link de confirmación
4. ¡Listo! Ahora podés enviar como `hello@carolinasella.com`

## Paso 5.5: (Opcional) Hacer default
1. En Gmail → Settings → Accounts and Import
2. En "Send mail as", al lado de `hello@carolinasella.com`
3. Clickeá **"Make default"** / **"Establecer como predeterminado"**

---

# PARTE 6: FLUENTSMTP EN WORDPRESS

## Paso 6.1: Acceder a FluentSMTP
1. Andá a: **https://carolinasella.com/wp-admin**
2. Menú izquierdo → **Settings** → **FluentSMTP**

## Paso 6.2: Borrar conexiones anteriores (si existen)
1. Si hay alguna conexión configurada, clickeá el ícono de basura 🗑️ para borrarla
2. Confirmá el borrado

## Paso 6.3: Crear nueva conexión
1. Clickeá **"Add New Connection"** o el botón para agregar
2. Elegí **"Other SMTP"** (el primero de la lista)

## Paso 6.4: Completar formulario SMTP
Completá EXACTAMENTE así:

| Campo | Valor |
|-------|-------|
| **From Email** | `carolinasellasantecchia@gmail.com` |
| **From Name** | `Carolina Sella` |
| **Return Path** | ☑️ Marcado (Set the return-path to match From Email) |
| **SMTP Host** | `smtp.gmail.com` |
| **SMTP Port** | `587` |
| **Encryption** | `TLS` |
| **Use Auto TLS** | ☑️ Yes |
| **Authentication** | ☑️ Yes |
| **SMTP Username** | `carolinasellasantecchia@gmail.com` |
| **SMTP Password** | La App Password de 16 caracteres (sin espacios) |
| **Disable Encryption for Password** | ❌ NO (dejá desmarcado) |

**⚠️ IMPORTANTE**: El "From Email" DEBE ser tu Gmail, no `hello@carolinasella.com`. Gmail solo permite enviar con el email autenticado O con alias verificados en Gmail (que configuramos en Parte 5).

## Paso 6.5: Guardar
1. Clickeá **"Save Connection Settings"**
2. Debería aparecer un mensaje de éxito

## Paso 6.6: Probar el envío
1. Andá a la pestaña **"Email Test"** (arriba)
2. En "Send To": poné otro email tuyo diferente (para probar), o el mismo Gmail
3. Clickeá **"Send Test Email"**
4. Revisá el email destino - debería llegar en 1-2 minutos
5. Si llega, ✅ FluentSMTP está funcionando

## Paso 6.7: (Opcional) Cambiar From Email a hello@
**SOLO después de haber completado la Parte 5 correctamente:**
1. Volvé a Settings en FluentSMTP
2. Editá la conexión
3. Cambiá "From Email" a `hello@carolinasella.com`
4. Guardá y probá de nuevo

Si no funciona con `hello@`, dejalo con tu Gmail. Los emails se van a enviar igual.

---

# PARTE 7: IPHONE MAIL APP

## Paso 7.1: Agregar cuenta Gmail (si no la tenés)
1. iPhone → **Ajustes** → **Mail** → **Cuentas** → **Añadir cuenta**
2. Seleccioná **Google**
3. Iniciá sesión con `carolinasellasantecchia@gmail.com`
4. Habilitá **Mail**

## Paso 7.2: Enviar como hello@carolinasella.com
1. Abrí la app **Mail**
2. Creá un nuevo email
3. Tocá el campo **"De:"** / **"From:"**
4. Te va a mostrar las opciones - elegí `hello@carolinasella.com`
5. ¡Listo! El email se va a enviar como Carolina Sella <hello@carolinasella.com>

---

# CHECKLIST FINAL

- [ ] **Cloudflare**: Email Routing habilitado
- [ ] **Cloudflare**: Ruta `hello` → tu Gmail creada
- [ ] **Cloudflare**: DNS records SPF y DMARC agregados
- [ ] **Gmail**: 2-Step Verification habilitado
- [ ] **Gmail**: App Password generada
- [ ] **Gmail**: "Send mail as" configurado para `hello@carolinasella.com`
- [ ] **WordPress**: FluentSMTP configurado con Gmail SMTP
- [ ] **WordPress**: Test email enviado correctamente
- [ ] **iPhone**: Gmail agregado y podés elegir `hello@` como remitente

---

# TROUBLESHOOTING

## "FluentSMTP dice 'sent' pero no llega el email"
1. Verificá que el "From Email" en FluentSMTP sea tu Gmail, no `hello@`
2. Revisá spam y todas las carpetas de Gmail
3. Esperá 5 minutos y revisá de nuevo

## "Gmail no me deja crear App Password"
1. Verificá que 2-Step Verification esté HABILITADO
2. Probá cerrar sesión y volver a entrar a Google
3. Usá el buscador en myaccount.google.com y escribí "app passwords"

## "No puedo enviar como hello@ desde Gmail"
1. Verificá que completaste la Parte 5 correctamente
2. El email de verificación llega a tu propio Gmail (Cloudflare lo reenvía)
3. Si dice "error de autenticación", la App Password está mal copiada

## "Los emails de WordPress van a spam"
1. Verificá que los DNS records SPF y DMARC estén correctos
2. Esperá 24 horas después de agregar DNS records
3. Considerá usar Brevo en lugar de Gmail (mejor deliverability)

---

## 📝 NOTES FOR AGENT
*Write here any feedback or new requests...*

