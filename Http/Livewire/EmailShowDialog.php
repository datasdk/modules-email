<?php

namespace Modules\Email\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;

class EmailShowDialog extends Component
{
    public $email;        // Email objekt
    public $open = false; // Kontrollerer dialogen

    public function mount($email)
    {
        $this->email = $email;
    }

    public function toggle()
    {
        $this->open = !$this->open;
    }

    public function formatDate($date)
    {
        return $date ? Carbon::parse($date)->format('d-m-Y H:i') : '';
    }

    public function storageLink($path)
    {
        return '/storage' . $path;
    }

    public function render()
    {
        return view('email::livewire.email-show-dialog');
    }
}
