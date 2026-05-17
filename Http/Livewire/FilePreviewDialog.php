<?php

namespace Modules\Email\Http\Livewire;

use Livewire\Component;

class FilePreviewDialog extends Component
{
    public $attachments = [];
    public $show = false;

    protected $listeners = ['openDialog' => 'open'];

    public function open()
    {
        $this->show = true;
    }

    public function close()
    {
        $this->show = false;
    }

    public function render()
    {
        return view('media::livewire.file-preview-dialog');
    }
}
