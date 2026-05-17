<section>
    <button type="button" wire:click="open" class="btn btn-secondary float-right">Send test mail</button>

    @if($showSendTestMail)
        <div class="modal fade show d-block" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                    <div class="modal-header bg-light">
                        <h5 class="modal-title">Send test mail</h5>
                        <button type="button" class="close" wire:click="close">&times;</button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Angiv den E-mail, som du vil sende en testmail til:</label>
                            <input type="text" class="form-control" wire:model="email" placeholder="Angiv E-mail">
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        @if($msg)
                            <div class="alert alert-danger">
                                <strong>Fejl:</strong>
                                <pre>{{ $msg }}</pre>
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" wire:click="send" wire:loading.attr="disabled">
                            Send E-mail
                        </button>
                        <button type="button" class="btn btn-secondary" wire:click="close">Luk vindue</button>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal-backdrop fade show"></div>
    @endif
</section>
