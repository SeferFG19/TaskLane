<?php

namespace App\Livewire;

use App\Livewire\Forms\FormCard;
use App\Livewire\Forms\FormTask;
use App\Models\Board;
use App\Models\Card;
use App\Models\Project;
use App\Models\Role;
use App\Models\Tlist;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Dashboard extends Component
{
    public Board $board;
    public Project $project;

    public FormCard $formCard;
    public FormTask $formTask;

    public bool $openCreateCard = false;
    public bool $openUpdateCard = false;
    public bool $openCreateTask = false;

    public ?Card $editandoCard = null;

    public string $nuevoComentario = '';
    public ?Card $cardComentarios = null;

    public function mount(Board $board): void
    {
        $this->board = $board->load('project');
        $this->project = $this->board->project;
    }

    public function render()
    {
        $board = $this->board
            ->load([
                'tlists.cards.assignedUser',
                'tlists.cards.tags',
                'tlists.cards.comments.user',
                'tags',
            ]);

        $role = $this->roleUserInProject($this->project);

        $empleadoRoleId = Role::where('name', 'Empleado')->value('id');

        // empleados a los que se les puede asignar tareas
        $empleados = User::whereHas('projects', function ($q) use ($empleadoRoleId) {
            $q->where('project_id', $this->project->id)
                ->wherePivot('role_id', $empleadoRoleId);
        })
            ->get();

        return view('livewire.dashboard', [
            'board' => $board,
            'role' => $role,
            'empleados' => $empleados,
        ]);
    }

    public function openCreateTask(): void
    {
        $this->asegurarAdminProject();
        $this->openCreateTask = true;
        $this->formTask->modoCrear($this->board);
    }

    public function storeTask(): void
    {
        $this->asegurarAdminProject();

        $this->formTask->formStore();
        $this->formTask->formCancelar();
        $this->openCreateTask = false;

        $this->board->refresh();

        $this->dispatch('mensaje', 'Tarea creada correctamente');
    }

    public function deleteTask(Tlist $list): void
    {
        $this->asegurarAdminProject();
        $list->delete();
        $this->board->refresh();
        $this->dispatch('mensaje', 'Tarea eliminada');
    }

    public function cancelTask(): void
    {
        $this->openCreateCard = false;
        $this->openUpdateCard = false;

        $this->formCard->formCancelar();
        $this->editandoCard = null;
    }


    public function openCreateCard(Tlist $tlistId): void
    {
        $this->asegurarAdminProject();
        $tlist = Tlist::findOrFail($tlistId);
        $this->openCreateCard = true;
        $this->formCard->modoCrear($tlist);
    }

    public function storeCard(): void
    {
        $this->asegurarAdminProject();
        $this->formCard->formStore();

        $this->openCreateCard = false;
        $this->formCard->formCancelar();
        $this->board->refresh();

        $this->dispatch('mensaje', 'Tarea creada');
    }

    public function editCard(Card $card): void
    {
        $this->asegurarAdminProject();
        $this->editandoCard = $card;
        $this->formCard->modoEditar($card);

        $this->openUpdateCard = true;
    }

    public function updateCard(): void
    {
        $this->asegurarAdminProject();
        $this->formCard->formUpdate();

        $this->openUpdateCard = false;
        $this->formCard->formCancelar();
        $this->editandoCard = null;
        $this->board->refresh();

        $this->dispatch('mensaje', 'Tarea actualizada');
    }

    public function confirmarDeleteCard(Card $card): void
    {
        $this->asegurarAdminProject();
        $this->dispatch('evtConfirmarDeleteCard', $card->id);
    }

    #[On('evtDeleteCardOk')]
    public function deleteCard(Card $card): void
    {
        $this->asegurarAdminProject();
        $card->delete();
        $this->board->refresh();

        $this->dispatch('mensaje', 'Tarea eliminada');
    }

    // quien puede mover tarjetas y a donde
    public function moverCard(Card $card, Tlist $destino): void
    {
        $role = $this->roleUserInProject($this->project);

        // admin
        if ($role === 'Admin') {
            $card->update(['list_id' => $destino->id]);
            $this->board->refresh();
            return;
        }

        // Empleado solo puede mover tareas que le pertenezcan
        if ($role === 'Empleado' && $card->assigned_to === Auth::id()) {
            $card->update(['list_id' => $destino->id]);
            $this->board->refresh();
        }
    }

    // el admin asigna la tarea a un empleado
    public function asignarCard(Card $card, int $userId): void
    {
        $this->asegurarAdminProject();
        $empleado = User::where('id', $userId)->firstOrFail();

        $card->update(['assigned_to' => $empleado->id]);

        $this->board->refresh();

        $this->dispatch('mensaje', 'Tarea asignada correctamente');
    }

    public function abrirComentarios(Card $card): void
    {
        $this->cardComentarios = $card->load('comments.user');
        $this->nuevoComentario = '';
    }

    public function agregarComentario(): void
    {
        if (!$this->cardComentarios) {
            return;
        }

        $role = $this->roleUserInProject($this->project);
        // el supervisor no puede comentar
        if ($role === 'Supervisor') {
            return;
        }

        $this->validate([
            'nuevoComentario' => ['required', 'string', 'min:3', 'max:500'],
        ]);

        $this->cardComentarios->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $this->nuevoComentario,
        ]);

        $this->cardComentarios->refresh();
        $this->nuevoComentario = '';

        $this->dispatch('mensaje', 'Comentario añadido');
    }

    // devuelve el rol del usuario en el proyecto y si no, desconocido
    protected function roleUserInProject(Project $project): string
    {
        $user = Auth::user();

        if ($user?->is_admin) {
            return 'Admin';
        }

        // para que trate al creador del proyecto como admin
        if ($project->created_by === $user->id) {
            return 'Admin';
        }

        $pivot = $project->users()->where('user_id', $user->id)->first();

        if (!$pivot) {
            return 'Desconocido';
        }

        $role = Role::find($pivot->pivot->role_id);

        return $role?->name ?? 'Desconocido';
    }

    // si no es admin del proyecto, salta error 403
    protected function asegurarAdminProject(): void
    {
        $role = $this->roleUserInProject($this->project);
        abort_unless($role === 'Admin', 403);
    }
}
