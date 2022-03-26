@component('mail::message')
{{-- Greeting --}}
@lang('mails.layout.hello')

<br><br>
@lang('mails.imo_report.body', ['vessel' => $vessel->name])

{{-- Salutations --}}
@lang('mails.layout.regards'), {{ config('app.name') }}

@endcomponent
