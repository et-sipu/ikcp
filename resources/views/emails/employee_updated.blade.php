@component('mail::message')
{{-- Greeting --}}
@lang('mails.employee_updated.hello')
<br><br>
@lang('mails.employee_updated.message')
<br>
@component('mail::panel')
    ## @lang('mails.employee_updated.Employee')  {{$employee_name }}
@endcomponent



@lang('mails.layout.regards'), {{ config('app.name') }}


@endcomponent