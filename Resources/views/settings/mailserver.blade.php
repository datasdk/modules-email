@extends('layouts.app')

@section('content')
<section>

    <form method="POST" action="{{ route('settings.mailserver.update') }}">
        @csrf
        @method('PATCH')

        {{-- MAILSERVER VALG --}}
        <table class="table">
            <tr><th colspan="2">Mailserver</th></tr>
            <tr>
                <td width="150">Udbyder</td>
                <td>
                    <select name="default" class="form-control">
                        <option value="smtp"
                            {{ (old('default') ?? $config['default']) === 'smtp' ? 'selected' : '' }}>
                            SMTP
                        </option>
                    </select>
                </td>
            </tr>
        </table>


        {{-- ---------------- SMTP FORM ---------------- --}}
        <table class="table">
            <tr><th colspan="2">SMTP</th></tr>

            <tr>
                <td width="150">Aktiv</td>
                <td>
                    <label>
                        <input 
                            type="checkbox"
                            name="smtp[active]"
                            value="1"
                            {{ (old('smtp.active') ?? $config['mailers']['smtp']['active']) ? 'checked' : '' }}
                        >
                        Aktiver mail-server

                        {{ old('smtp.active') }}
                    </label>
                </td>
            </tr>

            <tr>
                <td>Host</td>
                <td>
                    <input type="text"
                           name="smtp[host]"
                           class="form-control"
                           value="{{ old('smtp.host') ?? $config['mailers']['smtp']['host'] }}">
                </td>
            </tr>

            <tr>
                <td>Port</td>
                <td>
                    <input type="text"
                           name="smtp[port]"
                           class="form-control"
                           value="{{ old('smtp.port') ?? $config['mailers']['smtp']['port'] }}">
                </td>
            </tr>

            <tr>
                <td>Username</td>
                <td>
                    <input type="text"
                           name="smtp[username]"
                           class="form-control"
                           value="{{ old('smtp.username') ?? $config['mailers']['smtp']['username'] }}">
                </td>
            </tr>

            <tr>
                <td>Password</td>
                <td>
                    <input type="text"
                           name="smtp[password]"
                           class="form-control password"
                           value="{{ old('smtp.password') ?? $config['mailers']['smtp']['password'] }}">
                </td>
            </tr>

            <tr>
                <td>Encryption</td>
                <td>
                    <select name="smtp[encryption]" class="form-control">
                        @foreach(['tls' => 'TLS (recommended)', 'ssl' => 'SSL', 'starttls' => 'START TLS', '' => 'Ingen'] as $value => $label)
                            <option value="{{ $value }}"
                                {{ (old('smtp.encryption') ?? $config['mailers']['smtp']['encryption']) === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </td>
            </tr>

        </table>


        {{-- ---------------- FRA AFSENDER FORM ---------------- --}}
        <table class="table">
            <tr><th colspan="2">Afsender (reply)</th></tr>

            <tr>
                <td width="150">Navn</td>
                <td>
                    <input type="text"
                           name="from[name]"
                           class="form-control"
                           value="{{ old('from.name') ?? $config['from']['name'] }}">
                </td>
            </tr>

            <tr>
                <td>E-mail</td>
                <td>
                    <input type="text"
                           name="from[address]"
                           class="form-control"
                           value="{{ old('from.address') ?? $config['from']['address'] }}">
                </td>
            </tr>

            <tr>
                <td>Svar til</td>
                <td>
                    <input type="text"
                           name="from[reply_address]"
                           class="form-control"
                           value="{{ old('from.reply_address') ?? $config['from']['reply_address'] }}">
                </td>
            </tr>

        </table>

        <button type="submit" class="btn btn-primary">Gem</button>

    </form>

    {{-- LIVEWIRE SEND TEST MAIL --}}
    <livewire:emails::send-test-mail />

</section>
@endsection
