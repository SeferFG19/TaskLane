<div class="projects-dashboard">

    <div class="header">
        <h1>Mis proyectos</h1>
        <div class="header-actions">
            <input type="text" class="search" placeholder="Buscar..." wire:model.live="texto">
            <button class="btn btn-one" wire:click="openCreate">
                Crear proyecto
            </button>
        </div>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th class="sortable" wire:click="ordenar('id')">ID</th>
                    <th class="sortable" wire:click="ordenar('name')">Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($projects as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->description }}</td>
                    <td class="actions">

                        @php
                            $board = $p->boards->first();
                        @endphp

                        @if ($board)
                        <a href="{{ route('boards.show', $board->id) }}" class="btn btn-two btn-sm">
                            Ver tablero
                        </a>
                        @endif

                        <button class="btn btn-one btn-sm"
                            wire:click="editar({{ $p->id }})">
                            Editar
                        </button>

                        <button class="btn btn-three btn-sm"
                            wire:click="confirmarBorrar({{ $p->id }})">
                            Borrar
                        </button>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="empty">
                        No tienes proyectos todavía.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $projects->links() }}
    </div>

    @if($openCreate || $openUpdate)
    <div class="modal-background">
        <div class="modal">
            <h2>
                {{ $openCreate ? 'Crear Proyecto' : 'Editar Proyecto' }}
            </h2>

            <label>Nombre</label>
            <input type="text" wire:model="form.name">

            <br><br>

            <label>Descripción</label>
            <textarea wire:model="form.description"></textarea>

            <br><br>

            <div style="display:flex; gap:10px;">
                @if($openCreate)
                <button class="btn btn-one" wire:click="store">
                    Crear
                </button>
                @else
                <button class="btn btn-one" wire:click="update">
                    Guardar cambios
                </button>
                @endif

                <button class="btn btn-two" wire:click="cancelar">
                    Cancelar
                </button>
            </div>

        </div>
    </div>
    @endif

</div>