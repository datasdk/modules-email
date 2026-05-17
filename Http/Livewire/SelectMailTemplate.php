<?php

namespace Modules\Email\Http\Livewire;

use Livewire\Component;
use Modules\Email\Models\MailTemplate;

class SelectMailTemplate extends Component
{
    public $input; // Vælgeren
    public $asObject = false;
    public $withAttachments = false;
    public $mailTemplates = [];
    public $loading = true;

    public function mount($value = null, $asObject = false, $withAttachments = false)
    {
        $this->input = $value;
        $this->asObject = $asObject;
        $this->withAttachments = $withAttachments;

        $this->loadTemplates();
    }

    public function updatedInput($value)
    {
        $emitValue = $this->asObject ? $value : ($value['id'] ?? null);
        $this->emitUp('input', $emitValue);
    }

    public function loadTemplates()
    {
        $query = MailTemplate::query()->orderBy('title', 'asc');

        if ($this->withAttachments) {
            $query->with('attachments');
        }

        $this->mailTemplates = $query->get()->toArray();
        $this->loading = false;
    }

    public function render()
    {
        return view('email::livewire.select-mail-template');
    }
}
