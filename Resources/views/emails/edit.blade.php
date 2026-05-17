@extends('layouts.app')

@section('content')
<h2>Rediger E-mail</h2>

<form method="POST" action="{{ route('module.email_outbox.update', $email->id) }}">
    @csrf
    @method('PATCH')

    {{-- Modtager --}}
    <table class="table">
        <tr>
            <th colspan="2">Modtager</th>
        </tr>
        <tr>
            <td width="150">Til E-mail</td>
            <td>
                <select name="user_id" class="form-control" required>
                    <option value="">Vælg bruger</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $email->user_id == $user->id ? 'selected' : '' }}>
                            {{ $user->first_name }} {{ $user->last_name }}
                        </option>
                    @endforeach
                </select>
            </td>
        </tr>
    </table>

    {{-- Indhold --}}
    <table class="table">
        <tr>
            <th colspan="2">
                Indhold
                <div class="link" data-bs-toggle="modal" data-bs-target="#importTemplateModal">
                    <i class="fas fa-plus mr-2"></i>Importer Skabelon
                </div>
            </th>
        </tr>
        <tr>
            <td width="150">Emne</td>
            <td>
                <input type="text" name="subject" class="form-control" value="{{ old('subject', $email->subject) }}" />
            </td>
        </tr>
        <tr>
            <td>Indhold</td>
            <td>
                <textarea name="message" class="form-control" rows="10">{{ old('message', $email->message) }}</textarea>
            </td>
        </tr>
        <tr>
            <td>Vedhæftet</td>
            <td>
                @if($email->attachments->count())
                    <ul>
                        @foreach($email->attachments as $attachment)
                            <li><a href="{{ Storage::url($attachment->path) }}" target="_blank">{{ $attachment->filename }}</a></li>
                        @endforeach
                    </ul>
                @else
                    <span>Ingen vedhæftede filer</span>
                @endif
            </td>
        </tr>
    </table>

    {{-- Afsendelse --}}
    <table class="table">
        <tr>
            <th colspan="2">Afsendelse</th>
        </tr>
        <tr>
            <td width="150">Afsendelses dato</td>
            <td>
                {{ $email->send_after ? $email->send_after->format('d-m-Y H:i') : 'Ikke sendt endnu' }}
                @if($email->sent)
                    <span class="badge bg-success">SENDT</span>
                @endif
            </td>
        </tr>
        @if($email->sent)
        <tr>
            <td></td>
            <td>
                <button type="button" class="btn btn-default" data-bs-toggle="collapse" data-bs-target="#resendSection">Gensend E-mail</button>
            </td>
        </tr>
        <tr id="resendSection" class="collapse">
            <td></td>
            <td>
                <div class="form-check">
                    <input type="radio" name="deliver_now" value="1" class="form-check-input" checked>
                    <label class="form-check-label">Send nu</label>
                </div>
                <div class="form-check">
                    <input type="radio" name="deliver_now" value="0" class="form-check-input">
                    <label class="form-check-label">Send på et specifikt tidspunkt</label>
                </div>
                <input type="datetime-local" name="send_after" class="form-control mt-2" value="{{ $email->send_after ? $email->send_after->format('Y-m-d\TH:i') : '' }}">
            </td>
        </tr>
    </table>

    <button type="submit" class="btn btn-primary">Send E-mail</button>
    <a href="{{ route('module.email_outbox.index') }}" class="btn btn-secondary">Annuller</a>
</form>

{{-- Modal til import af skabelon --}}
<div class="modal fade" id="importTemplateModal" tabindex="-1" aria-labelledby="importTemplateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Importer skabelon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Luk"></button>
            </div>
            <div class="modal-body">
                <select name="template_id" class="form-control">
                    <option value="">Vælg skabelon</option>
                    @foreach($templat
