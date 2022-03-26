@extends('layouts.backend')

<!-- Main Content -->
@section('body')
    <div class="app flex-row align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="card p-4">
                        <div class="card-body">
                            <form action="{{ route('password.email') }}" method="post">
                                @csrf

                                <h1>@lang('labels.user.password_reset')</h1>
                                {{--<div class="form-group">--}}
                                    {{--<label for="email">@lang('validation.attributes.email')</label>--}}
                                    {{--{{ Form::bsEmail('email', null, ['required', 'placeholder' => __('validation.attributes.email')]) }}--}}
                                {{--</div>--}}
                                @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="email">
                                        @lang('validation.attributes.email')
                                    </label>

                                    <input id="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{old('email')}}" required placeholder="{{__('validation.attributes.email')}}" name="email" type="email">

                                    @if ($errors->has('email'))
                                        <div class="invalid-feedback">
                                            <span>{{ $errors->first('email') }}</span>
                                        </div>
                                    @endif
                                </div>

                                <button type="submit" class="btn btn-primary">@lang('labels.user.send_password_link')</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
