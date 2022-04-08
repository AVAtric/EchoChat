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
                        @if (session('status'))
                            <article class="message is-success">
                                <div class="message-body">
                                    {{ session('status') }}
                                </div>
                            </article>
                        @endif
                        <div class="content">
                            <form method="POST" action="{{ route('password.email') }}" id="forgot-password">
                                @csrf
                                <captcha-token captcha_name="validateEmail"></captcha-token>
                                <div class="field">
                                    <label class="label">{{ __('E-Mail Address') }}</label>
                                    <div class="control">
                                        <input name="email" class="input @error('email') is-danger @enderror"
                                               type="email"
                                               value="{{ old('email') }}" autocomplete="email" autofocus>
                                    </div>
                                    @error('email')
                                    <p class="help is-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </form>
                            <br>
                            <small>
                                This site is protected by reCAPTCHA and the Google
                                <a href="https://policies.google.com/privacy">Privacy Policy</a> and
                                <a href="https://policies.google.com/terms">Terms of Service</a> apply.
                            </small>
                        </div>
                    </div>
                    <footer class="card-footer">
                        <a href="#" class="button is-primary" onclick="event.preventDefault();
                                                     document.getElementById('forgot-password').submit();">
                            {{ __('Send Password Reset Link') }}</a>
                    </footer>
                </div>
            </div>
        </div>
    </section>
@endsection
