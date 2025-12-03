<?php

namespace App\Livewire;

use App\Livewire\Forms\FormProject;
use App\Models\Project;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectsDashboard extends Component
{
    use WithPagination;

    public FormProject $form;

    public bool $openCreate = false;
    public bool $openUpdate = false;

    public string $campo = 'id';
    public string $orden = 'desc';
    public string $texto = '';

    public ?Project $editandoProject = null;

    #[On('evtProyectoActualizado')]
    public function render()
    {
        $user = Auth::user();

        $query = Project::query()
            ->where(function ($q) use ($user) {
                $q->whereHas('users', function ($q2) use ($user) {
                    $q2->where('user_id', $user->id);
                })
                    ->orWhere('created_by', $user->id);
            })
            ->where(function ($q) {
                $q->where('name', 'like', "%{$this->texto}%")
                    ->orWhere('description', 'like', "%{$this->texto}%");
            })
            ->orderBy($this->campo, $this->orden);

        $projects = $query->with('boards')->paginate(8);

        return view('livewire.projects-dashboard', compact('projects'));
    }

    public function ordenar(string $campo): void
    {
        $this->orden = $this->orden === 'asc' ? 'desc' : 'asc';
        $this->campo = $campo;
    }

    public function updatingTexto(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->autorizarAdminGlobal();
        $this->openCreate = true;
        $this->form->modoCrear();
    }

    public function store(): void
    {
        $this->autorizarAdminGlobal();

        $project = $this->form->formStore();

        // asignar automáticamente al creador como Admin del proyecto
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $project->users()->attach(Auth::id(), ['role_id' => $adminRole->id]);
        }

        $this->cancelar();

        $this->dispatch('evtProyectoActualizado');
        $this->dispatch('mensaje', 'Proyecto creado correctamente');
    }

    public function editar(Project $project): void
    {
        $this->autorizarAdminProject($project);

        $this->editandoProject = $project;
        $this->form->modoEditar($project);

        $this->openUpdate = true;
    }

    public function update(): void
    {
        $this->autorizarAdminProject($this->editandoProject);

        $this->form->formUpdate();

        $this->cancelar();

        $this->dispatch('evtProyectoActualizado');
        $this->dispatch('mensaje', 'Proyecto actualizado correctamente');
    }

    public function confirmarBorrar(Project $project): void
    {
        $this->autorizarAdminProject($project);

        $this->dispatch('evtConfirmarBorrarProyecto', $project->id);
    }

    #[On('evtBorrarProyectoOk')]
    public function deleteProject(Project $project): void
    {
        $this->autorizarAdminProject($project);

        $project->delete();

        $this->dispatch('evtProyectoActualizado');
        $this->dispatch('mensaje', 'Proyecto eliminado');
    }

    public function cancelar(): void
    {
        $this->openCreate = false;
        $this->openUpdate = false;
        $this->editandoProject = null;
        $this->form->formCancelar();
    }

    // verifica si es admin y si no, salta el error 403
    protected function autorizarAdminGlobal(): void
    {
        $user = Auth::user();
        abort_unless($user && $user->is_admin, 403);
    }

    // verifica si es admin del proyecto y si no, salta el error 404
    protected function autorizarAdminProject(?Project $project): void
    {
        abort_if(!$project, 404);

        $user = Auth::user();
        if ($user?->is_admin) {
            return;
        }

        // Buscar rol del usuario en este proyecto
        $pivot = $project->users()
            ->where('user_id', $user->id)
            ->first();

        if (!$pivot) {
            abort(403);
        }

        $role = Role::find($pivot->pivot->role_id);
        abort_unless($role && $role->name === 'Admin', 403);
    }
}
