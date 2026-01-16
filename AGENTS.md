# Carolina Sella - Artist Profile & Ecommerce (CURRENT WORKFLOW)

## Descripción del Proyecto
Plataforma de e-commerce y portfolio para la artista Carolina Sella. El sitio exhibirá perfil de artista, pinturas, tatuajes y otros trabajos artísticos.

## ⚠️ NO GIT YET
**We are NOT using Git/Github yet.** Do not run git commands. Do not try to push/commit.
Changes are local only.

## 🧠 PERSISTENT MEMORY PROTOCOL (CRITICAL)
Since we don't have Git history, we rely on **Explicit Documentation Memory**.

1.  **BEFORE STARTED**:
    -   Read `DOCS/README.md`.
    -   Understand previous **Learnings** and **Struggles**.
    -   Check `DOCS/04-TODOS.xml` for active tasks.

2.  **DURING WORK**:
    -   If you discover a new trick/fix, **NOTE IT**.
    -   If you hit a wall/blocker, **NOTE IT**.

3.  **AFTER FINISHING**:
    -   **Update `DOCS/README.md`**:
        -   Add your new learnings to "Successful Workflows".
        -   Log any "Struggles & Blockers" so the next agent is warned.
        -   Update "Current Project State".
    -   **Update `DOCS/04-TODOS.xml`**: Mark completed tasks.

---

## Workflow "trabaja" (Current Scenario)

#### � Inicio de Sesión
1.  **Leer Memoria**: `DOCS/README.md`
2.  **Leer Tareas**: `DOCS/04-TODOS.xml`

#### � Ejecución
When receiving the single prompt **"trabaja"**:
- **Check** `DOCS/04-TODOS.xml` for pending tasks.
- **Execute tasks**.
- **Document everything** in `DOCS/README.md` (Memory) and relevant doc files.
- **NEVER use Git**.

#### � Auditoría (Sin Commit)
Al finalizar las tareas:
1.  **Auditoría Interna**: Revisar el código generado.
2.  **Limpieza**: Borrar scripts temporales.
3.  **Update Memory**: Escribir en `DOCS/README.md` qué se hizo, qué se aprendió y qué quedó pendiente.

---

## Documentation Rules

### File Management
- **❌ NEVER create new .md files** outside specific instructions. All docs in `DOCS/`.
- **📁 Documentation Location**: `/DOCS/`
- **📋 Index**: `DOCS/README.md` is the Master Index + Memory.

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
2. **Verificar tamaños de documentos**: Identificar files que excedan los límites

#### 🔄 Actualización de Documentos e Índices
**Regla importante**: Cuando se modifica un archivo, la IA debe:
1. **Actualizar el documento principal** con los cambios necesarios
2. **Actualizar todos los índices relevantes** que referencian ese documento

## � Documentation Structure
(See `DOCS/README.md` for live structure)

## 🎯 Priorities
1.  **Memory (`DOCS/README.md`)**: Read first, update last.
2.  **Tasks (`DOCS/04-TODOS.xml`)**: Execute efficiently.
3.  **Quality**: Valid code, no breakages.