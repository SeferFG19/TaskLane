<?php

namespace App\Livewire\Forms;

use App\Models\Card;
use App\Models\Tlist;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FormCard extends Form
{
    public ?Card $card = null;
    public ?Tlist $tlist = null;

    #[Validate(['required', 'string', 'min:3', 'max:150'])]
    public string $title = '';

    #[Validate(['required', 'string', 'min:5', 'max:1000'])]
    public string $description = '';

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'min:3', 'max:150'],
            'description' => ['required', 'string', 'min:5', 'max:1000'],
        ];
    }

    public function modoCrear(Tlist $tlist): void
    {
        $this->card  = null;
        $this->tlist = $tlist;

        $this->reset(['title', 'description']);
        $this->resetValidation();
    }

    public function modoEditar(Card $card): void
    {
        $this->card  = $card;
        $this->tlist = $card->tlist ?? null;

        $this->title       = $card->title;
        $this->description = $card->description ?? '';

        $this->resetValidation();
    }

    public function formStore(): Card
    {
        $datos = $this->validate();

        $datos['tlist_id'] = $this->tlist?->id;

        return Card::create($datos);
    }

    public function formUpdate(): void
    {
        $datos = $this->validate();

        if ($this->card) {
            $this->card->update($datos);
        }
    }

    public function formCancelar(): void
    {
        $this->card  = null;
        $this->tlist = null;

        $this->reset(['title', 'description']);
        $this->resetValidation();
    }
}
