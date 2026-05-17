@extends('email::layouts.app')

@section('title', 'Vis e-mail')

@section('content')

<div class="min-h-screen  py-10">

    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">


        {{-- Header --}}
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r ">

            <h1 class="text-2xl font-semibold">{{ $email->subject ?? '(Ingen emne)' }}</h1>

            <p class="text-sm opacity-90">
                Sendt til: <strong>{{ $email->to }}</strong>
            </p>

        </div>

        {{-- Body --}}
        <div class="px-6 py-6">

    
    
            {{-- Indhold --}}
            <div class=" pt-4">
             
                <div class="prose max-w-none text-gray-700 bg-gray-50 rounded-lg p-4">
                    {!! $email->message !!}

                </div>
            </div>


            {{-- Vedhæftninger --}}
            @if($email->attachments && count($email->attachments))
                <div class="border-t border-gray-200 mt-6 pt-4">
                    <h2 class="text-lg font-semibold mb-2 text-gray-800">Vedhæftede filer</h2>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($email->attachments as $attachment)
                            <li>
                                <a href="{{ $attachment['url'] }}" target="_blank" class="text-indigo-600 hover:underline">
                                    📎 {{ $attachment['name'] ?? 'Fil' }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif


        </div>

    </div>

</div>

@endsection
