@component('mail::message')
{{-- Greeting --}}
@lang('mails.workflowable_passed.hello')
<br><br>

@lang('mails.workflowable_passed.message', ['workflowable' => __('mails.workflowable_to_pass.types.'. $workflowable_type), 'status' => __('labels.backend.'.$workflowable->get_workflowable_name().'.states.'.$workflowable->status) ])
<br><br>

@lang('mails.workflowable_passed.followup_request', ['workflowable' => __('mails.workflowable_to_pass.types.'. $workflowable_type)])

@component('mail::button', ['url' => $link , 'color' => 'blue'])
    Follow Up
@endcomponent

{{-- Salutations --}}
@lang('mails.layout.regards'), {{ config('app.name') }}

@endcomponent
