@extends('layouts.app')

@section('content')
    <section class="section is-fullheight">
        <div class="container">
            <div class="columns is-multiline">
                <div class="card card column is-half is-offset-one-quarter">
                    <header class="card-header">
                        <p class="card-header-title">
                            {{ __('Reset Password') }}
                        </p>
                    </header>
                    <div class="card-content">
                        <div class="content">
                            <form method="POST" action="{{ route('password.update') }}" id="reset-password">
                                @csrf

                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="field">
                                    <label class="label">{{ __('E-Mail Address') }}</label>
                                    <div class="control">
                                        <input name="email" class="input @error('email') is-danger @enderror"
                                               type="email"
                                               value="{{ $email ?? old('email') }}" required autocomplete="email"
                                               autofocus>
                                    </div>
                                    @error('email')
                                    <p class="help is-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="field">
                                    <label class="label">{{ __('Password') }}</label>
                                    <div class="control">
                                        <input name="password" class="input @error('password') is-danger @enderror"
                                               type="password"
                                               required autocomplete="new-password">
                                    </div>
                                    @error('password')
                                    <p class="help is-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="field">
                                    <label class="label">{{ __('Confirm Password') }}</label>
                                    <div class="control">
                                        <input class="input" type="password"
                                               id="password-confirm" name="password_confirmation"
                                               required autocomplete="new-password">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <footer class="card-footer">
                        <a href="#" class="button is-primary" onclick="event.preventDefault();
                                                     document.getElementById('reset-password').submit();">
                            {{ __('Reset Password') }}</a>
                    </footer>
                </div>
            </div>
        </div>
    </section>
@endsection
