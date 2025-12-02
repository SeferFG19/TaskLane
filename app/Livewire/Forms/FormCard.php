<?php

namespace App\Livewire\Forms;

use App\Models\Card;
use App\Models\Tlist;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FormCard extends Form
{
    public ?Tlist $tlist = null;
    public ?Card $card = null;

    #[Validate(['required', 'string', 'min:3', 'max:150'])]
    public string $title = '';

    #[Validate(['nullable', 'string', 'max:1000'])]
    public string $description = '';

    #[Validate(['nullable', 'integer', 'exists:users,id'])]
    public $assigned_to = null;

    public function modoCrear(Tlist $list): void
    {
        $this->tlist = $list;
        $this->card = null;
        $this->title = '';
        $this->description = '';
        $this->assigned_to = null;
        $this->resetValidation();
    }

    public function modoEditar(Card $card): void
    {
        $this->card = $card;
        $this->tlist = $card->list;
        $this->title = $card->title;
        $this->description = $card->description ?? '';
        $this->assigned_to = $card->assigned_to;
        $this->resetValidation();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    public function formStore(): Card
    {
        $data = $this->validate();
        $data['list_id'] = $this->tlist->id;

        return Card::create($data);
    }

    public function formUpdate(): void
    {
        $data = $this->validate();
        $this->card->update($data);
    }

    public function formCancelar(): void
    {
        $this->reset();
        $this->resetValidation();
    }
}
