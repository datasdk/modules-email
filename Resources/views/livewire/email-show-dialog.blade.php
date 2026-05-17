<div>
    <!-- Knappen der åbner dialogen -->
    <button wire:click="toggle" class="btn btn-sm btn-primary">Vis e-mail</button>

    <!-- Dialogen -->
    @if($open)
        <div class="modal-backdrop fade show"></div>
        <div class="modal d-block" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">E-mail detaljer</h5>
                        <button type="button" class="close" wire:click="toggle">&times;</button>
                    </div>

                    <div class="modal-body">
                        @if($email)
                            <div><strong>Til:</strong> {{ $email->to }}</div>
                            <div><strong>Emne:</strong> {{ $email->subject }}</div>
                            <hr>

                            @if($email->message)
                                <div class="email-content">
                                    <strong>Indhold:</strong>
                                    <p>{!! $email->message !!}</p>
                                </div>
                                <hr>
                            @endif

                            @if($email->user)
                                <p><strong>Bruger:</strong> {{ $email->user->first_name }} {{ $email->user->middle_name }} {{ $email->user->last_name }}</p>
                            @endif

                            <p><strong>Sende-dato:</strong> {{ $this->formatDate($email->send_after) }}</p>
                            <p><strong>Status:</strong> {{ $email->status }}</p>
                            <p><strong>Oprettet:</strong> {{ $this->formatDate($email->created_at) }}</p>

                            @if($email->attachments && count($email->attachments))
                                <hr>
                                <strong>Vedhæftede filer:</strong>
                                <ul>
                                    @foreach($email->attachments as $file)
                                        <li>
                                            <a href="{{ $this->storageLink($file->path ?? $file) }}" target="_blank">
                                                {{ $file->name ?? basename($file) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="toggle">Luk</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.modal-backdrop {
    z-index: 1040;
}
.modal {
    z-index: 1050;
}
.email-content p {
    white-space: pre-wrap;
}
</style>
