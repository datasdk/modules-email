@extends('layouts.app')

@section('actions')
<a href="{{ route('module.email_campaigns.create') }}" class="btn btn-primary">Opret kampagne</a>
<a href="{{ route('module.email_campaigns.send') }}" class="btn btn-default">Send kampagne</a>
@endsection

@section('content')

<livewire:table 
    :config="Modules\Email\Tables\EmailCampaignsTable::class" 
/>

@endsection
