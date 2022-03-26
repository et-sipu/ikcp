@component('mail::message')
{{-- Greeting --}}
@lang('mails.new_comment_assigned.hello')
<br><br>
@lang('mails.new_comment_assigned.message', ['commented' => $comment->commented->name , 'commentable_type' => $commentable_type])
<br>
@component('mail::panel')

## @lang('mails.new_comment_assigned.title', ['item' => $commentable_type]) : {{$comment->commentable->title}}

## @lang('mails.new_comment_assigned.description') : {{ $comment->comment }}

@endcomponent

@lang('mails.new_comment_assigned.followup_request')

@component('mail::button', ['url' => $link , 'color' => 'blue'])
    Follow Up
@endcomponent

{{-- Salutations --}}
@lang('mails.layout.regards'), {{ config('app.name') }}

@endcomponent
