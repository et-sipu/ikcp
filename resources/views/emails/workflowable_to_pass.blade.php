@component('mail::message')
{{-- Greeting --}}
@lang('mails.workflowable_to_pass.hello')
<br><br>

@lang('mails.workflowable_to_pass.message', ['workflowable' => __('mails.workflowable_to_pass.types.'. $workflowable_type)])
<br>

@component('mail::panel')

## @lang('mails.workflowable_to_pass.requester') : {{ $workflowable->requester->name  }}

@endcomponent

@lang('mails.workflowable_to_pass.followup_request', ['workflowable' => __('mails.workflowable_to_pass.types.'. $workflowable_type)])

@component('mail::button', ['url' => $link , 'color' => 'blue'])
    Follow Up
@endcomponent

{{-- Salutations --}}
@lang('mails.layout.regards'), {{ config('app.name') }}

@endcomponent
