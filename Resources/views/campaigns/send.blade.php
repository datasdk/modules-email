@extends('layouts.app')

@section('content')
<h2>Send E-mail kampagne</h2>

<form method="POST" action="{{ route('module.email_campaigns.send') }}">
    @csrf

    {{-- Modtager --}}
    <table class="table">
        <tr>
            <th colspan="2">Modtager</th>
        </tr>
        <tr>
            <td width="150">Modtager</td>
            <td>
                <select name="user_id" class="form-control" required>
                    <option value="">Vælg bruger</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->first_name }} {{ $user->last_name }}
                        </option>
                    @endforeach
                </select>
            </td>
        </tr>
    </table>

    {{-- Afsendelsesdato --}}
    <table class="table">
        <tr>
            <th colspan="2">Afsendelses datoer</th>
        </tr>
        <tr>
            <td width="150">Fra dato</td>
            <td>
                <input type="date" name="from_date" class="form-control" required>
            </td>
        </tr>
    </table>

    {{-- E-mail kampagne --}}
    <table class="table">
        <tr>
            <th colspan="2">Send E-mail kampagne</th>
        </tr>
        <tr>
            <td width="150">Mail kampagne</td>
            <td>
                <div class="form-group">
                    @foreach($categories as $category)
                        <div class="form-check">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="form-check-input" id="cat_{{ $category->id }}">
                            <label class="form-check-label" for="cat_{{ $category->id }}">{{ $category->name }}</label>
                        </div>
                    @endforeach
                </div>
            </td>
        </tr>
    </table>

    {{-- Indstillinger --}}
    <table class="table">
        <tr>
            <th colspan="2">Indstillinger</th>
        </tr>
        <tr>
            <td width="150">Duplikering</td>
            <td>
                <div class="form-check">
                    <input type="checkbox" name="avoid_duplicate" value="1" class="form-check-input" id="avoid_duplicate">
                    <label class="form-check-label" for="avoid_duplicate">
                        Undlad at sende mail, som brugeren allerede har modtaget
                    </label>
                </div>
            </td>
        </tr>
    </table>

    <button type="submit" class="btn btn-primary">Send kampagne</button>
    <a href="{{ route('module.email_campaigns.index') }}" class="btn btn-secondary">Annuller</a>
</form>

{{-- Modal til oversigt efter sending --}}
@if(session('sent_emails'))
<div class="modal fade" id="sendOverviewModal" tabindex="-1" aria-labelledby="sendOverviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sendOverviewModalLabel">Sending overview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Luk"></button>
      </div>
      <div class="modal-body">
        @if(count(session('sent_emails')) === 0)
            <div class="alert alert-info">Ingen E-mails er sendt</div>
        @else
            @foreach(session('sent_emails') as $content)
                <div class="border border-secondary mb-3 p-3">
                    <div><strong>Template id: {{ $content['template_id'] }}</strong></div>
                    <div>Dag nr.: {{ $content['params']['send_day'] }}</div>
                    <div>Sende dato: {{ $content['send_at_date'] }}</div>
                </div>
            @endforeach
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Luk vindue</button>
      </div>
    </div>
  </div>
</div>

<script>
    // Åbn modal hvis der er sent e-mails
    var sendOverviewModal = new bootstrap.Modal(document.getElementById('sendOverviewModal'));
    sendOverviewModal.show();
</script>
@endif

@endsection
