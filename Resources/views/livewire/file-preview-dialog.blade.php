<section>
    @if(count($attachments) > 0)
        {{-- V-chip, der åbner dialogen --}}
        <span class="badge bg-secondary clickable" wire:click="open">
            Vis filer ({{ count($attachments) }})
        </span>

        @if($show)
            <div class="modal fade show d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Fil Preview</h5>
                            <button type="button" class="close" wire:click="close">&times;</button>
                        </div>

                        <div class="modal-body">
                            @livewire('media.file-preview', ['files' => $attachments])
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="close">Luk</button>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
        @endif
    @endif
</section>

<style>
.clickable {
    cursor: pointer;
}
</style>
