@extends('layouts.backend')

@section('body')
    <div class="app flex-row align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card-group">
                        <div class="card p-4">
                            <div class="card-body">
                                <form action="{{ route('login') }}" method="post">
                                    @csrf
                                    <h1>@lang('labels.user.login')</h1>

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



                                    <div class="form-group">
                                        <label for="password">
                                            @lang('validation.attributes.password')
                                        </label>
                                        <input id="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" required="" placeholder="{{__('validation.attributes.password')}}" name="password" value="" type="password">

                                        @if ($errors->has('password'))
                                            <div class="invalid-feedback">
                                                <span>{{ $errors->first('password') }}</span>
                                            </div>
                                        @endif
                                    </div>


                                    @if($isLocked)
                                        <div class="form-group {{ $errors->has('g-recaptcha-response') ? ' has-danger' : '' }}">
                                            {!! NoCaptcha::display() !!}
                                            @if ($errors->has('g-recaptcha-response'))
                                                <div class="invalid-feedback" style="display: block">{{ $errors->first('g-recaptcha-response') }}</div>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="custom-control-label" for="remember">{{ __('Remember Me') }}</label>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <button type="submit" class="btn btn-primary"><i class="fe fe-log-in"></i>&nbsp;@lang('labels.user.login')</button>

                                        <a href="{{ route('password.request') }}" class="ml-auto small">
                                            @lang('labels.user.password_forgot')
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card text-white d-md-down-none" style="width:44%">
                            <img alt="..." style="width: 100%;" src="{{asset('images/ik-login.jpg')}}" class="responsive float-right">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {!! NoCaptcha::renderJs() !!}
@endpush
