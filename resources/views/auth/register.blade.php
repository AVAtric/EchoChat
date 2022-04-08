@extends('layouts.app')

@section('content')
    <section class="section is-fullheight">
        <div class="container">
            <div class="columns is-multiline">
                <div class="card card column is-half is-offset-one-quarter">
                    <header class="card-header">
                        <p class="card-header-title">
                            {{ __('Register') }}
                        </p>
                    </header>
                    <div class="card-content">
                        <div class="content">
                            <form method="POST" action="{{ route('register') }}" id="register">
                                @csrf
                                <div class="field">
                                    <label class="label">{{ __('Name') }}</label>
                                    <div class="control">
                                        <input name="name" class="input @error('name') is-danger @enderror"
                                               type="text"
                                               value="{{ old('name') }}" required autocomplete="name"
                                               autofocus>
                                    </div>
                                    @error('name')
                                    <p class="help is-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="field">
                                    <label class="label">{{ __('E-Mail Address') }}</label>
                                    <div class="control">
                                        <input name="email" class="input @error('email') is-danger @enderror"
                                               type="email"
                                               value="{{ $email ?? old('email') }}" required autocomplete="email">
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
                                               required autocomplete="password">
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
                                               required autocomplete="password">
                                    </div>
                                </div>
                            </form>
                            <br>
                        </div>
                    </div>
                    <footer class="card-footer">
                        <a href="#" class="button is-primary" onclick="event.preventDefault();
                                                     document.getElementById('register').submit();">
                            {{ __('Register') }}</a>
                    </footer>
                </div>
            </div>
        </div>
    </section>
@endsection
