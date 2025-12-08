<div class="board-page">

    <header class="board-header">
        <div class="board-header-inner">
            <h1 class="board-title">{{ $project->name ?? '' }}</h1>
            @if($role === 'Admin')
            <div class="board-header-admin">
                <span class="badge-admin" aria-label="Rol: Administrador del proyecto">Admin de proyecto</span>
            </div>
            @endif
        </div>
    </header>

    <section class="board-container" aria-label="Columnas del tablero">
        @foreach($board->tlists as $tlist)
        <section class="board-column" data-list-id="{{ $tlist->id }}" aria-labelledby="list-title-{{ $tlist->id }}">
            <div class="board-column-header">
                <span class="board-column-title">{{ $tlist->name }}</span>
                @if($role === 'Admin')
                <button class="board-add-btn" aria-label="Crear nueva tarea en la lista {{ $tlist->name }}" wire:click="openCreateCard({{ $tlist->id }})">
                    + Tarea
                </button>
                @endif
            </div>

            <div class="board-column-body">
                @forelse($tlist->cards as $card)
                <article class="task-card" aria-label="Tarea: {{ $card->title }}">
                    <div class="task-card-top">
                        <h3 class="task-title">{{ $card->title }}</h3>

                        @if($card->assignedUser)
                        <span class="task-assignee">
                            {{ $card->assignedUser->name }}
                        </span>
                        @endif
                    </div>
                    @if($card->tags->count())
                    <div class="task-tags">
                        @foreach($card->tags as $tag)
                        <span class="task-tag"
                            style="background-color: {{ $tag->color }};">
                            {{ $tag->name }}
                        </span>
                        @endforeach
                    </div>
                    @endif

                    <p class="task-desc">
                        {{ $card->description }}
                    </p>

                    <div class="task-card-actions">
                        @if($role === 'Admin')
                        <button class="task-btn one" aria-label="Editar tarea {{ $card->title }}" wire:click="editCard({{ $card->id }})">
                            Editar
                        </button>
                        <button class="task-btn two"
                            wire:click="confirmarDeleteCard({{ $card->id }})">
                            Borrar
                        </button>
                        @endif

                        @if($role !== 'Supervisor')
                        <button class="task-btn comments"
                            wire:click="abrirComentarios({{ $card->id }})">
                            Comentarios ({{ $card->comments->count() }})
                        </button>
                        @endif
                    </div>

                    <div class="task-move-row">
                        @foreach($board->tlists as $otratlist)
                        @if($otratlist->id !== $tlist->id)
                        <button class="task-move-pill" aria-label="Mover tarea a la lista {{ $otratlist->name }}" wire:click="moverCard({{ $card->id }}, {{ $otratlist->id }})">
                            {{ $otratlist->name }}
                        </button>
                        @endif
                        @endforeach
                    </div>
                </article>
                @empty
                <p class="task-empty">Sin tareas</p>
                @endforelse
            </div>
        </section>
        @endforeach
    </section>

    @if($showOpenCreateCard)
    <div class="modal-backdrop">
        <div class="modal" aria-modal="true" aria-labelledby="modal-title-create" aria-describedby="modal-desc-create">
            <h2 class="modal-title">Crear tarea</h2>
            <label class="modal-label">Título</label>
            <input class="modal-input" type="text" wire:model="formCard.title">
            @error('formCard.title')
            <div class="error">{{ $message }}</div>
            @enderror
            <label class="modal-label">Descripción</label>
            <textarea class="modal-textarea" wire:model="formCard.description"></textarea>
            @error('formCard.description')
            <div class="error">{{ $message }}</div>
            @enderror

            @if($role === 'Admin')
            <label class="modal-label">Asignar a</label>
            <select class="modal-input" wire:model="formCard.assigned_to">
                <option value="">Sin asignar</option>
                @foreach($empleados as $emp)
                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                @endforeach
            </select>

            <label class="modal-label">Tags</label>
            <div class="tags-select">
                @foreach($availableTags as $tag)
                <label class="tag-checkbox">
                    <input type="checkbox" value="{{ $tag->id }}" wire:model="formCard.tags">
                    <span class="tag-checkbox-pill" style="background-color: {{ $tag->color }};">
                        {{ $tag->name }}
                    </span>
                </label>
                @endforeach
            </div>
            @endif

            <div class="modal-actions">
                <button class="btn btn-primary" wire:click="storeCard">
                    Crear
                </button>
                <button class="btn btn-three" wire:click="cancelTask">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
    @endif

    @if($showOpenUpdateCard)
    <div class="modal-backdrop">
        <div class="modal" aria-modal="true" aria-labelledby="modal-title-create" aria-describedby="modal-desc-create">
            <h2 class="modal-title">Editar tarea</h2>
            <label class="modal-label">Título</label>
            <input class="modal-input" type="text" wire:model="formCard.title">
            @error('formCard.title')
            <div class="error">{{ $message }}</div>
            @enderror
            <label class="modal-label">Descripción</label>
            <textarea class="modal-textarea" wire:model="formCard.description"></textarea>
            @error('formCard.description')
            <div class="error">{{ $message }}</div>
            @enderror

            @if($role === 'Admin')
            <label class="modal-label">Asignar a</label>
            <select class="modal-input" wire:model="formCard.assigned_to">
                <option value="">Sin asignar</option>
                @foreach($empleados as $emp)
                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                @endforeach
            </select>

            <label class="modal-label">Tags</label>
            <div class="tags-select">
                @foreach($availableTags as $tag)
                <label class="tag-checkbox">
                    <input type="checkbox" value="{{ $tag->id }}" wire:model="formCard.tags">
                    <span class="tag-checkbox-pill" style="background-color: {{ $tag->color }};">
                        {{ $tag->name }}
                    </span>
                </label>
                @endforeach
            </div>
            @endif

            <div class="modal-actions">
                <button class="btn btn-primary" wire:click="updateCard">
                    Guardar
                </button>
                <button class="btn btn-three" wire:click="cancelTask">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
    @endif

    @if($cardComentarios)
    <div class="modal-backdrop">
        <div class="modal" aria-modal="true" aria-labelledby="modal-title-create" aria-describedby="modal-desc-create">
            <h2 class="modal-title">Comentarios</h2>
            <div class="comments-list">
                @foreach($cardComentarios->comments as $com)
                <div class="comment-item">
                    <div class="comment-header">
                        <span class="comment-user">{{ $com->user->name }}</span>
                        <span class="comment-date">
                            {{ $com->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    <p class="comment-text">{{ $com->comment }}</p>
                </div>
                @endforeach
            </div>

            @if($role !== 'Supervisor')
            <label class="modal-label">Nuevo comentario</label>
            <textarea class="modal-textarea" wire:model="nuevoComentario"></textarea>
            <div class="modal-actions">
                <button class="btn btn-primary" wire:click="agregarComentario">
                    Añadir
                </button>
                <button class="btn btn-three" wire:click="cerrarComentarios">
                    Cerrar
                </button>
            </div>
            @else
            <div class="modal-actions">
                <button class="btn btn-secondary" wire:click="cerrarComentarios">
                    Cerrar
                </button>
            </div>
            @endif
        </div>
    </div>
    @endif

</div>