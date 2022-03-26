@component('mail::message')
{{-- Greeting --}}
@lang('mails.new_task_assigned.hello',['employee_name' => $task->assigned_department->hod->name])
<br><br>
@lang('mails.new_task_assigned.message')
<br>
@component('mail::panel')

## @lang('mails.new_task_assigned.title') : {{ $task->title  }}

## @lang('mails.new_task_assigned.description') : {{ $task->description  }}

@endcomponent

@lang('mails.new_task_assigned.followup_request')

@component('mail::button', ['url' => $link , 'color' => 'blue'])
    Follow Up
@endcomponent

{{-- Salutations --}}
@lang('mails.layout.regards'), {{ config('app.name') }}

@endcomponent
