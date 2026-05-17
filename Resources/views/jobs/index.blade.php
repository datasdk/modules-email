@extends('layouts.app')

@section('actions')
@if(request()->query('failed') === 'true')
    <a href="{{ route('email.jobs.index') }}" class="btn btn-default">Vis jobs</a>
@else
    <a href="{{ route('email.jobs.index', ['failed' => true]) }}" class="btn btn-default">Vis mislykkeds jobs</a>
@endif
@endsection

@section('content')

<h1>
    E-mails 
    @if(request()->query('failed') === 'true')
        mislykkeds
    @endif 
    jobs
</h1>
<p>Her er en oversigt over alle dine E-mail, som er sat på automatisk sendeliste. Aktiver cronjob for at afsende E-mails automatisk.</p>

<livewire:table 
    :config="App\Tables\EmailJobsTable::class" 
    :filters="['queue' => 'email']"
/>

@endsection
