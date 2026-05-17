<section>
    @if($loading)
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only"></span>
        </div>
    @else
        <select class="form-control" wire:model="input">
            <option value="0">(Vælg skabelon)</option>
            @foreach($mailTemplates as $item)
                <option value="{{ json_encode($item) }}">{{ $item['title'] }}</option>
            @endforeach
        </select>
    @endif
</section>
