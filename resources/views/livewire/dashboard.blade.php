<div class="dashboard">

    <h1>
        Tablero: {{ $board->name }}
    </h1>

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

    @if($openCreateCard)
        <div class="modal-background">
            <div class="modal">
                <h2>Crear tarea</h2>

                <label>Título</label>
                <input type="text" wire:model="formCard.title">

                <br><br>

                <label>Descripción</label>
                <textarea wire:model="formCard.description"></textarea>

                <br><br>

                <button class="btn btn-one" wire:click="storeCard">Crear</button>
                <button class="btn btn-two" wire:click="cancelTask">Cancelar</button>

            </div>
        </div>
    @endif

    @if($openUpdateCard)
        <div class="modal-background">
            <div class="modal">
                <h2>Editar tarea</h2>

                <label>Título</label>
                <input type="text" wire:model="formCard.title">

                <br><br>

                <label>Descripción</label>
                <textarea wire:model="formCard.description"></textarea>

                <br><br>

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

                <button class="btn btn-two" wire:click="$set('cardComentarios', null)">
                    Cerrar
                </button>

            </div>
        </div>
    @endif

</div>
