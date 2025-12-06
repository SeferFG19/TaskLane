<div class="projects-page">
    <div class="projects-header">
        <div>
            <h1 class="projects-title">Mis proyectos</h1>
            <p class="projects-subtitle">
                Pulsa en tareas para ir al tablero del proyecto.
            </p>
        </div>

        <div class="projects-header-actions">
            <input type="text" class="projects-search" placeholder="Buscar proyecto..." wire:model.live="texto">
            @if ($currentUser->is_admin)
            <button class="btn btn-one" wire:click="abrirCrearProyecto">
                + Crear proyecto
            </button>
            @endif
        </div>
    </div>

    <div class="projects-grid">
        @forelse($projects as $p)
        @php
        $pivotUser = $p->users->firstWhere('id', $currentUser->id);
        $isProjectAdmin = $currentUser->is_admin
        || $p->created_by === $currentUser->id
        || ($pivotUser && $pivotUser->pivot->role_id === $adminRoleId);
        $board = $p->boards->first();
        @endphp

        <article class="project-card">
            <div class="project-card-header">
                <h2 class="project-card-title">{{ $p->name }}</h2>
                <span class="project-card-id">#{{ $p->id }}</span>
            </div>

            <p class="project-card-description">
                {{ $p->description }}
            </p>

            <div class="project-card-footer">
                <div class="project-created-by">
                    <span class="project-created-by-label">Creador:</span>
                    <span class="project-created-by-value">
                        {{ optional($p->createdBy)->name ?? 'Desconocido' }}
                    </span>
                </div>
                <div class="project-card-buttons">
                    @if ($board)
                    <a href="{{ route('boards.show', $board->id) }}"
                        class="project-btn project-btn-one">
                        Tareas
                    </a>
                    @endif

                    @if($isProjectAdmin)
                    <button class="project-btn project-btn-two"
                        wire:click="editar({{ $p->id }})">
                        Editar
                    </button>

                    <button class="project-btn project-btn-three"
                        wire:click="confirmarBorrar({{ $p->id }})">
                        Borrar
                    </button>
                    @endif
                </div>
            </div>
        </article>
        @empty
        <p class="projects-empty">
            No tienes proyectos todavía.
        </p>
        @endforelse
    </div>

    <div class="projects-pagination">
        {{ $projects->links() }}
    </div>

    @if($openCreate || $openUpdate)
    <div class="modal-backdrop">
        <div class="modal">
            <h2 class="modal-title">
                {{ $openCreate ? 'Crear proyecto' : 'Editar proyecto' }}
            </h2>
            <label class="modal-label">Nombre</label>
            <input type="text" class="modal-input" wire:model="form.name">

            <label class="modal-label">Descripción</label>
            <textarea class="modal-textarea"
                wire:model="form.description"></textarea>

            <div class="modal-actions">
                @if($openCreate)
                <button class="btn btn-one" wire:click="store">
                    Crear
                </button>
                @else
                <button class="btn btn-two" wire:click="update">
                    Guardar cambios
                </button>
                @endif

                <button class="btn btn-three" wire:click="cancelar">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
    @endif
</div>