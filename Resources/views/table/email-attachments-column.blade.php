{{-- ATTACHMENTS --}}
@if($attachments && count($attachments) > 0)

<div class="d-inline-flex flex-wrap gap-1 mb-2">
    @foreach($attachments as $attachment)
        @php
            $fileName = basename($attachment['file_name']);
            $src      = $attachment['src'];
            $fileSize = $attachment['size'] ?? null;
        @endphp

        {{-- Knappen åbner modal --}}
        <button
            type="button"
            class="btn btn-sm btn-light border d-inline-flex align-items-center gap-1"
            data-bs-toggle="modal"
            data-bs-target="#attachmentModal"
            data-src="{{ $src }}"
            data-title="{{ $fileName }}"
        >
            <i class="fas fa-file-pdf text-danger fa-sm mr-2"></i>

            <span class="text-truncate" style="max-width:140px">
                {{ $fileName }}
            </span>
        </button>
    @endforeach
</div>

{{-- BOOTSTRAP MODAL --}}
<div class="modal fade" id="attachmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            {{-- HEADER med filnavn + action knapper --}}
            <div class="modal-header py-2 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="modal-title d-inline me-3"></h6>
                    <small class="text-muted" id="modalFileSize"></small>
                </div>
                <div class="d-flex gap-2">
                    <a id="modalDownload" href="#" target="_blank" class="btn btn-sm btn-outline-primary mr-2">
                        <i class="fas fa-download"></i> Download
                    </a>
                    <a id="modalView" href="#" target="_blank" class="btn btn-sm btn-outline-secondary mr-2">
                        <i class="fas fa-eye"></i> Vis i browser
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
            </div>

            <div class="modal-body p-0" style="height:80vh">
                <embed
                    id="attachmentEmbed"
                    src=""
                    type="application/pdf"
                    style="width:100%; height:100%;"
                >
            </div>

        </div>
    </div>
</div>

{{-- SCRIPT (jQuery) --}}
<script>
$(function() {
    $('#attachmentModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget)
        const src = button.data('src')
        const title = button.data('title')

        // Filnavn i modal
        $(this).find('.modal-title').text(title)

        // Opdater embed src
        $('#attachmentEmbed').attr('src', src)

        // Download & Vis i browser links
        $('#modalDownload').attr('href', "{{ url('media/download') }}/" + encodeURIComponent(title))
        $('#modalView').attr('href', "{{ url('media/show') }}/" + encodeURIComponent(title))
    })

    $('#attachmentModal').on('hidden.bs.modal', function() {
        $('#attachmentEmbed').attr('src', '')
        $('#modalDownload').attr('href', '#')
        $('#modalView').attr('href', '#')
    })
})
</script>

@else
    <span class="text-muted">-</span>
@endif
