@extends('email::layouts.app')

@section('title', 'Vis e-mail')

@section('content')

<div class="min-h-screen py-10 bg-gray-50">

    <div class="max-w-3xl mx-auto bg-white rounded-2xl overflow-hidden shadow">

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-white">
            <h1 class="text-2xl font-semibold text-gray-800">{{ $email->subject ?? '(Ingen emne)' }}</h1>
            <p class="text-sm opacity-90 mt-1">
                Sendt til: <strong>{{ $email->to }}</strong>
            </p>
            @if($email->from ?? false)
                <p class="text-sm opacity-70 mt-0.5">
                    Fra: <strong>{{ $email->from }}</strong>
                </p>
            @endif
        </div>

        {{-- Body --}}
        <div class="px-6 py-6">

            {{-- Email-indhold --}}
            <div class="pt-4">
                <div class="prose max-w-none text-gray-700 rounded-lg p-4 bg-gray-50">
                    {!! $email->message !!}
                </div>
            </div>

            {{-- Vedhæftninger --}}
            @if($email->attachments && count($email->attachments))
                <div class="border-t border-gray-200 mt-6 pt-4">
                    <h2 class="text-lg font-semibold mb-2 text-gray-800">Vedhæftede filer</h2>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($email->attachments as $attachment)
                            @php
                                $name = $attachment['name'] ?? 'Fil';
                                $fileName = $attachment['file_name'] ?? 'Fil';

                                if($name !== $fileName){ $name .= " ({$fileName})"; }

                                $fileUrl  = $attachment['src'] ?? '#';
                                $fileSize  = $attachment['size'] ?? null;
                            @endphp
                            <li>
                                <span>
                                    <a 
                                        href="javascript:void(0);" 
                                        onclick="window.open('{{ $fileUrl }}', '{{ addslashes($fileName) }}');"
                                        class="text-indigo-600 hover:underline"
                                    >
                                        {{ $name }}
                                    </a> 
                                    @if($fileSize)
                                        - size: {{ $fileSize }}
                                    @endif
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Luk vindue knap --}}
            <div class="mt-6 text-right">
                <button 
                    type="button" 
                    onclick="window.close()" 
                    class="btn btn-sm btn-secondary"
                >
                    Luk vindue
                </button>
            </div>

        </div>

    </div>

</div>

@endsection
