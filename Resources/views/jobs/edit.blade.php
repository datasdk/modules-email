@extends('layouts.app')

@section('content')

<h2>Rediger E-mail Kampagne</h2>

<form method="POST" action="{{ route('email_campaigns.update', $campaign->id) }}">
    @csrf
    @method('PATCH')

    {{-- E-mail skabelon --}}
    <table class="table">
        <tr>
            <th colspan="2">E-mail kampagne</th>
        </tr>
        <tr>
            <td width="150">Vedhæftet</td>
            <td>
                <select name="template_id" class="form-control">
                    <option value="0">(Vælg skabelon)</option>
                    @foreach($mail_templates as $template)
                        <option value="{{ $template->id }}" {{ $campaign->template_id == $template->id ? 'selected' : '' }}>
                            {{ $template->title }}
                        </option>
                    @endforeach
                </select>
            </td>
        </tr>
    </table>

    {{-- Delivery --}}
    <table class="table">
        <tr>
            <th colspan="2">Delivery</th>
        </tr>
        <tr>
            <td width="150">
                <div>Dag nr.</div>
                <div><small class="text-muted">(1 = samme dag)</small></div>
            </td>
            <td>
                <input type="number" name="send_day" min="1" value="{{ old('send_day', $campaign->send_day) }}" class="form-control">
            </td>
        </tr>
        <tr>
            <td>Send time</td>
            <td>
                <input type="time" name="send_time" value="{{ old('send_time', \Carbon\Carbon::parse($campaign->send_time)->format('H:i')) }}" class="form-control">
            </td>
        </tr>
    </table>

    {{-- Kategorier --}}
    <table class="table">
        <tr>
            <th colspan="2">Kategorier</th>
        </tr>
        <tr>
            <td colspan="2">
                @foreach($categories as $category)
                    <label class="mr-3">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                        {{ in_array($category->id, old('categories', $campaign->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                        {{ $category->name }}
                    </label>
                @endforeach
            </td>
        </tr>
    </table>

    <button type="submit" class="btn btn-primary">Opdater dokument</button>
    <a href="{{ route('module.email_campaigns.index') }}" class="btn btn-secondary">Annuller</a>

</form>

@endsection
