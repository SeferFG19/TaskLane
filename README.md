# Tasklane — Gestor de Proyectos
**Autor:** *Sergio Ferrón García* 

Aplicación web desarrollada en **Laravel 12**, **Livewire 3** y **Jetstream**, diseñada para gestionar proyectos, tareas y flujos de trabajo de forma visual, intuitiva y colaborativa.

Tasklane permite a administradores y equipos organizarse mediante tableros estilo **Kanban/Jira**, asignar tareas, añadir comentarios y controlar los roles de los usuarios dentro de cada proyecto.

---

## Características principales

### Autenticación y Seguridad
- Registro e inicio de sesión mediante **Laravel Fortify / Jetstream**
- Foto de perfil, configuración de cuenta y API Tokens
- Middleware y políticas integradas
- Accesibilidad mejorada con ARIA

---

### Gestión de Roles
Tasklane incluye tres niveles de permisos:

| Rol         | Puede crear tareas | Puede asignar | Puede mover                | Puede comentar | Puede editar proyecto |
|------------|--------------------|---------------|----------------------------|----------------|------------------------|
| **Admin**      | ✔️                 | ✔️            | ✔️ (todas)                 | ✔️             | ✔️                     |
| **Empleado**   | ❌                 | ❌            | ✔️ (solo las asignadas)    | ✔️             | ❌                     |
| **Supervisor** | ❌                 | ❌            | ❌                         | ✔️             | ❌                     |

---

### Gestión de Proyectos
- Crear, editar y eliminar proyectos
- Asignar empleados al proyecto
- Cada proyecto contiene automáticamente:
  - 1 tablero principal
  - 5 listas por defecto: **Importante**, **En curso**, **Pendiente**, **Revisión**, **Completado**
  - Tags configurables por tarea

---

### Tareas
- Crear / Editar / Eliminar tareas
- Asignar empleados a cada tarea
- Añadir múltiples tags por tarea (Hot Fix, FrontEnd, BackEnd, Debug, Excelencia, etc.)
- Visualización en columnas estilo **Kanban**
- Botones para mover las tareas entre columnas
- Comentarios internos por tarjeta
- Validación en tiempo real con Livewire

---

### Comentarios
- Comentarios por tarea con fecha y usuario
- Control de permisos 
- Modal accesible y fácil de usar

---

### Interfaz moderna
- Diseño responsive
- Funcionalidad inspirada en Jira / Trello
- Navbar personalizada
- Landing page con hero, secciones informativas y CTA
- SweetAlert2 integrado para notificaciones
- Fuente **Roboto Mono** para mejorar legibilidad

---

## Tecnologías utilizadas

| Tecnología          | Uso                           |
|---------------------|-------------------------------|
| **Laravel 12**      | Backend principal             |
| **Livewire 3**      | Componentes dinámicos         |
| **Jetstream**       | Autenticación y configuración |
| **CSS**             | Estilos base                  |
| **SweetAlert2**     | Alertas modernas              |
| **SQLite**          | Base de datos                 |
| **PHP 8.3**         | Requisito mínimo              |

---
