@component('mail::message')
{{-- Greeting --}}
@lang('mails.seafarer_contract_singing_request.hello',['vessel' => $contract->vessel->name])

@lang('mails.seafarer_contract_singing_request.message')

@component('mail::panel')

## @lang('mails.seafarer_contract_singing_request.seafarer_info')

<div style="text-align: center">
    <img src="{{ $contract->seafarer->photo_path }}">
</div>

@component('mail::table')
    |                 |               |
    | :-------------- |:--------------|
    | **Name**        | {{$contract->seafarer->full_name}}
    | **Nationality** | {{$contract->seafarer->real_nationality}}
    | **Birth Date**  | {{$contract->seafarer->date_of_birth}}
    | **Birth Place** | {{$contract->seafarer->place_of_birth}}
    | **Rank**        | {{$contract->seafarer->capabilitiesInfo->rank->name}}
@endcomponent

@endcomponent

@lang('mails.seafarer_contract_singing_request.sign_request')

@component('mail::button', ['url' => $link , 'color' => 'blue'])
    Attach
@endcomponent

{{-- Salutations --}}
@lang('mails.layout.regards'), {{ config('app.name') }}

@endcomponent
