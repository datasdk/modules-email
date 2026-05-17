@extends('layouts.app')

@section('content')
<section>
    <h2>Rediger E-mail Kampagne: {{ $campaign->template->title ?? '' }}</h2>

    <form method="POST" action="{{ route('email_campaigns.update', $campaign->id) }}">
        @csrf
        @method('PATCH')

        @php
            $input = $campaign ?? [
                'template_id' => 0,
                'send_day' => 1,
                'send_time' => '09:00',
                'categories' => [],
            ];
        @endphp

        {{-- E-mail kampagne --}}
        <table class="table">
            <tr><th colspan="2">E-mail kampagne</th></tr>
            <tr>
                <td width="150">Vedhæftet</td>
                <td>
                    <select name="template_id" class="form-control">
                        <option value="0">(Vælg skabelon)</option>
                        @foreach($mail_templates as $template)
                            <option value="{{ $template->id }}" {{ old('template_id', $input['template_id']) == $template->id ? 'selected' : '' }}>
                                {{ $template->title }}
                            </option>
                        @endforeach
                    </select>
                </td>
            </tr>
        </table>

        {{-- Valgt template detaljer --}}
        @if($input['template_id'] && ($template = collect($mail_templates)->firstWhere('id', $input['template_id'])))
            <table class="table">
                <tr><th colspan="2">E-mail template</th></tr>
                <tr>
                    <td>
                        <div><strong>{{ $template->subject }}</strong></div>
                        <div><small class="text-muted">{{ $template->slug }}</small></div>
                        @if(!empty($template->attachments))
                            <div class="pt-2">
                                <ul>
                                    @foreach($template->attachments as $attachment)
                                        <li>{{ $attachment->url }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </td>
                </tr>
            </table>
        @endif

        {{-- Delivery --}}
        <table class="table">
            <tr><th colspan="2">Delivery</th></tr>
            <tr>
                <td width="150">Dag nr.</td>
                <td>
                    <input type="number" name="send_day" min="1" class="form-control" value="{{ old('send_day', $input['send_day']) }}">
                </td>
            </tr>
            <tr>
                <td>Send time</td>
                <td>
                    <input type="time" name="send_time" class="form-control" value="{{ old('send_time', $input['send_time']) }}">
                </td>
            </tr>
        </table>

        {{-- Categories --}}
        @include('Modules.Categories.Resources.views.select_categories', [
            'type' => 'email_campaigns',
            'checked' => old('categories', $input['categories'] ?? []),
        ])

        <button type="submit" class="btn btn-primary">Opdater kampagne</button>
        <a href="{{ route('module.email_campaigns.index') }}" class="btn btn-secondary">Annuller</a>
    </form>
</section>
@endsection
