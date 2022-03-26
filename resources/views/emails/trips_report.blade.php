@component('mail::message')
    {{-- Greeting --}}

    @component('mail::panel')
            @lang('mails.new_comment_assigned.hello')
            <br>
            This email contains of the monthly trips report.
    @endcomponent
@endcomponent
