@extends('layouts.app')



@section('actions')
<!--
<a href="{{ route('emails.create') }}" class="btn btn-primary">Opret E-mail</a>
-->
@endsection



@section('content')

<livewire:table 
    :config="Modules\Email\Tables\MailTemplatesTable::class"
/>

@endsection
