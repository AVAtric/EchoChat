@extends('layouts.app')

@section('content')
    <div id="login" class="is-fullheight-with-navbar">
        <div class="login-card">

            <div class="card-title">
                <h1>Please Sign In</h1>
            </div>

            <div class="content">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="field">
                        @error('email')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                               placeholder="Email" title="email" name="email" value="{{ old('email') }}" required
                               autocomplete="email" autofocus>
                    </div>

                    <div class="field">
                        @error('password')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                        <input id="password" type="password" class="@error('password') is-danger @enderror"
                               name="password" placeholder="Password" required autocomplete="current-password">
                    </div>

                    <div class="level options">
                        <div class="checkbox level-left">
                            <input class="regular-checkbox" type="checkbox" name="remember"
                                   id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember"></label>
                            <span>{{ __('Remember Me') }}</span>
                        </div>

                        @if (Route::has('password.request'))
                            <a class="btn btn-link level-right" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>
                </form>

                <br>
            </div>
        </div>
    </div>
@endsection
