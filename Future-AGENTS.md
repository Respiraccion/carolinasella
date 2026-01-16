# Carolina Sella - Artist Profile & Ecommerce

## Descripción del Proyecto
Plataforma de e-commerce y portfolio para la artista Carolina Sella. El sitio exhibirá perfil de artista, pinturas, tatuajes y otros trabajos artísticos.
(Original template text: Adaptogenia - E-commerce de Tinturas... replaced based on user context).

## Stack Tecnológico
- **CMS/E-commerce**: WordPress + WooCommerce (Inferred context, keeping valid if true)
- **Local Environment**: LocalWP (Inferred from file path)
- **Hosting**: TBD

## Workflow de Desarrollo (FUTURE - WITH GIT)
Usar comando "trabaja" para desarrollo iterativo con IA. Ver DOCS/ para documentación completa.

## Documentation Rules

### File Management
- **❌ NEVER create new .md files** - All documentation goes in `DOCS/` folder
- **📁 Documentation Location**: All docs are in `/DOCS/` directory
- **📋 Index**: `DOCS/README.md` is the main index and entry point
- **🔄 Update After Actions**: Modify relevant documentation after every action
- **👥 Roles**:
  - `DOCS/04-TODOS.xml` - AI tasks (highest priority)
  - `DOCS/04-HUMAN.md` - Human tasks (minimize human work)

### Workflow
1. **Check TODOS.xml** for pending AI tasks
2. **Execute tasks in order**
3. **Update relevant docs** after completion
4. **Mark tasks as completed** in TODOS.xml

### 🔄 Workflow de Trabajo con IA - Comando "trabaja"

#### 📋 Inicio de Sesión de Trabajo
Cada sesión de trabajo comienza con:
1. **Limpiar Git**: Asegurar que todo esté en estado limpio (git status clean)
2. **Publicar cambios pendientes**: Hacer push de todo lo que esté listo
3. **Estado Cero**: Partir desde un estado conocido y limpio

#### 🎯 Comando "trabaja" - Ejecución Principal
When receiving the single prompt **"trabaja"**:
- **Automatically check** `DOCS/04-TODOS.xml` for pending tasks
- **Start working immediately** on highest priority tasks (índice de TODOs)
- **Document progress** and new steps discovered during development
- **Update todos** as tasks are developed and completed
- **Always update docs** after any action
- **Never create new .xml or .md files** - only modify existing TODOS.xml
- **Delete temporary scripts** that won't be used again
- **Ampliar contexto**: Si una tarea en TODOS.xml no es completamente clara, la IA debe ampliarla durante la ejecución

#### 🔍 Fall Back: Auditoría Pre-Commit
Una vez se termina de hacer todo lo que hay en TODOS.xml:
1. **Revisar cambios en Git**: Auditar todo lo modificado ANTES de hacer push
2. **Enfoque de auditoría**:
    - ❌ Buscar errores
    - 📈 Evaluar escalabilidad
    - 🔧 Verificar mantenibilidad
    - 🚀 Identificar optimizaciones futuras
    - 🔮 Considerar puntos de mejora a futuro
3. **Archivar TODOS actual**: Guardar con fecha del día (ej: `TODOS-2025-12-03.xml`)
4. **Registrar en TODOS List**: Anotar el nombre del commit en el archivo de lista de TODOs

#### 📝 Commit y Primera Auditoría
1. **Hacer commit** con nombre descriptivo
2. **Crear nueva TODOS.xml**: Con la revisión y auditoría de todo lo que ya se hizo
3. **Incluir optimizaciones**: Agregar TODOs para las optimizaciones identificadas
4. **Comando "trabaja"**: Empezar a pasar por la lista de auditoría/optimización

#### 🔁 Segunda Auditoría: Auditoría de Auditoría
Una vez se termina la primera auditoría:
1. **Guardar en archivos de TODOs**: Con la misma fecha pero con su commit particular
2. **Commit específico**: Llamado "Auditoría" o "Audit"
3. **Crear nueva TODOS.xml**: Para auditoría general del sistema

#### 🌟 Auditoría General del Sistema
Esta auditoría incluye una revisión completa:
- 🔐 **Seguridad**: Vulnerabilidades, autenticación, autorización
- ⚡ **Performance**: Optimizaciones, caching, queries
- 🔌 **Integraciones**: APIs, servicios externos, sincronizaciones
- 🐛 **Posibles errores**: Edge cases, validaciones, manejo de errores
- 🎨 **Optimizaciones**: Código duplicado, refactoring, mejores prácticas
- 👥 **Usabilidad del usuario**: UX/UI, accesibilidad, responsive
- 📱 **Responsividad móvil**: PWA, mobile-first, touch interactions

#### 🤖 Creatividad de la IA en Auditoría General
La IA debe ser **sumamente creativa** en buscar puntos de trabajo nuevo:
- **Revisar todo el sistema** exhaustivamente
- **Buscar qué no fue hecho** que debería estar
- **Identificar qué se puede mejorar** aunque funcione
- **Generar TODOs completos** con mucho contexto para la próxima sesión

#### 👤 Integración de Cambios Humanos
Al principio de cualquier sesión:
1. **Revisar archivo de humano** (`DOCS/04-HUMAN.md`)
2. **Si fue modificado**: Registrar en TODOS.xml qué cambios hay
3. **Indicar integración necesaria**: "El humano hizo X, hay que integrarlo"
4. **Desbloquear nuevas acciones**: Los cambios humanos pueden habilitar nuevas tareas para la IA

#### 🎯 Modo de Trabajo Actual
En este momento el sistema está en **modo configuración inicial**:
- ✅ Se está configurando la base del proyecto
- 🔍 Se están definiendo las tecnologías y arquitectura
- 🛠️ Desarrollo inicial de estructura y configuración
- 🎨 Enfoque en setup correcto antes del desarrollo principal

#### 📚 Mantenimiento del Índice
- **Mantener buen índice** en TODOS.xml
- **Generar TODOs con mucho contexto** para que la IA entienda
- **La IA debe entender**: Tal vez la descripción de una tarea no es completamente clara
- **Ampliar en ejecución**: La IA debe expandir el contexto al ejecutar

#### 🌐 Verificación Web
**Solamente al final de cada proceso** (después de commit):
- Levantar la web
- Hacer un **reseteo muy agresivo** de:
  - Caché del navegador
  - Todas las dependencias que hay que resetear
- Verificar que todo funcione correctamente

### TODOS.xml Optimization (Proactive)
When working on tasks, be highly proactive in optimizing TODOS.xml:
- **State relevant files** to the implementation being worked on
- **Create review steps** for any steps that need reviewing
- **Add future optimization todos** for performance, security, or maintainability improvements
- **Break down complex tasks** into specific, actionable steps
- **Identify dependencies** between tasks and note them
- **Suggest improvements** to existing implementations

### 📚 Documentation Maintenance & RAG Optimization

#### 🎯 Priority: Mantener RAG útil y eficiente
La IA debe entender que **mantener documentación limpia y optimizada para RAG es una prioridad**.

#### 📏 Límites de Tamaño de Documentos
**Límites recomendados** (para optimizar contexto y RAG):
- **Documentos Markdown (.md)**: Máximo **500 líneas**
- **Documentos XML (.xml)**: Máximo **800 líneas**
- **Archivos de código**: Máximo **300 líneas** (preferiblemente menos)

#### 🔍 Revisión Periódica del Índice
De tiempo en tiempo, la IA debe:
1. **Revisar el índice** (`DOCS/README.md`) para asegurar que esté limpio y bien organizado
2. **Verificar tamaños de documentos**: Identificar archivos que excedan los límites
3. **Evaluar estructura**: Determinar si la organización actual es óptima

#### ✂️ División de Documentos Grandes
Cuando un documento se vuelve demasiado grande:
1. **Analizar contenido**: Identificar secciones lógicas para dividir
2. **Crear subdocumentos**: Dividir en partes coherentes y bien nombradas
    - Ejemplo: `04-TODOS.xml` → `04-TODOS-ACTIVE.xml`, `04-TODOS-COMPLETED.xml`, `04-TODOS-ARCHIVED.xml`
    - Ejemplo: `README.md` → `README.md` (overview), `README-WORKFLOW.md`, `README-GUIDELINES.md`
3. **Actualizar índices**: Modificar `DOCS/README.md` y otros índices relevantes
4. **Mantener coherencia**: Asegurar que las referencias entre documentos funcionen
5. **Documentar cambios**: Registrar la división en `TODOS-HISTORY.md`

#### 🔄 Actualización de Documentos e Índices
**Regla importante**: Cuando se modifica un archivo, la IA debe:
1. **Actualizar el documento principal** con los cambios necesarios
2. **Actualizar todos los índices relevantes** que referencian ese documento
3. **Verificar referencias cruzadas** en otros documentos
4. **Mantener sincronización** entre documentación y código

## 📂 Documentation Structure
(Check DOCS/README.md for current structure)

## 🎯 Priorities
1. **TODOS.xml** - Critical AI tasks
2. **Technical configuration**
3. **Implementation**
4. **Documentation**
