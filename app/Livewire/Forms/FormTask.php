<?php

namespace App\Livewire\Forms;

use App\Models\Board;
use App\Models\Tlist;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FormTask extends Form
{
    public ?Board $board = null;
    public ?Tlist $tlist = null;

    #[Validate(['required', 'string', 'min:3', 'max:100'])]
    public string $name = '';

    #[Validate(['nullable', 'string', 'max:20'])]
    public string $color = '#000000ff';

    public function modoCrear(Board $board): void
    {
        $this->board = $board;
        $this->tlist = null;
        $this->name = '';
        $this->color = '#000000ff';
        $this->resetValidation();
    }

    public function modoEditar(Tlist $list): void
    {
        $this->board = $list->board;
        $this->tlist = $list;
        $this->name = $list->name;
        $this->color = $list->color ?? '#000000ff';
        $this->resetValidation();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'color' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function formStore(): Tlist
    {
        $data = $this->validate();
        $data['board_id'] = $this->board->id;

        return Tlist::create($data);
    }

    public function formUpdate(): void
    {
        $data = $this->validate();
        $this->tlist->update($data);
    }

    public function formCancelar(): void
    {
        $this->reset();
        $this->resetValidation();
    }
}
