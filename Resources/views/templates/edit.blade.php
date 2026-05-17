@extends('layouts.app')

@section('content')
<section>

    <form action="{{ isset($template) ? route('templates.update', $template->id) : route('templates.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($template))
            @method('PATCH')
        @endif

        <!-- Skjult slug / navn -->
        <input type="hidden" name="slug" value="{{ old('slug', $template->slug ?? '') }}">

        <table class="table">
            <tr>
                <th colspan="2">E-mail template</th>
            </tr>

            <!-- Emne -->
            <tr>
                <td width="150">Emne</td>
                <td>
                    <input type="text" name="title" class="form-control"
                        value="{{ old('title', $template->subject ?? '') }}">
                </td>
            </tr>

            <!-- Indhold -->
            <tr>
                <td>Indhold</td>
                <td>

                    @livewire("text-editor",[
                        "name" => "html_template",
                        "content" => old('html_template', $template->html_template ?? ''),
                    ])

                </td>
            </tr>

            <!-- Aktiv -->
            <tr>
                <td>Aktiv</td>
                <td>
                    <label>
                        <input type="checkbox" name="active" value="1"
                            {{ old('active', $template->active ?? 1) ? 'checked' : '' }}>
                        <strong>Templaten kan sendes som mail til brugere.</strong>
                        <div class="text-muted">Hvis feltet ikke er markeret, vil mails tilknyttet denne template ikke blive sendt.</div>
                    </label>
                </td>
            </tr>

            <!-- Vedhæftet (valgfri, skjult) -->
            <tr style="display:none">
                <td>Vedhæftet</td>
                <td>
                    <input type="file" name="attachments[]" multiple>
                    @if(!empty($template->attachments))
                        <ul>
                            @foreach($template->attachments as $file)
                                <li><a href="{{ Storage::url($file->url) }}" target="_blank">{{ $file->url }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </td>
            </tr>
        </table>

    

        <br>

        <!-- Handling buttons -->
        <button type="submit" class="btn btn-primary">{{ isset($template) ? 'Opdater dokument' : 'Opret dokument' }}</button>
        <a href="{{ route('templates.index') }}" class="btn btn-default">Annuller</a>

    </form>

</section>
@endsection
