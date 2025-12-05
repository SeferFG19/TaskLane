<div class="dashboard">

    <div class="board-container">
        @foreach($board->tlists as $tlist)
        <div class="board-list">
            <h3>{{ $tlist->name }}</h3>
            @if($role === 'Admin')
            <button class="btn btn-one btn-sm"
                wire:click="openCreateCard({{ $tlist->id }})">
                + Añadir tarea
            </button>
            @endif

            <br><br>
            @foreach($tlist->cards as $card)
            <div class="card">
                <strong>{{ $card->title }}</strong><br>
                <small>{{ $card->description }}</small><br>
                @if($card->assignedUser)
                <small>Asignada a: {{ $card->assignedUser->name }}</small>
                @endif

                @if($card->tags->count())
                <div style="margin-top: 6px;">
                    @foreach($card->tags as $tag)
                    <span style="display:inline-block; padding:2px 6px; border-radius:4px; font-size:0.7rem; background-color: {{ $tag->color }}; color:#fff; margin-right:4px;">
                        {{ $tag->name }}
                    </span>
                    @endforeach
                </div>
                @endif

                <br><br>

                @if($role === 'Admin')
                <button class="btn btn-one btn-sm"
                    wire:click="editCard({{ $card->id }})">
                    Editar
                </button>

                <button class="btn btn-three btn-sm"
                    wire:click="confirmarDeleteCard({{ $card->id }})">
                    Borrar
                </button>
                @endif

                @if($role !== 'Supervisor')
                <button class="btn btn-two btn-sm"
                    wire:click="abrirComentarios({{ $card->id }})">
                    Comentarios ({{ $card->comments->count() }})
                </button>
                @endif

                @foreach($board->tlists as $otratlist)
                @if($otratlist->id !== $tlist->id)
                <button class="btn btn-sm"
                    wire:click="moverCard({{ $card->id }}, {{ $otratlist->id }})">
                    → {{ $otratlist->name }}
                </button>
                @endif
                @endforeach

            </div>
            @endforeach

        </div>
        @endforeach

    </div>

    @if($showOpenCreateCard)
    <div class="modal-background">
        <div class="modal">
            <h2>Crear tarea</h2>
            <label>Título</label>
            <input type="text" wire:model="formCard.title">
            @error('formCard.title')
            <div style="color:red;font-size:0.8rem">{{ $message }}</div>
            @enderror
            <br><br>

            <label>Descripción</label>
            <textarea wire:model="formCard.description"></textarea>
            @error('formCard.description')
            <div style="color:red;font-size:0.8rem">{{ $message }}</div>
            @enderror
            <br><br>

            @if($role === 'Admin')
            <label>Asignar a:</label>
            <select wire:model="formCard.assigned_to">
                <option value="">Sin asignar</option>
                @foreach($empleados as $emp)
                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                @endforeach
            </select>
            <br><br>
            @endif

            @if($board->tags->count())
            <label>Etiquetas:</label>
            <div class="tags-picker">
                @foreach($board->tags as $tag)
                <label class="tag-pill">
                    <input type="checkbox" wire:model="formCard.tags" value="{{ $tag->id }}">
                    <span class="tag-color" style="background-color: {{ $tag->color }}"></span>
                    <span class="tag-name">{{ $tag->name }}</span>
                </label>
                @endforeach
            </div>
            <br>
            @endif

            <button class="btn btn-one" wire:click="storeCard">Crear</button>
            <button class="btn btn-two" wire:click="cancelTask">Cancelar</button>
        </div>
    </div>
    @endif

    @if($showOpenUpdateCard)
    <div class="modal-background">
        <div class="modal">
            <h2>Editar tarea</h2>
            <label>Título</label>
            <input type="text" wire:model="formCard.title">
            <br><br>
            <label>Descripción</label>
            <textarea wire:model="formCard.description"></textarea>
            <br><br>

            @if($role === 'Admin')
            <label>Asignar a</label>
            <select wire:model="formCard.assigned_to">
                <option value="">Sin asignar</option>
                @foreach($empleados as $emp)
                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                @endforeach
            </select>
            <br><br>
            @endif

            @if($board->tags->count())
            <label>Etiquetas:</label>
            <div class="tags-picker">
                @foreach($board->tags as $tag)
                <label class="tag-pill">
                    <input type="checkbox" wire:model="formCard.tags" value="{{ $tag->id }}">
                    <span class="tag-color" style="background-color: {{ $tag->color }}"></span>
                    <span class="tag-name">{{ $tag->name }}</span>
                </label>
                @endforeach
            </div>
            <br>
            @endif

            <button class="btn btn-one" wire:click="updateCard">Guardar</button>
            <button class="btn btn-two" wire:click="cancelTask">Cancelar</button>
        </div>
    </div>
    @endif

    @if($cardComentarios)
    <div class="modal-background">
        <div class="modal">
            <h2>Comentarios</h2>
            <div style="max-height: 250px; overflow-y: auto; margin-bottom: 15px;">
                @foreach($cardComentarios->comments as $com)
                <div style="margin-bottom: 12px;">
                    <strong>{{ $com->user->name }}</strong><br>
                    <span>{{ $com->comment }}</span>
                </div>
                @endforeach
            </div>

            @if($role !== 'Supervisor')
            <label>Nuevo comentario</label>
            <textarea wire:model="nuevoComentario"></textarea>

            <br><br>

            <button class="btn btn-one" wire:click="agregarComentario">
                Añadir comentario
            </button>
            @endif

            <button class="btn btn-two" wire:click="cerrarComentarios">
                Cerrar
            </button>

        </div>
    </div>
    @endif

</div>