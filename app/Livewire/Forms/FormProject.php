<?php

namespace App\Livewire\Forms;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FormProject extends Form
{
    public ?Project $project = null;

    #[Validate(['required', 'string', 'min:3', 'max:150'])]
    public string $name = '';

    #[Validate(['required', 'string', 'min:5', 'max:500'])]
    public string $description = '';

    public function modoCrear(): void
    {
        $this->project = null;
        $this->reset(['name', 'description']);
        $this->resetValidation();
    }

    public function modoEditar(Project $project): void
    {
        $this->project = $project;
        $this->name = $project->name;
        $this->description = $project->description;
        $this->resetValidation();
    }

    public function rules(): array
    {
        $id = $this->project?->id ?? 'NULL';

        return [
            'name' => ['required', 'string', 'min:3', 'max:150', "unique:projects,name,{$id}"],
            'description' => ['required', 'string', 'min:5', 'max:500'],
        ];
    }

    public function formStore(): Project
    {
        $datos = $this->validate();

        $datos['created_by'] = Auth::id();

        return Project::create($datos);
    }

    public function formUpdate(): void
    {
        $datos = $this->validate();

        $this->project->update($datos);
    }

    public function formCancelar(): void
    {
        $this->reset();
        $this->resetValidation();
    }
}
