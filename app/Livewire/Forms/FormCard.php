<?php

namespace App\Livewire\Forms;

use App\Models\Card;
use App\Models\Tlist;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FormCard extends Form
{
    public ?Card $card = null;

    #[Validate(['required', 'string', 'min:3', 'max:150'])]
    public string $title = '';

    #[Validate(['required', 'string', 'min:5', 'max:1000'])]
    public string $description = '';

    #[Validate(['required', 'integer'])]
    public ?int $tlist_id = null;

    #[Validate(['nullable', 'integer'])]
    public ?int $assigned_to = null;

    public array $tags = [];

    public function modoCrear(int $tlist): void
    {
        $this->card = null;
        $this->tlist_id = $tlist;
        $this->title = '';
        $this->description = '';
        $this->assigned_to = null;
        $this->tags = [];
        $this->resetValidation();
    }

    public function modoEditar(Card $card): void
    {
        $this->card = $card;
        $this->title = $card->title;
        $this->description = $card->description ?? '';
        $this->tlist_id = $card->tlist_id;
        $this->assigned_to = $card->assigned_to;
        $this->tags = $card->tags->pluck('id')->all();

        $this->resetValidation();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:150'],
            'description' => ['required', 'string', 'min:5', 'max:1000'],
            'tlist_id' => ['required', 'integer'],
            'assigned_to' => ['nullable', 'integer'],
            'tags' => ['array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ];
    }

    public function formStore(): Card
    {
        $datos = $this->validate();
        $tagIds = $this->tags ?? [];

        unset($datos['tags']);

        $card = Card::create($datos);

        if (!empty($tagIds)) {
            $card->tags()->sync($tagIds);
        }

        return $card;
    }

    public function formUpdate(): void
    {
        $datos = $this->validate();
        $tagIds = $this->tags ?? [];

        unset($datos['tags']);

        if ($this->card) {
            $this->card->update($datos);
            $this->card->tags()->sync($tagIds);
        }
    }

    public function formCancelar(): void
    {
        $this->card = null;
        $this->reset(['title', 'description', 'tlist_id', 'assigned_to', 'tags']);
        $this->resetValidation();
    }
}
