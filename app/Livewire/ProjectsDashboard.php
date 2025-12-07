<?php

namespace App\Livewire;

use App\Livewire\Forms\FormProject;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
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

        // solo filtra los usuarios si no es admin
        $query = Project::query();

        if (!$user->is_admin) {
            $query->where(function ($q) use ($user) {
                $q->whereHas('users', function ($q2) use ($user) {
                    $q2->where('user_id', $user->id);
                })
                    ->orWhere('created_by', $user->id);
            });
        }

        $query->where(function ($q) {
            $q->where('name', 'like', "%{$this->texto}%")->orWhere('description', 'like', "%{$this->texto}%");
        })
            ->orderBy($this->campo, $this->orden);

        $projects = $query->with(['users', 'boards', 'createdBy'])->paginate(12);
        $adminRoleId = Role::where('name', 'Admin')->value('id');

        $usuariosDisponibles = User::where('is_admin', false)->orderBy('name')->get();

        return view('livewire.projects-dashboard', [
            'projects' => $projects,
            'currentUser' => $user,
            'adminRoleId' => $adminRoleId,
            'usuariosDisponibles' => $usuariosDisponibles,
        ]);
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

    // abre el modal de crear
    public function showOpenCreate(): void
    {
        $this->autorizarAdminGlobal();
        $this->openUpdate = false;
        $this->openCreate = true;
        $this->editandoProject = null;
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

        $board = $project->boards()->create([
            'name' => 'Tablero principal',
            'created_by' => Auth::id(),
        ]);

        $board->tlists()->createMany([
            ['name' => 'Importante', 'color' => '#e74c3c'],
            ['name' => 'En curso', 'color' => '#3498db'],
            ['name' => 'Pendiente', 'color' => '#ff7e00'],
            ['name' => 'Revisión', 'color' => '#f1c40f'],
            ['name' => 'Completado', 'color' => '#2ecc71'],
        ]);

        $board->tags()->createMany([
            ['name' => 'Hot Fix',   'color' => '#e74c3c'],
            ['name' => 'FrontEnd',  'color' => '#3498db'],
            ['name' => 'BackEnd',   'color' => '#ff7e00'],
            ['name' => 'Debug',       'color' => '#88d621ff'],
            ['name' => 'Excelencia',    'color' => '#8e44ad'],
        ]);

        $empleadoRole = Role::where('name', 'Empleado')->first();
        if ($empleadoRole && !empty($this->form->employee_ids)) {
            $project->users()->attach(
                $this->form->employee_ids,
                ['role_id' => $empleadoRole->id]
            );
        }

        $this->cancelar();

        $this->dispatch('evtProyectoActualizado');
        $this->dispatch('mensaje', 'Proyecto creado correctamente');
    }

    public function editar(Project $project): void
    {
        $this->autorizarAdminProject($project);
        $this->openCreate = false;
        $this->openUpdate = true;
        $this->editandoProject = $project;
        $this->form->modoEditar($project);
        $empleadoRoleId = Role::where('name', 'Empleado')->value('id');
        $this->form->employee_ids = $project->users()->wherePivot('role_id', $empleadoRoleId)->pluck('users.id')->toArray();
    }

    public function update(): void
    {
        $this->autorizarAdminProject($this->editandoProject);
        $this->form->formUpdate();

        $empleadoRole = Role::where('name', 'Empleado')->first();

        if ($empleadoRole) {
            $project = $this->editandoProject;
            $currentEmpleadoIds = $project->users()->wherePivot('role_id', $empleadoRole->id)->pluck('users.id')->all();

            if (!empty($currentEmpleadoIds)) {
                $project->users()->detach($currentEmpleadoIds);
            }
            $newIds = $this->form->employee_ids ?? [];
            if (!empty($newIds)) {
                $project->users()->attach(
                    $newIds,
                    ['role_id' => $empleadoRole->id]
                );
            }
        }
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

    // verifica si el proyecto existe y si no, salta el error 404
    protected function autorizarAdminProject(?Project $project): void
    {
        abort_if(!$project, 404);

        $user = Auth::user();
        if ($user?->is_admin) {
            return;
        }

        // Busca al usuario en este proyecto y si no está salta 403
        $pivot = $project->users()->where('user_id', $user->id)->first();

        if (!$pivot) {
            abort(403);
        }

        $role = Role::find($pivot->pivot->role_id);
        abort_unless($role && $role->name === 'Admin', 403);
    }
}
